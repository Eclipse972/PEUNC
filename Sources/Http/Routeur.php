<?php
namespace PEUNC\Http;
use PEUNC\Http\RequeteHttp;
use PEUNC\Erreur\ServeurException;

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
	/**
	 * À FAIRE: vérifier la validité des paramètre
	 * URL: URL sur 3 niveaux ne contenant que des caractères cf .htacces 
	 * methodeHttp, namespaceControleur, controleurMethode des chaine de caractères non vide
	 * existence du controleur et de la méthode
	 * lancer une exception de PEUNC si ça arrive
	 **/
	# validté de l'URL
	# validité de la méthode http
	# existence du controleur
	# existence de la méthode du controleur
	$this->Troute[$URL][$methodeHttp] = array(
		'controleur' => $this->baseNamespaceControleur . '\\' . $namespaceControleurRelatif,
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
