<?php	# controleur principal
session_start();

use PEUNC\Http\ReponseClient;
use PEUNC\Http\HttpRoute;
use PEUNC\Http\RequeteHttp;
use PEUNC\Http\Routeur;
use PEUNC\Erreur\Exception as PEUNC_Exception;
use PEUNC\Erreur\ServeurException;
use VolEval\Controleur\ErreurControleur;

include 'autoloader.php';

try
{
	if (array_key_exists('serverError', $_GET)) # Cf .htaccess pour redirection des erreurs serveurs
		throw new ServeurException(intval($_GET['serverError']));

	$requete = new RequeteHttp;

	$routeur = new Routeur('VolEval\Controleur');
	include 'Application/Configuration/routes.php';
	$T = $routeur->TrouveChemin($requete);

	$controleurNom = $T['controleur'];
	$controleurMethode = $T['fonction'];
	$route = $T['route'];
}
catch(ServeurException $e)
{
	$route = new HttpRoute($requete, []);
	$controleurNom = 'Serveur';
	$controleurMethode = 'Serveur';
}
catch(PDOException $e)
{
	$controleurNom = 'PDO';
	$controleurMethode = 'BDD';
}
catch(PEUNC_Exception $e)
{
	$controleurNom = 'PEUNC';
	$controleurMethode = 'Application';
}
catch(Exception $e)
{
	$controleurNom = 'Exception';
	$controleurMethode = 'Exception';
}

# Création du controleur
if (in_array($controleurNom, ['Serveur', 'PDO', 'PEUNC', 'Exception'])) {
	$controleur = new ErreurControleur($route, $e->getMessage(), $e->getCode());
} else $controleur = new $controleurNom($route);

$controleur->$controleurMethode(); # application de la méthode au controleur

ReponseClient::Absorbe($controleur); # préparation de la réponse envoyée au client à partir du controleur
include ReponseClient::View(); # insertion de la vue
