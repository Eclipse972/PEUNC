<?php	// formulaire de PEUNC
use PEUNC\Http\HttpRoute;

namespace PEUNC\Controleur;

class Formulaire extends Page
{
	protected $jetonJSON;	// contient la configuration en clair sous la forme d'un objet JSON

	public function __construct(HttpRoute $route)
	{
		parent::__construct($route);
		switch($route->getMethode())
		{
			case "GET":
				// création du jeton qui sauvegarde l'état lors de la création du formulaire
				$this->jetonJSON = '{"depart":' . time()	// permet d'avoir une jeton qui change à chaque fois.
								. ', "noeud":[' . $route->getAlpha() . ',' . $route->getBeta() . ',' . $route->getGamma() . ']}';	// position dans l'arborescence
				break;
			case "POST":
				// lecture du jeton
				$this->jetonJSON = Formulaire::DecoderJeton($this->route->getParam("CSRF"));
				break;
			default:
				throw new Exception(300);
		}
	}

// Création du code du formulaire ================================================================

	public function Commencer($titre = "Connexion")
	{	return "<form action=/ method=post>\n<h1>{$titre}</h1>\n";	}

	public function BontonValidation($texte = "Soumettre")
	{	return "<input type=submit value=\"{$texte}\" />\n"; }

	public function AjouterBouton($texte, $URL, $bulle = null)
	{	return "<a href=" . $URL . (isset($bulle) ? " title=\"" . $bulle . "\"" : "") . ">" . $texte . "</a>\n";	}

	public function Terminer($URL)
	{
		$this->AjouterVariableAuJeton("URLsuivante", '"' . $URL . '"'); // ajout URL suivante
		return $this->InsererJeton() . "</form>\n";
	}

// Le jeton CSRF =================================================================================

	public function InsererJeton()
	{
		if(!file_exists("config_chiffrement.php"))	throw new Exception(301);

		require"config_chiffrement.php";	// défini $cipher, $key et $iv
		$jetonchiffré = openssl_encrypt($this->jetonJSON, $cipher, $key, 0, $iv);
		return "<input name=\"CSRF\" type=\"hidden\" value=\"" . $jetonchiffré . "\">\n";
	}

	public function AjouterVariableAuJeton($nom, $valeurJSON)
	{
		$this->jetonJSON .= '{"' . $nom . '":' . $valeurJSON . '}';		// les 2 objets ont mis cote à cote
		$this->jetonJSON = str_replace("}{", ", ", $this->jetonJSON);	// fusionne les deux objets
	}

	public static function DecoderJeton($jetonChiffré)
	{
		if(!file_exists("config_chiffrement.php"))	throw new Exception(301);

		require"config_chiffrement.php";	// défini $cipher, $key et $iv
		$jeton = openssl_decrypt($jetonChiffré, $cipher, $key, 0, $iv);// dechiffrement jeton
		$O_jeton = json_decode($jeton);		// si erreur renvoyer null sinon renvoyer l'objet
		return $O_jeton;
	}

// Fonctions abstraites =======================================================================

/*	abstract public function TraiterSpam();	// par exemple ajouter une entrée dans un journal

	abstract public function FormulaireOK();// la liste des champs est correcte et ils ont la forme attendue

	abstract public function Traitement();	// traitement si tout est OK. Par exemple envoyer un courriel, modifier une BD

	abstract public function TraitementAvantRepresentation();	// prépare le formulaire pour un réaffichage en  générant des messages d'erreur par exemple'
*/
//	zone de test ==============================================================================

}
