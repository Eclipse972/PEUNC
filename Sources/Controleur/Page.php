<?php
// classe-mère des pages de PEUNC
namespace PEUNC\Controleur;

use PEUNC\Http\HttpRoute;
use PEUNC\Erreur\Exception;
use PEUNC\Autre\BDD;

class Page implements iPage
{
// CONFIGURATION DE L'APPLICATION

// dossiers pas défaut. Il devront être déplacés dans un fichier de config ou dans une classe
const DOSSIER_VUE		= 'Application/Vue/';
const DOSSIER_CONTROLEUR= 'Controleur/';
const DOSSIER_CSS		= 'CSS/';

// variable membre
protected $titrePage	= 'Titre de la page affiché dans la barre du haut du navigateur';
protected $T_CSS		= [];
protected $entetePage;	// la valeur par défaut est donnée par le champ titre dans le squelette
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
	$this->entetePage = isset($route) ? $route->getTitre() : 'Erreur serveur';
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

public function getNav()			{ return $this->T_nav; }

public function getRoute()			{ return $this->route; }

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

public static function MenuAlphaBeta(HttpRoute $route, $alphaMini, $alphaMaxi)
{	// menu sur 2 niveaux: premier alpha et deuxième beta
	return BaseControleur::ConversionEnMenu(
		BDD::SELECT('	alpha AS niveau1, beta AS niveau2, CONCAT("<li><a href=",URL,">",titre,"</a></li>") AS lien
						FROM Squelette
						WHERE (alpha>='.$alphaMini.' AND alpha<='.$alphaMaxi.' AND beta=0 AND gamma=0) OR (alpha=? AND beta>0 AND gamma=0)
						ORDER BY alpha, beta',
						[$route->getAlpha()]),
		$route->getAlpha(),
		$route->getBeta()
	);
}

}
