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
	/* Renvoie le résultat d'une requête SQL SELECT.
	 * $requete: chaine de caractère sans le mot SELECT de début. Les variables nommées ou anonymes
	 * $T_parametre: tableau qui contient la liste des valeur ou des variables.
	 *
	 * Exemples de syntaxe
	 * 1- plusieurs variables:
	 * 		PEUNC\BDD::SELECT("* FROM table WHERE alpha=?		AND beta=?",	[5,1])
	 * OU	PEUNC\BDD::SELECT("* FROM table WHERE alpha=:alpha	AND beta=:beta",["alpha" => 5, "beta" => 1])
	 *
	 * 2- Une seule variable:
	 * 		PEUNC\BDD::SELECT("* FROM table WHERE ID=?",	[5]) Il faut quand même envoyer un tableau
	 * 	OU	PEUNC\BDD::SELECT("* FROM table WHERE ID=:ID",	["ID" => 5])
	 *
	 * Remarque: que des ? ou des variables dans la requête pas de mélange.
	 *
	 * 3- Aucune variable		PEUNC\BDD::SELECT("* FROM table") pas besoin de tableau
	 *
	 * La réponse est reformatée suivant les cas:
	 * un tableau avec les résultats en ligne
	 * une liste de valeurs sous la forme d'un tableau associatif
	 * une valeur (il n'y a plus de tableau)
	 * renvoie null s'il n'y a acun résultat
	 * */

	public static function INSERT_INTO($requete, $valeur);
	/* Exécute la requête INSERT INTO.
	 * $requete: chaine de caractère sans le mot INSERT INTO de début.
	 * $valeur: Le(s) n-uplet(s) en second paramètre
	 *
	 * Exemples d'appels
	 * Avec un triplet: PEUNC\BDD::INSERT_INTO(" table (alpha, beta, gamma) VALUE (?, ?, ?)", [1,3,0])
	 *
	 * Pas de résultat en retour
	 * Remarque ne permet l'insertion que d'une ligne à la fois
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
