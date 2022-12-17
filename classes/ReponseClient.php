<?php
namespace PEUNC;

class ReponseClient
/* Réponse à servir au client en fonction de la route trouvée suite à la requête http.
 * Classe nécesaire: HttpRoute chargée par l'autoloader
*/
{	
	const DUREE_VIE = 3600;	// 1 heure
	const DOSSIER_CACHE = "cache/";

	private $route;

	public function __construct(HttpRoute $route)
	{
		/* pour le moment toutes les pages peuvent être mise en cache.
		 * Or ce pas possible pour les pages posesdant des paramètres car on ne peut les connaitre
		 * tousà l'avance.
		 * La liste des paramètres de chaque page est diponible dans la table squellette
		 * Une age peut est 'cachée' si la méthode http =GET  et pas de paramètre */

		$this->route = $route;

		/* Remarque: dans le cas d'un traitement de formulaire, la redirection devrait provoquer
		 * une nouvelle requête qui générera une nouvelle réponse. A VÉRIFIER */
	}

	public function AvecCache()
	{
		$fichierCache = self::DOSSIER_CACHE . "cache" . str_replace('/','-',$this->route->getURL()) . '.html';
		If(file_exists($fichierCache) && filemtime($fichierCache) + self::DUREE_VIE > time())
		{	// le cache existe et n'est pas périmé
			$PAGE = new PAGE($route);
			$PAGE->setView($fichierCache, false);
		} else
		{	// il faut créer le cache
			$PAGE = $this->SansCache();

			// création du cache
			ob_start();
			include $PAGE->getView();	// la vue fait appel à une variable $PAGE pour fonctionner
			$contenu = ob_get_clean();
			$contenu = str_replace("<body>", "<!-- cache créé le " .  date("d-m-Y") . " à " . date("H:i:s") ." -->\n<body>", $contenu);
			file_put_contents($fichierCache, $contenu);
		}
		return $PAGE;
	}

	public function SansCache()
	{
		// pré-traitement
		$Tparam = self::PrepareParametres($this->route);

		// création de la page
		$classePage = $this->route->getClassePage();
		if (!isset($classePage))	throw new Exception("La classe de page n&apos;est pas d&eacute;finie dans le squelette.");
		$page = new $classePage($this->route, $Tparam);
		$page->ExecuteControleur($this->route);
		return $page;
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
