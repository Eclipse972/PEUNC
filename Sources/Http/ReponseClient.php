<?php
namespace PEUNC\Http;

use PEUNC\Erreur\Exception;
use PEUNC\Autre\BDD;
use PEUNC\Macro\Balise;
use PEUNC\Controleur\Page;

class ReponseClient implements iReponseClient
/**
 * Réponse à servir au client en fonction de la route trouvée suite à la requête http.
 * Classe nécesaire: HttpRoute chargée par l'autoloader
 **/
{
private static $instance;

protected $controleur;

private function __construct()
{
	// rien à faire a part avoir le statut privé pour empêcher l'instanciation de la classe
}

private static function getInstance()
{
	if (is_null(self::$instance))	self::$instance = new ReponseClient;

	return self::$instance;
}

// Implémentation de l'interface =====================================================================
public static function Absorbe($controleur)
{
	$reponse = ReponseClient::getInstance();// récupération instance
	$reponse->controleur = $controleur;		// modification
	self::$instance = $reponse;				// sauvegarde
}

public static function View()
{
	return ReponseClient::getInstance()->controleur->getVue();
}

public static function CSS()
{
	$Liste = ReponseClient::getInstance()->controleur->getCSS();
	foreach($Liste as $feuilleCSS)
		echo "\t" , '<link rel="stylesheet" href="', $feuilleCSS, '"/>', "\n";
}

public static function Menu()
{
	$code = '';
	$nbTabulation = 0;
	$Liste = ReponseClient::getInstance()->controleur->getNav();
	foreach ($Liste as $ligne)
	{
		if($ligne == '</ul>') $nbTabulation--;
		$code .= str_repeat("\t", $nbTabulation) . $ligne . "\n";
		if($ligne == '<ul>') $nbTabulation++;
	}
	return $code;
}

public static function Element($nom)
{
	return ReponseClient::getInstance()->controleur->get($nom);
}

public static function Existe($nom)
{
	return array_key_exists($nom, ReponseClient::getInstance()->controleur->get());
}
// Fin de l'implémentation de l'interface ============================================================
}

