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
	public function get($nom);				// retourne l'élément nommé
	public function set($nom, $valeur);	// ajoute ou écrase l'élément nommé. c'est une méthode privée
	/**
	 * Listes des noms déjà pris
	 * T_CSS: tableau contenant la liste des feuilles CSS
	 * dossierCSS: emplacement des feuilles se style. Par défaut = CSS
	 * T_nav: liste des instruction html pour le menu
	 * vue: nom complet de la vue à afficher
	 */
	public function AjouteCSS($FeuilleCSS);// sans l'extension
}
