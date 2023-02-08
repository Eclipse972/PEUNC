<?php	// API de la classe BDD de PEUNC
namespace PEUNC;

interface iBDD
/* BDD de PEUNC
 *
 * La classe est statique et ne doit pas être instanciée. Elle permet d'avoir partout la même instance de la BDD sans passer par une variable globale
 * ma source d'inspiration: https://www.training-dev.fr/Cours/Creer-un-framework-MVC-en-Php/Acceder-a-une-base-de-donnees
 * */
{
	public static function SELECT($requete, array $T_parametre);
	/* Renvoie le réultat d'une requête SQL SELECT.
	 * $requete: chaine de caractère sans le mot SELECT de début. Les variables doivent remplacées par de ?
	 * $T_parametre: tableau qui contient la liste des variables dans l'ordre d'apparition des ?
	 *
	 * Exemples d'appels
	 * 1- plusieurs variables:	PEUNC\BDD::SELECT("* FROM table WHERE alpha=? AND beta=?", [5,1]) -> alpha=5 et beta=1
	 * 2- Une seule variable:	PEUNC\BDD::SELECT("* FROM table WHERE ID=?", [5]) Il faut quand même faire un tableau
	 * 3- Aucune variable		PEUNC\BDD::SELECT("* FROM table", []) Il faut quand même donner un tableau vide
	 *
	 * le résultat peut être
	 * un tableau en 2 dimensions
	 * un tableau d'une ligne
	 * une seule valeur
	 * */

 	public static function Liste_niveau($i_sectionne, $alpha = null, $beta = null);
 	/* Renvoie un liste des codes html des noeuds d'un niveau de l'arborescence
 	 *
 	 * $i_sectionne: N° de l'item sélectionné
 	 * $mini, $maxi: borne pour les indice por le niveau alpha
 	 * 
 	 * Exemples d'appels
 	 * premier niveau (alpha):	PEUNC\BDD::Liste_niveau(5)
 	 * deuxième niveau (beta):	PEUNC\BDD::Liste_niveau(3,X)	pour alpha = X
 	 * troisième niveau(gamma):	PEUNC\BDD::Liste_niveau(2,X,Y)	pour alpha = X et beta = Y
 	 * */
}
