<?php
namespace PEUNC\Http;

use PEUNC\Erreur\ServeurException;
use PEUNC\Erreur\Exception;
use PEUNC\Autre\BDD;
use PEUNC\Autre\JetonCSRF;
use PEUNC\Http\RequeteHttp;

class HttpRoute implements iHttpRoute {
private $url;
private $T_param = [];	# paramètres transmis par la requête

public function __construct(RequeteHttp $requete, array $TparamAutorises) {
	$this->url = $requete->getURL();
	$TparamTransmis = $requete->getParam();
	$this->T_param = [];
	# nettoyage des paramètres
	foreach ($TparamAutorises as $clé)
		if (array_key_exists($clé, $TparamTransmis))	# seules les clés autorisées sont prises en compte
			$this->T_param[$clé] = strip_tags($TparamTransmis[$clé]);	# la valeur est nettoyée
}	

public static function SauvegardeEtat(HttpRoute $route) : void {
	$URLactuelle = $route->getURL();

	if ($_SESSION['PEUNC']['URL'] != $URLactuelle) // sauvagarde s'il n'y a pas rafraichiisemnt de page
	{
		$_SESSION['PEUNC']['URLprecedente'] = (isset($_SESSION['PEUNC']['URL'])) ? $_SESSION['PEUNC']['URL'] : '/';

		$_SESSION['PEUNC']['URL'] =	$URLactuelle;
	}
}

public static function URLprecedente() : string	{
	return $_SESSION['PEUNC']['URLprecedente'];
}

//	Accesseurs ===================================================================================

public function getURL()		{ return $this->url; }

public function getParam($nom = null) { # renvoie les paramètres $_GET, $_POST suivant les cas
	if (is_null($nom))
		return $this->T_param;		# tout le tableau

	if (array_key_exists($nom, $this->T_param))
		return $this->T_param[$nom];# le paramètre demandé

	return null;					# rien
}
}
