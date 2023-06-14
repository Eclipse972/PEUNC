<?php
namespace PEUNC\Http;

use PEUNC\Erreur\ServeurException;
use PEUNC\Erreur\Exception;
use PEUNC\Autre\BDD;

include"API_HttpRoute.php";

class HttpRoute implements iHttpRoute
{
	// position dans l'arborescence
	private $alpha;
	private $beta;
	private $gamma;
	private $methode;	// méthode Http
	private $URL;
	private $T_param;

	// pour le futur
	private $IP;

	public function __construct()
	{
		// recherche de la position dans l'arborescence stockée en BD
		switch($_SERVER['REDIRECT_STATUS'])
		{	// Toutes les erreurs serveur sont traitées ici via le script index.php. Cf .htaccess
			case 200:	// le script est lancé sans redirection
				list($this->alpha, $this->beta, $this->gamma) = HttpRoute::SansRedirection();
				$this->T_param = HttpRoute::ExtraireParamRacine();
				break;
			case 404:
				list($URL, $reste) = explode("?", $_SERVER['REQUEST_URI'], 2);
				list($this->alpha, $this->beta, $this->gamma) = HttpRoute::Redirection404($URL);
				$this->T_param = HttpRoute::ExtraireParamURL();
				break;
			default:
				throw new ServeurException($_SERVER['REDIRECT_STATUS']);
		}

		$this->URL = BDD::SELECT("URL FROM Vue_URLvalides
									WHERE niveau1 = ? AND niveau2 = ? AND niveau3 = ?",
									[$this->alpha, $this->beta, $this->gamma],true
								);
		$this->methode = $_SERVER['REQUEST_METHOD'];

		/* Dans la table Squelette on récupère la liste des paramètres autorisés.
		 * On construit un nouveau tableau qui ne contient que les clés autorisées.
		 * Par contre un paramètre manquant ne provoque pas d'erreur. C'est au controleur de décider. */
		$reponse = BDD::SELECT('paramAutorise FROM Squelette WHERE alpha=? AND beta=? AND gamma=? AND methode=?',
							[	$this->alpha,
								$this->beta,
								$this->gamma,
								$this->methode
							],true);
		$TparamAutorises = json_decode($reponse, true);

		$Treponse = [];
		foreach ($TparamAutorises as $clé)
			if (isset($this->T_param[$clé]))			// seules les clés autorisées sont prises en compte
				$Treponse[$clé] = $this->T_param[$clé];	// la valeur a déjà été nettoyée
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
		// interrogation de la BD pour retrouver la position dans l'arborescence
		$Treponse = BDD::SELECT("niveau1, niveau2, niveau3 FROM Vue_URLvalides WHERE URL = ?", [$URL],true);
		if (isset($Treponse["niveau1"]))	// l'URL existe?
		{	// la page existe
			header("Status: 200 OK", false, 200);	// modification pour dire au navigateur que tout va bien finalement
			return array($Treponse["niveau1"], $Treponse["niveau2"], $Treponse["niveau3"]);
		} else throw new ServeurException(404);
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

	private static function ExtraireParamURL()
	/* Extraction des paramètres suite à une redirection 404.
	 * $_GET n'existe pas, il faut donc décoder l'URL manuellement.
	 * $_POST n'existe pas dans ce cas.
	*/
	{	// découpe de l'URL
		list($URL, $reste) = explode("?", $_SERVER['REQUEST_URI'], 2);
		list($paramURL, $ancre) = explode("#", $reste, 2);

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

//	Accesseurs ===================================================================================

	public function getAlpha()	{ return $this->alpha; }
	public function getBeta()	{ return $this->beta; }
	public function getGamma()	{ return $this->gamma; }
	public function getMethode(){ return $this->methode; }
	public function getURL()	{ return $this->URL; }
	public function getParam($nom = null){ return (isset($nom)) ? $this->T_param[$nom] : $this->T_param; }
}
