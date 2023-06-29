<?php
namespace PEUNC\Http;

use PEUNC\Erreur\ServeurException;
use PEUNC\Erreur\Exception;
use PEUNC\Autre\BDD;
use PEUNC\Controleur\Formulaire;
use PEUNC\Http\iHttpRoute;

class HttpRoute implements iHttpRoute
{
	// position dans l'arborescence
	private $alpha;
	private $beta;
	private $gamma;
	private $methode;	// méthode Http
	private $URL;
	private $T_param;
	private $controleur;
	private $fonction;
	private $dureeCache;

	// pour le futur
	private $IP;

	public function __construct()
	{
		// Retrouver tous les composants de la route
		switch ($_GET['serverError']) {
			case null:// sans redirection
				list($this->alpha, $this->beta, $this->gamma) = self::SansRedirection();
				$this->T_param = self::ExtraireParamRacine();
				break;
			
			case 404:// on arrive ici à cause d'une redirection 404 Cf .htaccess
				list($URL, $reste) = explode('?', $_SERVER['REQUEST_URI']);
				list($this->alpha, $this->beta, $this->gamma) = self::Redirection404($URL);
				$this->T_param = self::ExtraireParamURL($reste);
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
		
		$this->URL = $URL;
		$this->methode = $_SERVER['REQUEST_METHOD'];

		/* Dans la table Squelette, on récupère la liste des informations utiles pour la  
		 * construction du controleur puis de la réponse envoyé au client.					 */
		$reponseBDD = BDD::SELECT('paramAutorise, classePage, controleur, dureeCache FROM Squelette WHERE alpha=? AND beta=? AND gamma=? AND methode=?',
								[$this->alpha, $this->beta, $this->gamma, $this->methode],
								true);
		if(is_null($reponseBDD)) throw new ServeurException(404);
		$this->controleur = $reponseBDD['classePage'];
		$this->fonction = $reponseBDD['controleur'];
		$this->dureeCache = $reponseBDD['dureeCache'];

		/* On construit un nouveau tableau qui ne contient que les paramètres autorisées.
		 * Par contre un paramètre manquant ne provoque pas d'erreur. C'est au controleur de décider. */
		$TparamAutorises = json_decode($reponseBDD['paramAutorise'], true);

		$Treponse = [];
		foreach ($TparamAutorises as $clé)
			if (array_key_exists($clé, $this->T_param))	// seules les clés autorisées sont prises en compte
				$Treponse[$clé] = $this->T_param[$clé];	// la valeur a déjà été nettoyée lors de la création de la liste
		$this->T_param = $Treponse;
	}	

//	Renvoie la position dans l'arborescence sous la forme [alpha, beta, gamma] ===================

	private static function Redirection404($URL)
	/* Cette fonction est appelé suite à une erreur 404. C'est cette redirection que j'exploite pour gérer ma pseudo-réécriture d'URL.
	 * Ma source d'inspiration: http://urlrewriting.fr/tutoriel-urlrewriting-sans-moteur-rewrite.htm Merci à son auteur.
	 *
	 * À partir d'une URL, Cette fonction renvoie la position dans l'arborescence du site.
	*/
	{
		header('Status: 200 OK', false, 200);	// modification pour dire au navigateur que tout va bien finalement

		$reponse = BDD::SELECT('alpha, beta, gamma FROM Squelette WHERE URL=? AND methode=?',
								[$URL, $_SERVER['REQUEST_METHOD']],true);
		if(is_null($reponse))	throw new ServeurException(404);
		
		return array($reponse['alpha'], $reponse['beta'], $reponse['gamma']);
	}

	private static function SansRedirection()
	{	/*	Un appel direct de index.php.
			La pseudo réécriture d'URL ne fonctionne pas avec le script action de formulaire.
			J'ai choisi de repasser par index.php pour traiter tous les formulaires.
		*/
		switch($_SERVER['REQUEST_METHOD'])
		{
			case"GET":
				return [0, 0, 0];	// un appel ordinaire vers la page d'accueil
				break;
			case"POST":	// le jeton CSRF contient des infos sur le formuaire notemment sa position dans l'arborescence
				if (!isset($_POST["CSRF"]))	// si le fomulaire ne contient pas de jeton CSRF
					throw new Exception(101);

				$jeton = Formulaire::DecoderJeton($_POST["CSRF"]);

				if (!isset($jeton->noeud))	// si le jeton est invalide
					throw new Exception(102);

				return $jeton->noeud;	// renvoie la position du formulaire
				break;
			default:
				throw new ServeurException(405);// erreur 405!
		}
	}

//	Renvoie les paramètres $_GET ou $_POST nettoyés ==============================================

	private static function ExtraireParamURL($reste)
	/* Extraction des paramètres suite à une redirection 404.
	 * $_GET et $_POST n'existent pas, il faut donc décoder l'URL manuellement.
	 *  n'existe pas dans ce cas.
	 */
	{
		list($paramURL, $ancre) = explode('#', $reste, 2);

		$T_param = [];
		foreach (explode('&', $paramURL) as $instruction)
		{
			$param = explode("=", $instruction);
			if ($param) $T_param[urldecode($param[0])] = strip_tags(urldecode($param[1]));
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
		$T_param = [];
		foreach($tableau as $cle => $valeur)
			$T_param[$cle] = strip_tags($valeur);
		return $T_param;
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

	public function getAlpha()	{ return $this->alpha; }
	public function getBeta()	{ return $this->beta; }
	public function getGamma()	{ return $this->gamma; }
	public function getMethode(){ return $this->methode; }
	public function getURL()	{ return $this->URL; }
	public function getParam($nom = null)
	{
		return (isset($nom)) ?
				$this->T_param[$nom] :
				$this->T_param;
	}
	public function getControleur()	{ return $this->controleur; }
	public function getFonction()	{ return $this->fonction; }
	public function getDureeCache()	{ return $this->dureeCache; }
}
