<?php
namespace PEUNC\Http;

use PEUNC\Erreur\Exception;
use PEUNC\Autre\BDD;

class ReponseClient
/* Réponse à servir au client en fonction de la route trouvée suite à la requête http.
 * Classe nécesaire: HttpRoute chargée par l'autoloader
*/
{
	const DOSSIER_CACHE = "cache/";

	private $controleur;

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

	/*public function Page()
	{
		if(($dureeCache = $this->route->getDureeCache()) == 0)
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
		$controleur = $this->route->getControleur();
		if (!isset($controleur))	throw new Exception(200);
		$page = new $controleur($this->route);	// création de la page
		$page->ExecuteControleur($this->route->getFonction());
		return $page;
	}
	*/

	public function View()
	{
		return $this->controleur->getView();
	}

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
		echo $this->controleur->getTitle();
	}

	public function HeaderText()
	{
		echo $this->controleur->getHeaderText(), "\n";
	}

	public function Logo()
	{
		echo Page::BaliseImage($this->controleur->getLogo(),'Logo');
	}

	// pas d'implémentation de getDossier()?

	public function CSS()
	{
		$Liste = $this->controleur->getCSS();

		foreach($Liste as $feuilleCSS)
			echo "\t" , '<link rel="stylesheet" href="', $feuilleCSS, '"/>', "\n";
	}

	public function Nav($avecBalise = false)
	{
		$Liste = $this->controleur->getNav();
		if(count($Liste) > 0)
		{
			if($avecBalise)	echo "<nav>\n";
			$n = 0; // nombre de tabulation pour indenter le code
			foreach($Liste as $ligne)
			{
				if($ligne == '</ul>') $n--;
				echo str_repeat("\t", $n), $ligne, "\n";
				if($ligne == '<ul>') $n++;
			}
			if($avecBalise)	echo "</nav>\n";
		}
	}

	public function Section($avecBalise = false)
	{
		self::Balise('section', $this->controleur->getSection(), $avecBalise);
	}
	
	public function Footer($avecBalise = false)
	{
		self::Balise('footer', $this->controleur->getFooter(), $avecBalise);
	}
	
}
