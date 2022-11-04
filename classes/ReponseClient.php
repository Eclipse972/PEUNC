<?php
namespace PEUNC;

class ReponseClient
/* Réponse à servir au client en fonction de la route trouvée suite à la requête http.
 * Classe nécesaire: HttpRoute chargée par l'autoloader
*/
{
	protected $route;
	
	public function __construct(HttpRoute $route)
	{
		$classePage = BDD::SELECT("classePage FROM Squelette WHERE alpha= ? AND beta= ? AND gamma= ? AND methode = ?",
								[$route->getAlpha(), $route->getBeta(), $route->getGamma(), $route->getMethode()]);
		if (!isset($classePage))
			throw new Exception("La classe de page n&apos;est pas d&eacute;finie dans le squelette.");

		// pré-traitement
		$Tparam = self::PrepareParametres($route);
					
		// création de la page
		$PAGE = new $classePage($route, $Tparam);
		$PAGE->ExecuteControleur($route);

		// post-traitement
		if ($route->getMethode()=="GET")	include $PAGE->getView(); // insertion de la vue
	}

	public static function PrepareParametres(HttpRoute $route)
	/* Dans la table Squelette on récupère la liste des paramètres autorisés.
	 * On construit un nouveau tableau qui ne contient que les clés autorisées et chaque valeur subit un nettoyage.
	 * Par contre des paramètres manquant ne provoquent pas d'erreur.
	 * Une requete GET ne nécessitte pas forcément tous les paramètres
	 * Un paramètre manquant sur une requête POST doit provoquer une erreur.
	 * C'est l'objet qui crée la réponse qui doit décider
	 *
	 * Retour: un tableau débarasssé des clés non autorisées avec ses valeurs nettoyées.
	 * */
	{
		switch($route->getMethode())
		{
			case "GET":
				$Tableau = $_GET;
				break;
			case "POST":
				$Tableau = $_POST;
				break;
			default:
				throw new Exception("M&eacute;thode http inconnue");
		}

		// récupère la liste des paramètres autorisés
		$reponseBD = BDD::SELECT("paramAutorise FROM Squelette WHERE alpha= ? AND beta= ? AND gamma= ? AND methode = ?",
								[$route->getAlpha(), $route->getBeta(), $route->getGamma(), $route->getMethode()]);

		$TparamAutorises = json_decode($reponseBD, true);

		$Treponse = [];
		foreach ($TparamAutorises as $clé)
			if (isset($Tableau[$clé]))							// seules les clés autorisées sont prises en compte
				$Treponse[$clé] = strip_tags($Tableau[$clé]);	// puis ces valeurs sont nettoyées
		return $Treponse;
	}
}
