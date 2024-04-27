<?php
namespace PEUNC\Http;
use PEUNC\Http\RequeteHttp;
use PEUNC\Http\HttpRoute;
use PEUNC\Erreur\ServeurException;
use PEUNC\Erreur\Exception;

class Routeur {
private array $Troute = [];
private string $baseNamespaceControleur; # namespace de base ex: VolEval\Controleur le \ ajouté automatiquement

public function __construct(string $namespace) {
	$this->baseNamespaceControleur = $namespace;
}

private function ajouteRoute(string $URL,
							string $methodeHttp,
							string $namespaceControleurRelatif,
							string $controleurMethode,
							array $parametreAutorisés) : void
{
	# validité de l'URL sur 3 niveaux ne contenant que des caractères cf .htacces 
	$regexp = '/^\/(?:[a-zA-Z0-9_-]+\/?(?:[a-zA-Z0-9_-]+\/?(?:[a-zA-Z0-9_-]+\/?)?)?)?$/';
	if (!preg_match($regexp, $URL)) throw new Exception(800, $URL);

	# validité de la méthode http: les routes sont construites avec les nom des méthodes

	# inexistence du controleur => échec autoloading -> À FAIRE: lancer une exception au lieu de faire exit
	$namespaceControleur = $this->baseNamespaceControleur.'\\'.$namespaceControleurRelatif;

	# existence de la méthode du controleur
	/**
	 * if(!method_exists($namespaceControleur, $controleurMethode))	throw new Exception(801,$controleurMethode.' pour '.$namespaceControleur);
	 * 
	 * Pour une raison que je comprend pas la méthode ClicSurNON de MobileControleur n'est pas détecté
	 * Toutes les autres méthode sont détectées. Si je met en commentaire les lignes incriminées ça passe.
	 * Toutes les autre méthode de MobileControleur sont détectées et toutes les méthode des autres controleurs aussi
	 * Le changement de nom ne fonctionne pas non plus
	 * 
	 **/

	# enregistrement de la route
	$this->Troute[$URL][$methodeHttp] = array(
		'controleur' => $namespaceControleur,
		'fonction' => $controleurMethode,
		'paramAutorise' => $parametreAutorisés
	);
}

# fonctions pour créer les routes de type GET et POST
public function get(string $URL,
					string $namespaceControleur,
					string $controleurMethode,
					array $parametreAutorisés = []) : void
{ $this->ajouteRoute($URL, 'GET', $namespaceControleur, $controleurMethode, $parametreAutorisés); }

public function post(string $URL,
					string $namespaceControleur,
					string $controleurMethode,
					array $parametreAutorisés = []) : void
{ $this->ajouteRoute($URL, 'POST', $namespaceControleur, $controleurMethode, $parametreAutorisés); }

public function TrouveChemin(RequeteHttp $requete) : array {
	/**
	 * C'est index.php qui va instancié le controleur.
	 * Cette fonction lui fournit tous les éléments pour le faire:
	 * controleur, sa fonction, la liste des paramètres autorisés et la route
	 **/
	$URL = $requete->getURL();
	$methodeHttp = $requete->getMethode();
	if ((!array_key_exists($URL, $this->Troute)) # url inexistante
		|| (!array_key_exists($methodeHttp, $this->Troute[$URL])) # méthode http inconnue pour l'url donnée
	) throw new ServeurException(404);
	$T = $this->Troute[$URL][$methodeHttp];
	$T['route'] = new HttpRoute($requete, $T['paramAutorise']); # route à construire 
	return $T;
}

# À FAIRE: vérifier l'intégrité de l'arborescence. noeud orphelin, doubllon
}
