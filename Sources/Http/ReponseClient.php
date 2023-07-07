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

protected $controller;

public function __construct($controller)
{
	/* pour le moment toutes les pages peuvent être mise en cache.
		* Or ce pas possible pour les pages posesdant des paramètres car on ne peut les connaitre
		* tousà l'avance.
		* La liste des paramètres de chaque page est diponible dans la table squellette
		* Une age peut est 'cachée' si la méthode http =GET  et pas de paramètre */
	$this->controller = $controller;

	/* Remarque: dans le cas d'un traitement de formulaire, la redirection devrait provoquer
		* une nouvelle requête qui générera une nouvelle réponse. A VÉRIFIER */
}

// Implémentation de l'interface =====================================================================
public function View()
{
	return $this->controller->getView();
}

public function CSS()
{
	$Liste = $this->controller->getCSS();

	foreach($Liste as $feuilleCSS)
		echo "\t" , '<link rel="stylesheet" href="', $feuilleCSS, '"/>', "\n";
}

public function Menu()
{
	$code = '';
	$nbTabulation = 0;
	foreach ($thiss->controleur->getNav() as $ligne)
	{
		if($ligne == '</ul>') $nbTabulation--;
		$code .= str_repeat("\t", $nbTabulation) . $ligne . "\n";
		if($ligne == '<ul>') $nbTabulation++;
	}
	return $code;
}

public function Element($nom)
{
	$Telement =  $this->controleur->getElement();
	if (!array_key_exists($nom, $Telement)) throw new Exception('Reponse: élément inexistant');
	return $Telement[$nom];
}
// Fin de l'implémentation de l'interface ============================================================

/* futur méthode à éliminer
// balise générique
private static function Balise($balise, $contenu, $avecBalise)
{
	if($avecBalise)	echo "<", $balise, ">\n";
	echo  $contenu, "\n";
	if($avecBalise)	echo "</", $balise, ">\n";
}

// écriture des balises pour la vue
public function Title()
{
	echo $this->controller->getTitle();
}

public function HeaderText()
{
	echo $this->controller->getHeaderText(), "\n";
}

public function Logo()
{
	echo Balise::Image($this->controller->getLogo(),'Logo');
}

// pas d'implémentation de getDossier()?

public function Section($avecBalise = false)
{
	self::Balise('section', $this->controller->getSection(), $avecBalise);
}

public function Footer($avecBalise = false)
{
	self::Balise('footer', $this->controller->getFooter(), $avecBalise);
}
*/
}
