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

	if (array_key_exists('serverError', $_GET)) { # Cf .htaccess pour redirection des erreurs serveurs
		$erreurServeur = intval($_GET['serverError']);
		if($erreurServeur != 404) throw new ServeurException($erreurServeur);
	} else $erreurServeur = null;

	$this->server_request_method = $methodeHttp;

	list($URL, $paramURL) = $this->DecodageURI($URI);

	# construire la requête pour retrouver tous les composants de la route
	if ($erreurServeur == 404) {
		header('Status: 200 OK', false, 200); # modification pour dire au navigateur que tout va bien finalement
		$clauseWhereRequeteRoute = 'URL=?';
		$TparamRequeteRoute = [$URL];
	} else list($clauseWhereRequeteRoute, $TparamRequeteRoute) = $this->SansRedirection(); # pas d'erreur serveur

	array_unshift($TparamRequeteRoute, $this->server_request_method, $site);	# ajout de deux paramètres en premier
	
	# extraction des données pour la route
	$this->Tchamp = BDD::SELECT('* FROM Vue_route WHERE methodeHttp=? AND site=? AND ' . $clauseWhereRequeteRoute, $TparamRequeteRoute, true);
	if(is_null($this->Tchamp)) throw new ServeurException(404);
	
	$this->T_param = $this->ListeParametre($paramURL);
}	

private function DecodageURI($URI) : array {
	$T = explode('?', $URI); # T[1] contient ce qu'il y a après le ?
	$URL = $T[0];
	if (count($T) > 1) {
		$T2 = explode('#', $T[1]);
		$paramURL = $T2[0];
	}
	else $paramURL = '';
	return [$URL, $paramURL];
}

private function ListeParametre($paramURL) : array {
	$TparamAutorises = json_decode($this->Tchamp['paramAutorise'], true);
	# À FAIRE: si $this->Tchamp['paramAutorise'] est mal encodé alors le résultat est vide => lancer une exception?
	$TparamTransmis = ($this->server_request_method == 'POST') ?
					$this->ExtraireParamRacine() :
					self::ExtraireParamURL($paramURL);
	$T_param = [];
	foreach ($TparamAutorises as $clé)
		if (array_key_exists($clé, $TparamTransmis))	// seules les clés autorisées sont prises en compte
			$T_param[$clé] = strip_tags($TparamTransmis[$clé]);	// la valeur est nettoyée
	return $T_param;
}

private function SansRedirection() : array {
	switch($this->server_request_method) {
		case 'GET':
			$Tparam = [0, 0, 0];	// un appel ordinaire vers la page d'accueil
			break;
		case 'POST':
			/* La pseudo réécriture d'URL ne fonctionne pas avec le script action de formulaire.
			 * J'ai choisi de repasser par index.php pour traiter tous les formulaires.
			 * le jeton CSRF contient des infos sur le formulaire notemment sa position dans l'arborescence
			 */
			if (!JetonCSRF::Verifier($_POST['CSRF']))
				throw new Exception(101);
							
			$jeton = JetonCSRF::Dechiffre($_POST['CSRF']);
			$Tparam = $jeton['noeud'];// renvoie la position du formulaire dans l'arborescence
			break;
		default:
			throw new ServeurException(405);// erreur 405!
	}
	return array('alpha=? AND beta=? AND gamma=?',$Tparam);
}

//	Renvoie les paramètres $_GET ou $_POST nettoyés ==============================================

private static function ExtraireParamURL($paramURL) : array {
	/**
	 * Extraction des paramètres suite à une redirection 404.
	 * $_GET et $_POST n'existent pas, il faut donc décoder l'URL manuellement.
	 **/
	$T_param = [];
	foreach (explode('&', $paramURL) as $instruction) {
		$param = explode("=", $instruction);
		if (count($param) == 2)
			$T_param[urldecode($param[0])] = urldecode($param[1]);
	}
	return $T_param;
}

private function ExtraireParamRacine() : array { # renvoie les paramètres envoyés à index.php
	switch($this->server_request_method) {
		case"GET":
			$tableau = $_GET;
			break;
		case"POST":
			$tableau = $_POST;
			break;
		default:
			throw new Exception(100);
	}
	return $tableau;
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
