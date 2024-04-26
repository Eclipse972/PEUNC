<?php
namespace PEUNC\Http;

/**
 * contient tous les paramètres issus de la requêt :
 * 		- l'url
 * 		- les paramètres nettoyés $_GET ou $_POST suivant le type de requête
 *
 * La route et envoyée ensuite au controleur qui pourra k'utiliset
 **/
interface iHttpRoute
{
	public function getURL();		# URL
	public function getParam($nom);	# renvoie le paramètre nommé s'il est précisé et la liste des parmètres sinon
	public static function SauvegardeEtat(HttpRoute $route);	# sauvegarde l'URL associée à la route
	public static function URLprecedente();						# retrouve l'URL précédente sauvegardée en session
}
