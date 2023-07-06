<?php
/**
 * Interface du controleur de base
 * Le controleur de base permet de créer et restituer tous les moceaux d'une page HTML. Et ne fait rien d'autre.
 * Les controleurs de l'utiisateur hériteront de celui-ci.
 * Les decendants pourront définir d'autes méthodes pour contruire la page.
 * 
 * Chacune de ces méthodes ne devra pas avoir de paramètre puisqu'on ne les connais pas d'avance.
 * Par contre la route avec toutes ses informations est passée au constructeur.
 * Cette route peut contenir des paramètes exploitable par le controleur.
 *
 *  C'est l'objet RéponseClient qui se chargera de placer chaque morceau dans la vue avec éventuellement une mise en page.
 * */

namespace PEUNC\Controleur;

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
