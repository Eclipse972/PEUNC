<?php	// API de la classe BDD de PEUNC
namespace PEUNC;

interface iBDD
/* BDD de PEUNC
 *
 * La classe est statique et ne doit pas être instanciée. Elle permet d'avoir partout la même instance de la BDD sans passer par une variable globale
 * ma source d'inspiration: https://www.training-dev.fr/Cours/Creer-un-framework-MVC-en-Php/Acceder-a-une-base-de-donnees
 * */
{
	public static function SELECT($requete, array $T_parametre, $B_postTraitement);
	/* Renvoie le résultat d'une requête SQL SELECT.
	 * $requete: chaine de caractère sans le mot SELECT de début. Les variables nommées ou anonymes
	 * $T_parametre: tableau qui contient la liste des valeur ou des variables.
	 * $B_postTraitement: un booléen indiquant s'il y un post-traitement sur les résultats. Valeur par défaut true
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
	 * Si $B_postTraitement égale à true, la réponse est reformatée suivant les cas:
	 * un tableau avec les résultats en ligne
	 * une liste de valeurs sous la forme d'un tableau associatif
	 * une valeur (il n'y a plus de tableau)
	 * renvoie null s'il n'y a aucun résultat
	 *
	 * Si $B_postTraitement = false (ou est omis) la réponse est un tableau qui peut être éventuellement vide
	 * */

	public static function INSERT_INTO($requete, array $T_valeurs);
	/* Exécute la requête INSERT.
	 * $requete: chaine de caractère sans leS mot INSERT INTO de début.
	 * $T_valeur: un tableau de valeurs
	 *
	 * Exemples d'appels
	 * Ajouter un enregistrement sous forme de triplet:
	 * 		PEUNC\BDD::INSERT_INTO(" table (alpha, beta, gamma) VALUE (?, ?, ?)", [1,3,0])
	 * OU	PEUNC\BDD::INSERT_INTO(" table (alpha, beta, gamma) VALUE (:alpha, :beta, gamma)", ["alpha" => 1, "beta" => 3, "gamma" => 0])
	 *
	 * On peut ajouter une liste d'enregistement en un seul appel
	 * 		PEUNC\BDD::INSERT_INTO(" table (alpha, beta, gamma) VALUE (?, ?, ?)", [[1,3,0], [3,0,0], [1,3,3]])
	 * OU	PEUNC\BDD::INSERT_INTO(" table (alpha, beta, gamma) VALUE (:alpha, :beta, gamma)",
	 * 								[	["alpha" => 1, "beta" => 3, "gamma" => 0],
	 * 									["alpha" => 3, "beta" => 0, "gamma" => 0],
	 * 									["alpha" => 1, "beta" => 3, "gamma" => 3] ])
	 *
	 * Dans les deux cas il s'agit d'une liste d'enregistrementS
	 *
	 * Pas de résultat en retour
	 *
	 * */

	public static function DELETE_FROM($requete, array $T_parametre);
	/* Exécute la requête DELETE.
	 * $requete: chaine de caractère sans les mots DELETE FROM de début.
	 * $T_parametre: liste des paramètres
	 *
	 * Exemples d'appels
	 * 		PEUNC\BDD::DELETE_FROM("Table WHERE alpha= ? AND beta=? AND gamma=?", [8,1,0])
	 *  OU	PEUNC\BDD::DELETE_FROM("Table WHERE alpha= :alpha AND beta=:beta AND gamma=:gamma",  ["alpha" => 8, "beta" => 1, "gamma" => 0])
	 *
	 * 		PEUNC\BDD::DELETE_FROM("Table WHERE alpha= ?", [8])
	 * OU	PEUNC\BDD::DELETE_FROM("Table WHERE alpha= :alpha", ["alpha" => 8])
	 * */

	public static function UPDATE($requete, array $T_parametre);
	/* Exécute la requête UPDATE.
	 * $requete: chaine de caractère sans le mot UPDATE de début.
	 * $T_parametre: liste des paramètres
	 *
	 * Exemples d'appels
	 * 		PEUNC\BDD::UPDATE('Table SET champ=?', [14])
	 * OU	PEUNC\BDD::UPDATE('Table SET champ=:valeur', ['valeur' => 14])
	 *
	 * 		PEUNC\BDD::UPDATE('Table SET champ1=?, champ2=?, champ3=?', [14, 5, 7])
	 * OU	PEUNC\BDD::UPDATE('Table SET champ1=:valeur1, champ2=valeur2', ['valeur1' => 14, 'valeur2' => 4])
	 */

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
