<?php	// routeur de PEUNC
session_start();

spl_autoload_register(function($classe)
	{	// $classe contient le namespace qui est aussi le chemin vers le fichier
		$TcheminFichier = explode('\\', $classe);

		switch($TcheminFichier[0]) // inspection du premier élément
		{
			case 'PEUNC':
				break;
			/* Exemple pour le futur
			case 'VolEval':
				$TcheminFichier[0] = 'Application';
				break;
			*/
			default:
				throw new PEUNC\Erreur\Exception('Namespace inconnu: ' . $TcheminFichier[0]);
		}
		$fichier = implode('/', $TcheminFichier) . '.php';
		if(!file_exists($fichier)) throw new PEUNC\Erreur\Exception('autoloader. fichier=' . $fichier);
		require_once $fichier;
	}
);

try
{
	$route = new PEUNC\Http\HttpRoute;				// à partir d'une requête Http on trouve la route

	PEUNC\Controleur\Page::SauvegardeEtat($route);	// sauvegarde de l'état courant

	$reponse = new PEUNC\Http\ReponseClient($route);// construction de la réponse en fonction de la route trouvée
	$PAGE = $reponse->Page();
}
catch(PEUNC\Erreur\ServeurException $e)
{
	$PAGE = new PEUNC\Controleur\Erreur();	// il n'y a pas de route
	$PAGE->setTitle("Erreur serveur");
	$PAGE->setHeaderText("<p>Erreur serveur</p>");
	$PAGE->setSection("<h1>" . $e->getMessage() . " - code: " . $e->getCode() . "</h1>\n<img src=/images/serveur.png style=\"width:300px\" alt=\"Logo\" >");
	$PAGE->setFooter("");
	$PAGE->setView("doctype.html");
}
catch(PDOException $e)
{
	$PAGE = new PEUNC\Controleur\Erreur($route);
	$PAGE->setTitle("Erreur de BDD");
	$PAGE->setHeaderText("<p>Erreur de base de donn&eacute;es</p>");
	$PAGE->setSection("<p>" . $e->getMessage() . "</p>\n" . $PAGE->NoeudArborescence());
	$PAGE->setFooter("");
	$PAGE->setView("doctype.html");
}
catch(PEUNC\Erreur\Exception $e)
{
	$PAGE = new PEUNC\Controleur\Erreur($route);
	$PAGE->setTitle("Erreur de l&apos;application");
	$PAGE->setHeaderText("<p>Erreur de l&apos;application</p>");
	$PAGE->setSection("<h1>" . $e->getMessage() . "</h1>\n" . $PAGE->NoeudArborescence());
	$PAGE->setFooter("");
	$PAGE->setView("doctype.html");
}
catch(Exception $e)
{
	$PAGE = new PEUNC\Controleur\Erreur($route);
	$PAGE->setTitle("Erreur inconnue");
	$PAGE->setHeaderText("<p>Erreur inconnue</p>");
	$PAGE->SetSection("<h1>" . $e->getMessage() . "</h1>\n" . $PAGE->NoeudArborescence());
	$PAGE->setFooter("");
	$PAGE->setView("doctype.html");
}

include $PAGE->getView(); // affichage de la vue (réponse client ou page d'erreur)
