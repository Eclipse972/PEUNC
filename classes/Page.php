<?php
// classe-mère des pages de PEUNC
namespace PEUNC;

include"API_page.php";

class Page implements iPage	{
// CONFIGURATION DE L'APPLICATION

	// dossiers pas défaut
	const DOSSIER_MODEL		= 'Modele/';
	const DOSSIER_VUE		= 'Vue/';
	const DOSSIER_CONTROLEUR= 'Controleur/';
	const DOSSIER_IMAGE		= 'images/';
	const DOSSIER_CSS		= 'CSS/';
	const DOSSIER_JS		= 'js/';
	const DOSSIER_VIDEO		= 'video/';
	const IMAGE_ABSENTE		= '/images/image_absente.png';
	
	// Intervalle pour les onglets
	const ALPHA_MINI = 0;
	const ALPHA_MAXI = 4;

	protected $titrePage	= "Titre de la page affiché dans la barre du haut du navigateur";
	protected $T_CSS		= [];
	protected $entetePage;	// la valeur par défaut est donnée par le champ texteMenu dans le squelette
	protected $logo			= "logo.png";
	protected $dossier		= "/";
	protected $scriptSection= "<h1>Page en construction</h1>\n<p>Contactez l&apos;adminitrateur si le probl&egrave;me persiste </p>\n";
	protected $scriptNav	= "";
	protected $PiedDePage	= "<p>Pied de page &agrave; d&eacute;finir</p>";
	protected $vue			= "doctype.html";
	protected $route;
// FIN DE LA CONFIGURATION


	protected $T_paramURL	= [];

	public function __construct(HttpRoute $route = null, array $TparamURL = [])
	{
		$this->route = $route;
		if (isset($route))
		{
			// valeur par défaut de l'en-tête
			$this->entetePage = BDD::SELECT("texteMenu FROM Squelette WHERE alpha= ? AND beta= ? AND gamma= ? AND methode = ?",
									[$route->getAlpha(), $route->getBeta(), $route->getGamma(), $route->getMethode()]);
			foreach($TparamURL as $clé => $valeur)
				$this->T_paramURL[$clé] = htmlspecialchars($valeur);
		}
		else $this->entetePage = "Erreur serveur";
	}
/* ***************************
 * MUTATEURS (SETTER)
 * ***************************/
	public function setTitle($titre)		{ $this->titrePage = $titre; }

	public function setHeaderText($texte)	{ $this->entetePage = $texte; }

	public function setLogo($logo)			{ $this->logo = $logo; }			// nom de la forme /sous/dossier/fichier.extension à partir du dossier image du site

	public function setDossier($dossier)	{ $this->dossier = $dossier . "/"; }

	public function setSection($code)		{ $this->scriptSection = $code;	}

	public function setNav($code)			{ $this->scriptNav = $code;	}

	public function setFooter($code)		{ $this->PiedDePage = $code; }

	public function setView($fichier, $cheminParDefaut = true)
	{
		$fichier =  $cheminParDefaut ? self::DOSSIER_VUE . $fichier : $fichier;
		if (file_exists($fichier))
			$this->vue = $fichier;
		else throw new Exception("Vue inexistante");
	}

	public function setCSS($feuilleCSS)
	{
		if(substr($feuilleCSS,0,4) == 'http')
			$this->T_CSS[] = $feuilleCSS;	// pas de vérification sur feuille externe
		else
		{
			$feuilleCSS = self::DOSSIER_CSS . $feuilleCSS . ".css";
			if(file_exists($feuilleCSS))
				$this->T_CSS[] = '/' . $feuilleCSS;
			else throw new Exception($feuilleCSS . " n&apos;existe pas");
		}
	}

/* ***************************
 * ASSESSURS (GETTER)
 * ***************************/
	public function getTitle()			{ return $this->titrePage; }

	public function getHeaderText() 	{ return $this->entetePage . "\n"; }

	public function getLogo()			{ return Page::BaliseImage($this->logo,'Logo'); }

