<?php
/*
 * L'arborescence du site est stockée en BDD dans une table appelée Squelette. Elle est limitée à trois niveaux.
 * Elle est représentée par un triplet (alpha, beta, gamma) par importance décroissante
 * (X;0;0) => page de 1er niveau. 	(0;0;0) -> page d'accueil.
 * (X;Y;0) avec Y>0 => page de 2e niveau
 * (X;Y;Z) avec Y>0 et Z>0 => page de 3e niveau
 *
 * L'objet route décode une requête http et renvoie :
 * 		- la position dans l'arborescence
 * 		- en cas d'erreur la position où a eut lieu cette erreur
 *		- la méthode Http utilisée
 * 		- les paramètres $_GET ou $_POST suivant le type de requête
 * 		- le controleur à lancer et le fonction demandée au controleur
 * 		- durée de vie du cache en secondes
 *
 * La route est une variable membre de chaque controleur hériant de BaseControleur
*/
interface iHttpRoute
{
	public function getAlpha();		// alpha (1er niveau)
	public function getBeta();		// beta (2e niveau)
	public function getGamma();		// gamma (3e niveau)
	public function getMethode();	// méthode http de la requête
	public function getURL();		// URL
	public function getParam($nom);	// renvoie le paramètre nommé s'il est précisé et la liste des parmètres sinon
	public function getControleur();// le nomdu controleur à utiliser
	public function getFonction();	// fonction du controleur à exécuter
	public function getDureeCache();// durée du cache e secondes
	public static function SauvegardeEtat(HttpRoute $route);	// sauvegarde l'URL associée à la route
	public static function URLprecedente();						// retrouve l'URL précédente sauvegardée en session
}
