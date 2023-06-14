<?php	//
use PEUNC\Erreur\Exception;
use PEUNC\Autre\BDD;

namespace PEUNC\Modele;

include"API_utilisateur.php";

class Utilisateur implements iUtilisateur
{
	protected $ID;
	protected $pseudo;
	protected $MDP;
	protected $nom;
	protected $prenom;
	protected $courriel;

	public function __construct($id = 0)
	{
		// on charge depuis la BDD
		$reponse = BDD::SELECT("* FROM Utilisateur WHERE ID = ?", [$id],true);

		if(isset($reponse))
		{	// utilisateur enregistré
			$this->ID		= $reponse["ID"];
			$this->pseudo	= $reponse["pseudo"];
			$this->MDP		= $reponse["MDP"];
			$this->nom		= $reponse["nom"];
			$this->prenom	= $reponse["prenom"];
			$this->courriel = $reponse["courriel"];
		} else
		{	// utilisateur anonyme
			$this->ID		= 0;
			$this->pseudo	= "Anonyme";
			$this->MDP		= "Mot2passe";
			$this->nom		= "Nyme";
			$this->prenom	= "Arnaud";
			$this->courriel = "arnaud.nyme@free.fr";
		}
	}

// SETTERS =======================================================================================
	public function setID($entier)		{ $this->ID			= $entier; }
	public function setPseudo($texte)	{ $this->pseudo		= $texte; }
	public function setMDP($texte)		{ $this->MDP		= $texte; }
	public function setNom($texte)		{ $this->nom		= $texte; }
	public function setPrenom($texte)	{ $this->prenom		= $texte; }
	public function setCouriel($texte)	{ $this->courriel	= $texte; }

// GETTERS =======================================================================================
	public function getID()		{ return $this->ID; }
	public function getPseudo()	{ return $this->pseudo; }
	public function getMDP()	{ return $this->MDP; }
	public function getNom()	{ return $this->nom; }
	public function getPrenom()	{ return $this->prenom; }
	public function getCouriel(){ return $this->courriel; }

// AUTRES ========================================================================================
	public function PseudoExiste($texte)
	{
		switch(BDD::SELECT("count(*) FROM Utilisateur WHERE pseudo = ?", [$texte],true))
		{
			case 0:
				return false;
				break;
			case 1:
				return true;
				break;
			default: throw new Exception(700);
		}
	}

	public function MDPcorrect($texte)	{ return ($this->MDP == $texte); }

	public function Enregistrer()	// dans la BDD
	{
		// vérifier que le pseudo n'existe pas
		// enregistrer tous les champs dans la BDD
	}
}
