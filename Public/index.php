<?php	// routeur de PEUNC
session_start();

spl_autoload_register(function($classe)
	{
		if (substr($classe, 0, 5) == "PEUNC")
		{	// PEUNC
			$classe = substr($classe, 6, 99);
			$prefixe = "PEUNC/";
		}
		else $prefixe =  "Modele/classe_"; // utilisateur

		require_once $prefixe . $classe . ".php";
	}
);

try
{
	$route = new PEUNC\HttpRoute;				// à partir d'une requête Http on trouve la route

	PEUNC\Page::SauvegardeEtat($route);			// sauvegarde de l'état courant

	$reponse = new PEUNC\ReponseClient($route);	// construction de la réponse en fonction de la route trouvée
}
catch(PEUNC\ServeurException $e)
{
	$PAGE = new PEUNC\Page();	// il n'y a pas de route
	$PAGE->setTitle("Erreur serveur");
	$PAGE->setHeaderText("<p>Erreur serveur</p>");
	$PAGE->SetSection("<h1>" . $e->getMessage() . " - code: " . $e->getCode() . "</h1>\n");
	$PAGE->setFooter(" - <a href=/Contact>Me contacter</a>");
	$PAGE->setView("erreur.html");
	include $PAGE->getView();
}
catch(PDOException $e)
{
	$PAGE = new PEUNC\Page($route);
	$PAGE->setTitle("Erreur de base de donn&eacute;es");
	$PAGE->setHeaderText("<p>Erreur de base de donn&eacute;es</p>");
	$PAGE->SetSection("<h1>" . $e->getMessage() . "</h1>\n");
	$PAGE->setView("erreur.html");
	include $PAGE->getView();
}
catch(PEUNC\Exception $e)
{
	$PAGE = new PEUNC\Page($route);
	$PAGE->setTitle("Erreur de base de l&apos;application");
	$PAGE->setHeaderText("<p>Erreur de l&paos;application</p>");
	$PAGE->SetSection("<h1>" . $e->getMessage() . "</h1>\n");
	$PAGE->setView("erreur.html");
	include $PAGE->getView();
}
catch(Exception $e)
{
	$PAGE = new PEUNC\Page($route);
	$PAGE->setTitle("Erreur inconnue");
	$PAGE->setHeaderText("<p>Erreur inconnue</p>");
	$PAGE->SetSection("<h1>" . $e->getMessage() . "</h1>\n");
	$PAGE->setView("erreur.html");
	include $PAGE->getView();
}
