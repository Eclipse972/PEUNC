<?php
namespace PEUNC\Http;

use PEUNC\Erreur\Exception;
use PEUNC\Autre\BDD;
use PEUNC\Macro\Balise;
use PEUNC\Controleur\Page;

class ReponseClient implements iReponseClient
/* Réponse à servir au client en fonction de la route trouvée suite à la requête http.
 * Classe nécesaire: HttpRoute chargée par l'autoloader
*/
{
const DOSSIER_CACHE = "cache/";

protected $controleur;

public function __construct($controleur)
{
	/* pour le moment toutes les pages peuvent être mise en cache.
		* Or ce pas possible pour les pages posesdant des paramètres car on ne peut les connaitre
		* tousà l'avance.
		* La liste des paramètres de chaque page est diponible dans la table squellette
		* Une age peut est 'cachée' si la méthode http =GET  et pas de paramètre */
	$this->controleur = $controleur;

	/* Remarque: dans le cas d'un traitement de formulaire, la redirection devrait provoquer
		* une nouvelle requête qui générera une nouvelle réponse. A VÉRIFIER */
}

// Implémentation de l'interface =====================================================================
public function View()
{
	return $this->controleur->getVue();
}

public function CSS()
{
	$Liste = $this->controleur->getCSS();

	foreach($Liste as $feuilleCSS)
		echo "\t" , '<link rel="stylesheet" href="', $feuilleCSS, '"/>', "\n";
}

public function Menu()
{
	$code = '';
	$nbTabulation = 0;
	foreach ($this->controleur->getNav() as $ligne)
	{
		if($ligne == '</ul>') $nbTabulation--;
		$code .= str_repeat("\t", $nbTabulation) . $ligne . "\n";
		if($ligne == '<ul>') $nbTabulation++;
	}
	return $code;
}

public function Element($nom)
{
	return $this->controleur->get($nom);
}
// Fin de l'implémentation de l'interface ============================================================
}

