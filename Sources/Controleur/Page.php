<?php
// classe-mère des pages de PEUNC
namespace PEUNC\Controleur;

use PEUNC\Http\HttpRoute;
use PEUNC\Erreur\Exception;
use PEUNC\Autre\BDD;
use PEUNC\Controleur\iPage;

class Page implements iPage	{
// CONFIGURATION DE L'APPLICATION

	// dossiers pas défaut
	const DOSSIER_MODEL		= 'Modele/';
	const DOSSIER_VUE		= 'Application/Vue/';
	const DOSSIER_CONTROLEUR= 'Controleur/';
	const DOSSIER_IMAGE		= 'images/';
	const DOSSIER_CSS		= 'CSS/';
	const DOSSIER_JS		= 'js/';
	const DOSSIER_VIDEO		= 'video/';
	const IMAGE_ABSENTE		= '/images/image_absente.png';

	// Intervalle pour  le niveau alpha (les onglets)
	const ALPHA_MINI = 10;
	const ALPHA_MAXI = 20;

	protected $titrePage	= 'Titre de la page affiché dans la barre du haut du navigateur';
	protected $T_CSS		= [];
	protected $entetePage;	// la valeur par défaut est donnée par le champ texteMenu dans le squelette
	protected $logo			= 'logo.png';
	protected $dossier		= '/';
	protected $scriptSection= "<h1>Page en construction</h1>\n<p>Contactez l&apos;adminitrateur si le probl&egrave;me persiste </p>\n";
	protected $T_nav		= [];
	protected $PiedDePage	= '<p>Pied de page &agrave; d&eacute;finir</p>';
	protected $vue;
	protected $route;
// FIN DE LA CONFIGURATION

	public function __construct(HttpRoute $route = null)
	{
		$this->setView('doctype.html');
		$this->route = $route;
		if (isset($route))
		{	// valeur par défaut de l'en-tête
			$this->entetePage = BDD::SELECT('texteMenu FROM Squelette WHERE alpha= ? AND beta= ? AND gamma= ? AND methode = ?',
									[$route->getAlpha(), $route->getBeta(), $route->getGamma(), $route->getMethode()],true);
		}
		else $this->entetePage = 'Erreur serveur';
	}
/* ***************************
 * MUTATEURS (SETTER)
 * ***************************/
	public function setTitle($titre)		{ $this->titrePage = $titre; }

	public function setHeaderText($texte)	{ $this->entetePage = $texte; }

	public function setLogo($logo)			{ $this->logo = $logo; }			// nom de la forme /sous/dossier/fichier.extension à partir du dossier image du site

	public function setDossier($dossier)	{ $this->dossier = $dossier . '/'; }

	public function setSection($code)		{ $this->scriptSection = $code;	}

	public function setNav(array $code)		{ $this->T_nav = $code;	}

	public function setFooter($code)		{ $this->PiedDePage = $code; }

	public function setView($fichier, $cheminParDefaut = true)
	{
		$fichier =  $cheminParDefaut ? self::DOSSIER_VUE . $fichier : $fichier;
		if (file_exists($fichier))
			$this->vue = $fichier;
		else throw new Exception(500);
	}

	public function setCSS($feuilleCSS)
	{
		if(substr($feuilleCSS,0,4) == 'http')
			$this->T_CSS[] = $feuilleCSS;	// pas de vérification sur feuille externe
		else
		{
			$feuilleCSS = self::DOSSIER_CSS . $feuilleCSS . '.css';
			if(file_exists($feuilleCSS))
				$this->T_CSS[] = '/' . $feuilleCSS;
			else throw new Exception($feuilleCSS . ' n&apos;existe pas');
		}
	}

/* ***************************
 * ASSESSURS (GETTER)
 * ***************************/
	public function getTitle()			{ return $this->titrePage; }

	public function getHeaderText() 	{ return $this->entetePage; }

	public function getLogo()			{ return $this->logo; }

	public function getDossier()		{ return $this->dossier; }

	public function getSection()		{ return $this->scriptSection; }

	public function getFooter()			{ return $this->PiedDePage; }

	public function getView()			{ return $this->vue; }

	public function getCSS()			{ return $this->T_CSS; }

	public function getRoute()			{ return $this->route; }

	public function getNav()			{ return $this->T_nav; }

/* ***************************
 * AUTRE
 * ***************************/

	public function ExecuteControleur($script)
	{
		if($script == '') throw new Exception(501);

		$script = self::DOSSIER_CONTROLEUR . $script;
		if (file_exists($script))							//	script défini de manière absolue
			require($script);
		else throw new Exception(502, $script);
	}

/* ***************************
 * méthodes statiques
 * ***************************/

	public static function BaliseImage($src, $alt = '<b>Image ici</b>', $code = '')
	{
		if(substr($src,0,4) != 'http')	// fichier interne?
		{
			//		chemin absolu?				suppression de / au début		ajout dossier image
			$src = (substr($src,0,1) == '/') ? substr($src,1,strlen($src)) : self::DOSSIER_IMAGE . $src;
			$src = (file_exists($src)) ? '/' . $src : self::IMAGE_ABSENTE;
		}
		return '<img src=' . $src . ' alt="' . $alt . '" ' . $code . '>';
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

 	public static function MENU(HttpRoute $route, $niveau, $profondeur, $alphaMini = Page::ALPHA_MINI, $alphaMaxi = Page::ALPHA_MAXI)
	{
		if (($niveau <= 0) || ($profondeur < 0) || ($niveau + $profondeur > 3))	throw new Exception(503);

		switch($niveau)
		{
			case 1:
				$ancienTableau = BDD::Liste_niveau($route->getAlpha());
				// suppression des lignes en dehors de l'intervalle
				$Tableau = [];
				foreach($ancienTableau as $i => $ligne)
					if(($i >= $alphaMini) && ($i <= $alphaMaxi))
						$Tableau[$i] = $ligne;
				break;
			case 2:
				$Tableau = BDD::Liste_niveau($route->getBeta(), $route->getAlpha());
				break;
			case 3:
				$Tableau = BDD::Liste_niveau($route->getGamma(), $route->getAlpha(), $route->getBeta());
				break;
			default: throw new Exception(504);
		}
		if (count($Tableau) > 0)
		{
			Page::DebutMenu($T_code);
			foreach($Tableau as $i => $ligne)
			{
				Page::AjouteItem($T_code, $ligne);

				if ((substr($ligne, 0, 6) == '<a id=') && ($niveau < 4) && ($profondeur > 0))	// y a-t-il un niveau inférieur à afficher?
				//	pour	chaque ligne de code du sous-menu s'il existe			on rajoute le sous-code
					foreach(PAGE::MENU($route, $niveau+1, $profondeur-1) as $ligne)	$T_code[] = $ligne;
			}
			Page::FinMenu($T_code);
			return $T_code;
		} else return [];
	}

	public static function DebutMenu(&$Tableau)	{ $Tableau[] = '<ul>'; }

	public static function FinMenu(&$Tableau)	{ $Tableau[] = '</ul>'; }

	public static function AjouteItem(&$Tableau, $ligne){ $Tableau[] = '<li>' . $ligne . '</li>'; }
}
