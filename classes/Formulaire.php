<?php	// formulaire de PEUNC

namespace PEUNC;

/*abstract*/ class Formulaire extends Page
{
	protected $jetonJSON;	// contient la configuration en clair sous la forme d'un objet JSON

	public function __construct(HttpRoute $route, array $TparamURL = [])
	{
		parent::__construct($route, $TparamURL);
		switch($route->getMethode())
		{
			case "GET":
				// création du jeton qui sauvegarde l'état lors de la création du formulaire
				$this->jetonJSON = '{"depart":' . time()	// permet d'avoir une jeton qui change à chaque fois.
								. ', "noeud":[' . $route->getAlpha() . ',' . $route->getBeta() . ',' . $route->getGamma() . ']}';	// position dans l'arborescence
			break;
			case "POST":
				break;
			default:
				throw new Exception("Méthode inatendue pour un formulaire");
		}
	}

// Fonctions pour le jeton ====================================================================

	public function InsererJeton()
	{
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
