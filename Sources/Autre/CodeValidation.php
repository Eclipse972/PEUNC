<?php
namespace PEUNC\Autre;

/* Le code de validation est un mot de 5 caractères composé d'une lettre de chaque champ (soit 4 lettres).
 * Pour le choix du caractère il y a quatre possibilités: premier, deuxième, avant dernier et dernier
 * la dernière lettre est une des 4 premières du code.
 *
 * Exemple de validation:
 * 	deuxième caractère de l'objet
 * 	avant dernier caractère du message
 *  deuxième caractère de votre courriel
 *  dernier caractère de votre nom
 *  quatrième caractère de ce code de validation
 * Si les champs sont :
 * 		nom =  Eclipse972
 * 		courriel = christophe.hervi@free.fr
 * 		objet = Question
 * 		message = Pourquoi un code si compliqué?
 * Le code de validation sera uéh22
 * */

class CodeValidation
{
	private $T_id_champ;	// tableau contenant les numéros de champ
	private $T_choix;		// tableau contenant les positions demandées
	private $dernier_choix; // dernière instruction: position du caractère du code de validation

	public function __construct($T_id_champ = null, $T_choix = null, $dernier_choix = null)
	{
		$scenario = (isset($T_id_champ) ? 1 : 0) + (isset($T_choix) ? 2 : 0) + (isset($dernier_choix) ? 4 : 0);
		switch($scenario)
		{
			case 0:	// aucune variable n'est définie
				for($i=0; $i<4; $i++)	// i-ème instruction
				{
					$this->T_id_champ[$i] = $i;		// numéro du champ
					$this->T_choix[$i] = rand(0,3);	// choix du caractère
				}
				shuffle($this->T_id_champ);			// on mélange l'ordre des champs
				$this->dernier_choix = rand(0,3);	// choix du dernier caractère
				break;

			case 7:	// les 3 variables sont définies
				for($i=0; $i<4; $i++)	// i-ème instruction
				{
					$this->T_id_champ[$i] = $T_id_champ[$i];// numéro du champ
					$this->T_choix[$i] = $T_choix[$i];		// choix du caractère
				}
				$this->dernier_choix = $dernier_choix;		// choix du dernier caractère
				break;

			default: throw new \Exception("Certaines variables du code de validation ne sont pas définies. Scénario: " . $scenario);
		}
	}

	public function Afficher()
	{
		$champs		= array('de votre nom', 'de votre courriel', 'de l&apos;objet', 'du message');
		$position	= array('premier', 'deuxi&egrave;me', 'avant dernier', 'dernier'); // => il faut au moins deux caratères pour le champ

		$code = "<ul>\n";
		for($i=0; $i<4; $i++)	$code .= "\t\t\t<li>{$position[$this->T_choix[$i]]} caract&egrave;re {$champs[$this->T_id_champ[$i]]}</li>\n";
		// dernier caractère
		$position = array('premier', 'deuxi&egrave;me', 'troisi&egrave;me', 'quatri&egrave;me');
		$code .= "\t\t\t<li>{$position[$this->dernier_choix]} caract&egrave;re de ce code de validation</li>\n";

		return $code . "\t\t</ul>\n\t\t<p>Code <input type=\"text\" name=\"code\" style=\"width:100px;\" /></p>\n";
	}

	public function CodeOK($nom, $courriel, $objet, $message, $codeFourni)
	{
		$réponse = array($nom, $courriel, $objet, $message);
		$position = array(0, 1, -2, -1);	// position : premier, deuxième, avant dernier et dernier

		// construction de la solution issue des instructions.
		$code = '';
		for($i=0; $i<4; $i++)	$code .= substr($réponse[$this->T_id_champ[$i]] ,$position[$this->T_choix[$i]], 1); // i-ème instruction
		$code .= substr($code, $this->dernier_choix, 1); // dernier caractère

		return ($code == $codeFourni);
	}

	public function getConfiguration()
	{
		// tableau T_id_champ
		$json = '{ "T_id_champ":[' . $this->T_id_champ[0] . ', ' . $this->T_id_champ[1] . ', ' . $this->T_id_champ[2] . ', ' . $this->T_id_champ[3] . '], ';
		// tableau T_choix
		$json .= '"T_choix":[' . $this->T_choix[0] . ', ' . $this->T_choix[1] . ', ' . $this->T_choix[2] . ', ' . $this->T_choix[3] . '], ';
		// dernier_choix
		$json .= '"dernier_choix":' . $this->dernier_choix . '}';
		return $json;
	}

//	fonctions pour le test ===========================================================================================

}
