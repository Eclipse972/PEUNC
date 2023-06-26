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
	public function get($clé);	// retourne l'élément référencé par la clé
	public function getCSS();	// affiche le code pour utiliser toutes les feuilles CSS associée à la page
	public function getView();	// chemin de la vue associée à la page
	public function getNav();

// Assesseurs (setters)
	public function setCSS($feuilleCSS);	// ajoute une feuille CSS associée à la page. Répétables plusieurs fois
	public function setNav(array $code);	// affiche la liste des instructions de la barre de navigation avec <nav> car cette balise est optionnelle
	public function setView($fichier);		// définit le chemin de la vue
}