	public function getDossier()		{ return $this->dossier; }

	public function getSection()		{ return $this->scriptSection; }

	public function getNav()			{ return ($this->scriptNav == "" ? "" : "<nav>\n" . $this->scriptNav . "</nav>\n"); }
	
	public function getFooter()			{ return $this->PiedDePage; }

	public function getView()			{ return $this->vue; }

	public function getParamURL($i = 0)	{ return (isset($this->T_paramURL[$i])) ? $this->T_paramURL[$i] : null; }

	public function getCSS()			{ foreach($this->T_CSS as $feuilleCSS) echo"\t<link rel=\"stylesheet\" href=\"", $feuilleCSS,"\" />\n";	}

	public function getRoute()			{ return $this->route; }

/* ***************************
 * AUTRE
 * ***************************/

	public function ExecuteControleur($script)
	{
		if($script == "")
			throw new Exception("Controleur non d&eacute;fini");
		elseif (file_exists(self::DOSSIER_CONTROLEUR. $script))	// script dans le dossier des controleurs
			require(self::DOSSIER_CONTROLEUR . $script);
		elseif (file_exists($script))							//	script défini de manière absolue
			require($script);
		else throw new Exception("Controleur inexistant");
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
		return '<img src="' . $src . '" alt="' . $alt . '" ' . $code . '>';
	}

	public static function SauvegardeEtat(HttpRoute $route)
	{
		$URLactuelle = $route->URL();

		if ($_SESSION["PEUNC"]["URL"] != $URLactuelle) // sauvagarde s'il n'y a pas rafraichiisemnt de page
		{
			$_SESSION["PEUNC"]["URLprecedente"] = (isset($_SESSION["PEUNC"]["URL"])) ? $_SESSION["PEUNC"]["URL"] : "/";

			$_SESSION["PEUNC"]["URL"] =	$URLactuelle;
		}
	}

	public static function URLprecedente()	{ return $_SESSION["PEUNC"]["URLprecedente"]; }
	
 	public static function CodeOnglets($alphaCourant, $alphaMini = Page::ALPHA_MINI, $alphaMaxi = Page::ALPHA_MAXI)
 	{
		$T_Onglets = BDD::Liste_niveau();
		if(!is_array($T_Onglets)) throw new Exception("Onglets inexistants!  Il faut au moins 2 items");

		$codeOnglet = "<ul>\n";
		foreach($T_Onglets as $alpha => $code)
		{
			if (($alpha >= $alphaMini) && ($alpha <= $alphaMaxi))
				$codeOnglet .= "\t<li>" . (($alpha == $alphaCourant) ? str_replace('href', 'id="alpha_actif" href', $code) : $code) . "</li>\n";
		}
		return $codeOnglet . "\t</ul>\n";
	}

	public static function CodeMenu(HttpRoute $route)
	{
		$T_item = BDD::Liste_niveau($route->getAlpha());
		if(!is_array($T_item)) throw new Exception("Menu inexistant! Il faut au moins 2 items");
		
		$codeMenu = "\t<ul>\n";
		foreach($T_item as $beta => $code)
		{
			$codeMenu .= "\t<li>" . (($beta == $route->getBeta()) ? str_replace('href', 'id="beta_actif" href', $code) : $code) . "</li>\n";
			if ($beta == $route->getBeta())	// sous-menu?
			{
				$T_sous_item = BDD::Liste_niveau($route->getAlpha(), $route->getBeta());
				if (isset($T_sous_item))	// génération sous-menu s'il existe
				{
					$codeMenu .= "\t<ul>\n";
					foreach($T_sous_item as $gamma => $sous_code)
						$codeMenu .= "\t\t<li>" . (($gamma == $route->getGamma()) ? str_replace('href', 'id="gamma_actif" href', $sous_code) : $sous_code) . "</li>\n";
					$codeMenu .= "\t</ul>\n";
				}
			}
		}
		return $codeMenu . "\t</ul>\n";
	}
}
