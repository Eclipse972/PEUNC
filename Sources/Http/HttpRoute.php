<?php
namespace PEUNC\Http;

use PEUNC\Erreur\ServeurException;
use PEUNC\Erreur\Exception;
use PEUNC\Autre\BDD;
use PEUNC\Autre\JetonCSRF;

class HttpRoute implements iHttpRoute
/* J'utilise une pseudo-réécriture d'URL qui exploite la redirection 404 Cf .htaccess
 * Ma source d'inspiration: http://urlrewriting.fr/tutoriel-urlrewriting-sans-moteur-rewrite.htm Merci à son auteur.
 */
{
private $Tchamp;
/** Liste des champs tirés de la table Squelette
 * alpha position dans l'arborescence
 * beta
 * gamma
 * methodeHttp
 * URL
 * titre
 * controleur
 * fonction
 * dureeCache;
 */

private $T_param;	// paramètres $_GET ou $_POST

public function __construct($URI = null)
{
	if (is_null($URI))
	{
		$this->T_param = [];
		return;
	}

	// décodage URI
	$T = explode('?', $URI);	// T[1] contient ce qu'il y a après le ?
	$URL = $T[0];
	if (count($T) > 1)
	{
		$T2 = explode('#', $T[1]);
		$paramURL = $T2[0];
	}
	else $paramURL = '';

	// construire la requête pour retrouver tous les composants de la route
	if (array_key_exists('serverError', $_GET))
		switch ($_GET['serverError'])	// une erreur serveur a été redirigée vers index.php Cf .htaccess
		{
			case 404:
				header('Status: 200 OK', false, 200);	// modification pour dire au navigateur que tout va bien finalement
				$clauseWhereRequeteRoute = 'URL=? AND methodeHttp=?';
				$TparamRequeteRoute = [$URL, $_SERVER['REQUEST_METHOD']];
				break;
			case 403:
			case 405:
			case 500:
				throw new ServeurException((int)$_GET['serverError']);
				break;
			default:
				throw new Exception('Erreur de route'); // .htaccess est à vérifier
				break;
		}
	else list($clauseWhereRequeteRoute, $TparamRequeteRoute) = self::SansRedirection();	// pas d'erreur serveur

	// extraction des données de la table Squelette
	$this->Tchamp = BDD::SELECT('alpha, beta, gamma, URL, methodeHttp, titre, paramAutorise, controleur, methodeControleur, dureeCache
									FROM Squelette WHERE ' . $clauseWhereRequeteRoute,
								$TparamRequeteRoute, true);
	if(is_null($this->Tchamp)) throw new ServeurException(404);
	
	// construction de la liste des paramètres
	$TparamAutorises = json_decode($this->Tchamp['paramAutorise'], true);
	$TparamTransmis = ($URL == '/') || ($URL == '/home') ? // on est à la racine
					self::ExtraireParamRacine() :
					self::ExtraireParamURL($paramURL);
	$this->T_param = [];
	foreach ($TparamAutorises as $clé)
		if (array_key_exists($clé, $TparamTransmis))	// seules les clés autorisées sont prises en compte
			$this->T_param[$clé] = strip_tags($TparamTransmis[$clé]);	// la valeur est nettoyée
}	

private static function SansRedirection()
{
	switch($_SERVER['REQUEST_METHOD'])
	{
		case 'GET':
			$Tparam = [0, 0, 0];	// un appel ordinaire vers la page d'accueil
			break;
		case 'POST':
			/* La pseudo réécriture d'URL ne fonctionne pas avec le script action de formulaire.
			 * J'ai choisi de repasser par index.php pour traiter tous les formulaires.
			 * le jeton CSRF contient des infos sur le formuaire notemment sa position dans l'arborescence
			 */
			if (!JetonCSRF::Verifier($_POST['CSRF']))
				throw new Exception(101);
							
			$jeton = JetonCSRF::Dechiffre($_POST['CSRF']);
			$Tparam = $jeton['noeud'];// renvoie la position du formulaire dans l'arborescence
			break;
		default:
			throw new ServeurException(405);// erreur 405!
	}
	$Tparam[] = $_SERVER['REQUEST_METHOD'];
	return array('alpha=? AND beta=? AND gamma=? AND methodeHttp=?',$Tparam);
}

//	Renvoie les paramètres $_GET ou $_POST nettoyés ==============================================

private static function ExtraireParamURL($paramURL)
/* Extraction des paramètres suite à une redirection 404.
	* $_GET et $_POST n'existent pas, il faut donc décoder l'URL manuellement.
	*/
{
	$T_param = [];
	foreach (explode('&', $paramURL) as $instruction)
	{
		$param = explode("=", $instruction);
		if (count($param) == 2)
			$T_param[urldecode($param[0])] = urldecode($param[1]);
	}
	return $T_param;
}

private static function ExtraireParamRacine()
{	// renvoie les paramètres envoyés à index.php
	switch($_SERVER['REQUEST_METHOD'])
	{
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

public static function SauvegardeEtat(HttpRoute $route)
{
	$URLactuelle = $route->getURL();

	if ($_SESSION['PEUNC']['URL'] != $URLactuelle) // sauvagarde s'il n'y a pas rafraichiisemnt de page
	{
		$_SESSION['PEUNC']['URLprecedente'] = (isset($_SESSION['PEUNC']['URL'])) ? $_SESSION['PEUNC']['URL'] : '/';

		$_SESSION['PEUNC']['URL'] =	$URLactuelle;
	}
}

public static function URLprecedente()	{ return $_SESSION['PEUNC']['URLprecedente']; }

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

public function getParam($nom = null)	// renvoie les paramètres $_GET, $_POST suivant les cas
{
	if (is_null($nom))
		return $this->T_param;		// tout le tableau
	elseif (array_key_exists($nom, $this->T_param))
		return $this->T_param[$nom];// le paramètre demandé
	else return null;				// rien
}
}
