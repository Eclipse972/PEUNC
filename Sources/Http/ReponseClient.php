<?php
namespace PEUNC\Http;

use PEUNC\Erreur\Exception;
use PEUNC\Autre\BDD;
use PEUNC\Macro\Balise;
use PEUNC\Controleur\BaseControleur;

class ReponseClient implements iReponseClient
/**
 * Réponse à servir au client en fonction de la route trouvée suite à la requête http.
 * Classe nécesaire: HttpRoute chargée par l'autoloader
 **/
{
	# À FAIRE: utiliser la syntaxe de PHP8 pour la déclaration des fonctions
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
public static function Absorbe(BaseControleur $controleur)
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

public static function Menu() {
	return implode("\n", ReponseClient::getInstance()->controleur->getNav()) . "\n";
}

public static function Element($nom)
{
	return ReponseClient::getInstance()->controleur->get($nom);
}

public static function Existe($nom)
{
	return array_key_exists($nom, ReponseClient::getInstance()->controleur->get());
}

public static function Message(string $nomMessage) : string
{
	if (array_key_exists($nomMessage, $_SESSION)) {
		$codeHTML = "<p style=\"color:red\">{$_SESSION[$nomMessage]}</p>\n";
		unset($_SESSION[$nomMessage]); # suppression du message dans la session
		return $codeHTML;
	} else return '';
}
// Fin de l'implémentation de l'interface ============================================================
}

