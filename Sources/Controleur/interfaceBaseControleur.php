<?php		// 
/*
    Interface du controleur de base

    Le controleur de bas e permet de créer et restituer toutes les partie d'une page HTML.
    Il ne sait rien faire d'autre. Les controleur de l'utiisateur devront hériter de celui-ci.
    Les decendants pourront définir des méthodes qui contruiront la page.

    Chacune de ces méthode ne devra pas avoirs de paramètre puisqu'on ne les connais pas d'avance.
    Par contre la route avec toutes ses information est passée au constructeur. Cette route
    peut contenir des paramètes exploitable par le controleur.

	Les mutateurs permettent de définir le contenu. Les accesseurs restituent seulement le code 
	sans le mettre en forme sous la forme d'une variable texte ou un tabkeau. C'est l'objet RéponseClient qui s'en chargera.
*/

interface iBaseControleur
{
// Mutateur (getters)
	public function getCSS();		// affiche le code pour utiliser toutes les feuilles CSS associée à la page
	public function getTitle();		// affiche le titre du document (qui est affiché dans la barre de titre du navigateur ou dans l'onglet de la page)
	public function getHeaderText();// en-tête de la page
	public function getSection();	// affiche le code du corps de la page
	public function getNav();		// nav
	public function getFooter();	// pied de page
	public function getView();		// chemin de la vue associée à la page
	public function getDossier();	// retourne le dossier associé à la page
	public function getRoute();		// renvoie la route http. Remarque pas de setter car variable intialisée dans le constructeur

// Assesseurs (setters)
	public function setCSS($feuilleCSS);				// ajoute une feuille CSS associée à la page. Répétables plusieurs fois
	public function setTitle($titre);					// affiche le titre du document (qui est affiché dans la barre de titre du navigateur ou dans l'onglet de la page)
	public function setHeaderText($texte);				// en-tête de la page
	public function setSection($code);					// affiche le code du corps de la page
	public function setNav(array $code);				// affiche la liste des instructions de la barre de navigation avec <nav> car cette balise est optionnelle
	public function setFooter($code);					// pied de page
	public function setView($fichier, $cheminParDefaut);// définit le chemin de la vue
	public function setDossier($dossier);				// défini le dossier associé à la page

// méthodes statiques
	public static function MENU(HttpRoute $route, $niveau, $profondeur, $alphaMini, $alphaMaxi); // génère un menu à partir de l'arborescence avec un niveau et une profondeur
	public static function BaliseImage($src, $alt, $code);	// insère une image en tenant compte du répertoire image. Seul le premier paramètre est obligatoire
	public static function SauvegardeEtat(HttpRoute $route);// sauvegarde l'état courant dans la session
	public static function URLprecedente();					// URL de la page précédete sauf si cette page est spéciale (alpha < 0)

// Autre
	public function ExecuteControleur($script);	// execute le script du controleur
}
