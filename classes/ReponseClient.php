<?php
namespace PEUNC;

class ReponseClient
/* Réponse à servir au client en fonction de la route trouvée suite à la requête http.
 * Classe nécesaire: HttpRoute chargée par l'autoloader
*/
{	
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

	public function Page()
	{
		$dureeCache = BDD::SELECT("dureeCache FROM Squelette WHERE alpha=? AND beta=? AND gamma=? AND methode=?",
				[$this->route->getAlpha(), $this->route->getBeta(), $this->route->getGamma(), $this->route->getMethode()]);
		if($dureeCache==0)
			return $this->SansCache();
		else
		{	// gestion du cache
			$fichierCache = self::DOSSIER_CACHE . "cache" . str_replace('/','-',$this->route->URL()) . '.html';
			If(file_exists($fichierCache) && filemtime($fichierCache) + $dureeCache * 3600 > time())
			{	// le cache existe et est valide
				$PAGE = new PAGE($this->route);
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
	}
	
	public function SansCache()
	{	// trouver les infos pour construire la réponse
		$Treponse = BDD::SELECT("classePage, controleur, paramAutorise FROM Squelette WHERE alpha=? AND beta=? AND gamma=? AND methode=?",
								[$this->route->getAlpha(), $this->route->getBeta(), $this->route->getGamma(), $this->route->getMethode()]);
		$classePage = $Treponse["classePage"];
		if (!isset($classePage))	throw new Exception("La classe de page n&apos;est pas d&eacute;finie dans le squelette.");
		
		$controleur = $Treponse["controleur"];
		$paramAutorise = $Treponse["paramAutorise"];
		
		// création de la page
		$Tparam = self::PrepareParametres($this->route, $paramAutorise);
		$page = new $classePage($this->route, $Tparam);
		$page->ExecuteControleur($controleur);
		return $page;
	}

	public static function PrepareParametres(HttpRoute $route, $TparamAutorise)
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
		$TparamAutorises = json_decode($TparamAutorise, true);

		$Treponse = [];
		foreach ($TparamAutorises as $clé)
			if (isset($Tableau[$clé]))							// seules les clés autorisées sont prises en compte
				$Treponse[$clé] = strip_tags($Tableau[$clé]);	// puis ces valeurs sont nettoyées
		return $Treponse;
	}
}
