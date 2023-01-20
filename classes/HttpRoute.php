<?php
namespace PEUNC;

class HttpRoute
/*
 * Cette classe décode une requête http et renvoie :
 * 	- la position dans l'arborescence même s'il s'agit d'une erreur serveur
 *	- la méthode Http utilisée
 * 	- l'URL en clair
 *
 * La position dans l'arborescence. Elle est représentée par un triplet (alpha, beta, gamma) par importance décroissante
 * Si alpha >= 0 => pages du site
 * (X;0;0) => page de 1er niveau. 	(0;0;0) -> page d'accueil.
 * (X;Y;0) avec Y>0 => page de 2e niveau
 * (X;Y;Z) avec Z>0 => page de 3e niveau
 *
 * si alpha < 0 => page spéciales PEUNC ou autre
 * (-2;0;0) -> formulaire de contact. Mais ce n'est pas une obligation
 *
 * Les pages d'erreur serveur gérées sont: 404, 403, 405 et 500 mais on peut en rajouter facilement d'autres.
 * Si la page n'est pas trouvée quelqu'en soit la raison la réponse sera la page 404.
*/
{
	// position dans l'arborescence
	private $alpha;
	private $beta;
	private $gamma;
	private $methode;	// méthode Http
	private $T_paramURL;

	public function __construct()
	{
		// recherche de la position dans l'arborescence stockée en BD
		switch($_SERVER['REDIRECT_STATUS'])
		{	// Toutes les erreurs serveur sont traitées ici via le script index.php. Cf .htaccess
			case 403:	// accès interdit
			case 405:	// méthode http non permise
			case 500:	// erreur serveur
				throw new ServeurException($_SERVER['REDIRECT_STATUS']);
				break;
			case 200:	// le script est lancé sans redirection
				list($this->alpha, $this->beta, $this->gamma, $this->URL, $this->methode, $this->classePage, $this->controleur, $this->parametres) = self::SansRedirection();
				break;
			case 404:
				list($this->alpha, $this->beta, $this->gamma, $this->URL, $this->methode, $this->classePage, $this->controleur, $this->parametres) = self::Redirection404();
				break;
			default:
				throw new Exception("erreur inconnue");
		}

		$this->methode = $_SERVER['REQUEST_METHOD'];
		
		// Extraction des paramètres passés par l'URL		
		list($URL, $reste) = explode("?", $_SERVER['REQUEST_URI'], 2);// découpe de l'URL
		list($paramURL, $ancre) = explode("#", $reste, 2);	// suppression de l'ancre

		$this->T_paramURL = [];
		foreach (explode('&', $paramURL) as $instruction)
		{
			$param = explode("=", $instruction);
			if ($param) $this->T_paramURL[urldecode($param[0])] = strip_tags(urldecode($param[1]));
		}

	}

//	Gestion des redirections =====================================================================

	private static function Redirection404()
	/* Ce script est appelé suite à une erreur 404. C'est cette redirection que j'exploite pour gérer ma pseudo-réécriture d'URL.
	 * Ma source d'inspiration: http://urlrewriting.fr/tutoriel-urlrewriting-sans-moteur-rewrite.htm Merci à son auteur.
	 *
	 * À partir d'une URL, Cette fonction renvoie la position dans l'arborescence du site.
	 *
	 * Résultat: le triplet (alpha, beta, gamma) sous la forme d'un tableau
	 */
	{
		list($URL, $reste) = explode("?", $_SERVER['REQUEST_URI'], 2);
		
		// recherche alpha, beta et gamma
		$Treponse = BDD::SELECT("niveau1, niveau2, niveau3 FROM Vue_URLvalides WHERE URL = ?", [$URL]);
		if(!isset($Treponse["niveau1"]))
			throw new ServeurException(404);
		header("Status: 200 OK", false, 200);	// modification pour dire au navigateur que tout va bien finalement
		
		return [$Treponse["niveau1"], $Treponse["niveau2"], $Treponse["niveau3"], $_SERVER['REQUEST_METHOD']];
	}

	private static function SansRedirection()
	/* Un appel direct de index.php.
	 * La pseudo réécriture d'URL ne fonctionne pas avec le script action de formulaire.
	 * J'ai choisi de repasser par index.php pour traiter tous les formulaires.
	 */
	{
		switch($_SERVER['REQUEST_METHOD'])
		{
			case"GET":
				return [0, 0, 0, "GET"];
				break;
			case"POST":	// le jeton CSRF contient des infos sur le formuaire notemment sa position dans l'arborescence
				if (!isset($_POST["CSRF"]))	// si le fomulaire ne contient pas de jeton CSRF
					throw new Exception("Jeton CSRF inexistant");

				$jeton = Formulaire::DecoderJeton($_POST["CSRF"]);

				if (!isset($jeton->noeud))	// si le jeton est invalide
					throw new ApplicationExceotion("Jeton CSRF invalide");
				
				return $jeton->noeud;	// renvoie la position du formulaire
				break;
			default:
				throw new ServeurException(405);// erreur 405!
		}
	}

//	Accesseurs ===================================================================================

	public function getAlpha()		{ return $this->alpha; }
	public function getBeta()		{ return $this->beta; }
	public function getGamma()		{ return $this->gamma; }
	public function getMethode()	{ return $this->methode; }
	public function getParamURL(){ return $this->T_paramURL; }

//	Autre ========================================================================================
	public function getURL()
	{
		return BDD::SELECT("URL FROM Vue_Routes WHERE niveau1=? AND niveau2=? AND niveau3=? AND methodeHttp=?",
							[$this->alpha, $this->beta, $this->gamma, $this->methode]);
	}
}
