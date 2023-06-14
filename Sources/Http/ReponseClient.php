<?php
namespace PEUNC\Http;

use PEUNC\Erreur\Exception;
use PEUNC\Http\HttpRoute;
use PEUNC\Autre\BDD;

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
				[$this->route->getAlpha(), $this->route->getBeta(), $this->route->getGamma(), $this->route->getMethode()],true);
		if($dureeCache==0)
			return $this->SansCache();
		else
		{	// gestion du cache
			$fichierCache = self::DOSSIER_CACHE . "cache" . str_replace('/','-',$this->route->getURL()) . '.html';
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
		$Treponse = BDD::SELECT("classePage, controleur FROM Squelette WHERE alpha=? AND beta=? AND gamma=? AND methode=?",
								[$this->route->getAlpha(), $this->route->getBeta(), $this->route->getGamma(), $this->route->getMethode()],true);
		$classePage = $Treponse["classePage"];
		if (!isset($classePage))	throw new Exception(200);

		$controleur = $Treponse["controleur"];

		// création de la page
		$page = new $classePage($this->route);
		$page->ExecuteControleur($controleur);
		return $page;
	}
}
