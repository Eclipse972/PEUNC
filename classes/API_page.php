<?php		// API de la classe Page de PEUNC
namespace PEUNC;

interface iPage
/* Chaque page est entièrement construite avant le moindre affichage. L'hydratation de la page se fait à partir d'un controleur.
 * Une fois la page construite on injecte le code dans le fichier doctype.html. Ce fichier ne fait qu'utiliser les différentes
 * assesseurs (getter)) de la classe.
*/
{
// Mutateur (getters)
	public function getCSS();		// affiche le code pour utiliser toutes les feuilles CSS associée à la page
	public function getTitle();		// affiche le titre du document (qui est affiché dans la barre de titre du navigateur ou dans l'onglet de la page)
	public function getHeaderText();// en-tête de la page
	public function getSection();	// affiche le code du corps de la page
	public function getNav();		// nav
	public function getFooter();	// pied de page
	public function getView();		// chemin de la vue associée à la page
	public function getParamURL($i);// retourne le i-ème paramètre passé par l'URL
	public function getDossier();	// retourne le dossier associé à la page

// Assesseurs (setters)
	public function setCSS($feuilleCSS);	// ajoute une feuille CSS associée à la page. Répétables plusieurs fois
	public function setTitle($titre);		// affiche le titre du document (qui est affiché dans la barre de titre du navigateur ou dans l'onglet de la page)
	public function setHeaderText($texte);	// en-tête de la page
	public function setSection($code);		// affiche le code du corps de la page
	public function setNav($code);			// affiche le code de la barre de navigation avec <nav> car cette balise est optionnelle
	public function setFooter($code);		// pied de page
	public function setView($fichier);		// définit le chemin de la vue
	public function setDossier($dossier);	// défini le dossier associé à la page

// méthodes statiques
	public static function BaliseImage($src, $alt, $code);	// insère une image en tenant compte du répertoire image. Seul le premier paramètre est obligatoire
	public static function SauvegardeEtat(HttpRoute $route);// sauvegarde l'état courant dans la session
	public static function CodeOnglets(HttpRoute $route);	// renvoie le code html des onglets sur un intervalle alpha. Permet par exemple d'ignorer la page de contact
	public static function CodeMenu(HttpRoute $route);		// renvoie le code html du menu pour une route donnée
	public static function URLprecedente();					// URL de la page précédete sauf si cette page est spéciale (alpha < 0)

// Autre
	public function ExecuteControleur(HttpRoute $route);	// execute le controleur associé à la route http
}
