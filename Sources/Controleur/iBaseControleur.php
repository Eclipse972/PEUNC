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
	public function get($nom);			// retourne l'élément nommé
	public function set($nom, $valeur);	// ajoute ou écrase l'élément nommé. c'est une méthode privée
	
	// les éléments spécifiques
	public function AjouteCSS($FeuilleCSS);	// sans l'extension
	public function getCSS();				// retourne la liste des liens CSS

	public function setVue($fichier);	// nom du fichier sans le répertoire par défaut
	public function getVue();			// renvoie le chimn complet vers la vue
}
