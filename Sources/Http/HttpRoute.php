<?php
namespace PEUNC\Http;

use PEUNC\Erreur\ServeurException;
use PEUNC\Erreur\Exception;
use PEUNC\Autre\BDD;
use PEUNC\Autre\JetonCSRF;

class HttpRoute implements iHttpRoute
/**
 * Utilisation de la réécriture d'URL
 **/
{
private $Tchamp = [];	# Liste des champs tirés de la table Squelette voir la première ligne de la requête

private $T_param = [];	# paramètres $_GET ou $_POST

private $server_request_method = 'GET';

public function __construct($URI = null, $methodeHttp = 'GET', $site = 'site') {
	if (is_null($URI)) return;

	if (array_key_exists('serverError', $_GET)) # Cf .htaccess pour redirection des erreurs serveurs
		throw new ServeurException(intval($_GET['serverError']));

	$this->server_request_method = $methodeHttp;
	$T = explode('?', $URI); # T[0] contient l'URL débarassée des paramètres
	
	if(is_null($this->Tchamp = $this->ExtraireDonnéesRoute($T[0], $methodeHttp, $site))) # extraction des données pour la route
		throw new ServeurException(404); # aucune route trouvée
	
	$this->T_param = $this->ListeParametre();
}	

private function ExtraireDonnéesRoute(string $URL, string $methodeHttp, $site) : ?array {
	if (($methodeHttp== 'POST') && ($URL == '/')) { # formulaires traités par index.php
		if (!JetonCSRF::Verifier($_POST['CSRF']))	throw new Exception(101);
							
		$jeton = JetonCSRF::Dechiffre($_POST['CSRF']); # le jeton contient entre autre les coordonées du noeud

		$Tchamp = BDD::SELECT('*
							FROM Vue_route
							WHERE methodeHttp="POST" AND site=? AND alpha=? AND beta=? AND gamma=?'
						, $jeton['noeud'] # renvoie la position du formulaire dans l'arborescence
						, true);
	} elseif ($URL == '/') {
		$Tchamp = BDD::SELECT('*
						FROM Vue_route
						WHERE methodeHttp=? AND site=? AND alpha=0 AND beta=0 AND gamma=0'
					, [$methodeHttp, $site], true);
	} else {
		$Tchamp = BDD::SELECT('* FROM Vue_route WHERE methodeHttp=? AND site=? AND URL=?', [$methodeHttp, $site, $URL], true);
	}
	return $Tchamp;
}
private function ListeParametre() : array {
	switch ($this->server_request_method) {
		case"GET":
			$TparamTransmis = $_GET;
			break;
		case"POST":
			$TparamTransmis = $_POST;
			break;
		default:
			throw new Exception(100); # méthode http inconnue
	}
	
	$T_param = [];
	# nettoyage des paramètres
	$TparamAutorises = json_decode($this->Tchamp['paramAutorise'], true);
	# À FAIRE: si $this->Tchamp['paramAutorise'] est mal encodé alors le résultat est vide => lancer une exception?
	foreach ($TparamAutorises as $clé)
		if (array_key_exists($clé, $TparamTransmis))	# seules les clés autorisées sont prises en compte
			$T_param[$clé] = strip_tags($TparamTransmis[$clé]);	# la valeur est nettoyée
	return $T_param;
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

public function getSite()		{ return $this->Tchamp['site']; }
public function getAlpha()		{ return $this->Tchamp['alpha']; }
public function getBeta()		{ return $this->Tchamp['beta']; }
public function getGamma()		{ return $this->Tchamp['gamma']; }
public function getMethodeHttp(){ return $this->Tchamp['methodeHttp']; }
public function getURL()		{ return $this->Tchamp['URL']; }
public function getControleur()	{ return $this->Tchamp['controleur']; }
public function getFonction()	{ return $this->Tchamp['methodeControleur']; }
public function getDureeCache()	{ return $this->Tchamp['dureeCache']; }
public function getTitre()		{ return $this->Tchamp['titre']; }

public function getParam($nom = null) { # renvoie les paramètres $_GET, $_POST suivant les cas
	if (is_null($nom))
		return $this->T_param;		# tout le tableau

	if (array_key_exists($nom, $this->T_param))
		return $this->T_param[$nom];# le paramètre demandé

	return null;					# rien
}
}
