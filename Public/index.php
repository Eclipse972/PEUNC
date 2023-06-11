<?php	// routeur de PEUNC
session_start();

spl_autoload_register(
	function($classe)
	{
		/*	ancienne version 
		if (substr($classe, 0, 5) == "PEUNC")
		{	// PEUNC
			$classe = substr($classe, 6, 99);
			$prefixe = "PEUNC/";
		}
		else $prefixe =  "Modele/classe_"; // utilisateur

		require_once $prefixe . $classe . ".php";
		*/
		
		// $classe contient le namespace et ce namespace est le chemin vers le fichier
		$TcheminFichier = explode('\\', $classe);

		if ($TcheminFichier[0] != 'PEUNC') // classe utilisateur
		{	// 2 sources de classes pour le moment PEUNC et utilisateur. Il faudra reprendre lorsqu'il faudra gérer les librairies
			$TcheminFichier = array_unshift($TcheminFichier, 'Modele'); // ajout dossier contenant les classes utilisateur devant
		}

		$fichier = implode('/', $TcheminFichier) . '.php';	// test existence à faire
		require_once $fichier;
	}
);

try
{
	$route = new PEUNC\HttpRoute;				// à partir d'une requête Http on trouve la route

	PEUNC\Page::SauvegardeEtat($route);			// sauvegarde de l'état courant

	$reponse = new PEUNC\ReponseClient($route);	// construction de la réponse en fonction de la route trouvée
	$PAGE = $reponse->Page();
}
catch(PEUNC\ServeurException $e)
{
	$PAGE = new PEUNC\Erreur();	// il n'y a pas de route
	$PAGE->setTitle("Erreur serveur");
	$PAGE->setHeaderText("<p>Erreur serveur</p>");
	$PAGE->setSection("<h1>" . $e->getMessage() . " - code: " . $e->getCode() . "</h1>\n");
	$PAGE->setFooter(" - <a href=/Contact>Me contacter</a>");
	$PAGE->setView("erreur.html");
}
catch(PDOException $e)
{
	$PAGE = new PEUNC\Erreur($route);
	$PAGE->setTitle("Erreur de base de donn&eacute;es");
	$PAGE->setHeaderText("<p>Erreur de base de donn&eacute;es</p>");
	$PAGE->setSection("<h1>Erreur de base de donn&eacute;es</h1>\n<p>" . $e->getMessage() . "</p>");
	$PAGE->setView("erreur.html");
}
catch(PEUNC\Exception $e)
{
	$PAGE = new PEUNC\Erreur($route);
	$PAGE->setTitle("Erreur de base de l&apos;application");
	$PAGE->setHeaderText("<p>Erreur de l&apos;application</p>");
	$PAGE->setSection("<h1>" . $e->getMessage() . "</h1>\n");
	$PAGE->setView("erreur.html");
}
catch(Exception $e)
{
	$PAGE = new PEUNC\Erreur($route);
	$PAGE->setTitle("Erreur inconnue");
	$PAGE->setHeaderText("<p>Erreur inconnue</p>");
	$PAGE->SetSection("<h1>" . $e->getMessage() . "</h1>\n");
	$PAGE->setView("erreur.html");
}

include $PAGE->getView(); // affichage de la vue (réponse client ou page d'erreur)
