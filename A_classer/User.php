<?php	// 
namespace PEUNC;

include"API_user.php";

class User implements iUser
{
	protected $ID;
	protected $pseudo;
	protected $MDP;
	protected $nom;
	protected $prenom;
	protected $courriel;

	public function __construct()
	{
		$this->ID		= null;
		$this->pseudo	= "Anonyme";
		$this->MDP		= "Mot2passe";
		$this->nom		= "Nyme";
		$this->prenom	= "Arnaud";
		$this->courriel = "arnaud.nyme@free.fr";
	}

// SETTERS ========================================================================================
	public function setID($entier)		{ $this->ID			= $entier; }
	public function setPseudo($texte)	{ $this->pseudo		= $texte; }
	public function setMDP($texte)		{ $this->MDP		= $texte; }
	public function setNom($texte)		{ $this->nom		= $texte; }
	public function setPrenom($texte)	{ $this->prenom		= $texte; }
	public function setCouriel($texte)	{ $this->courriel	= $texte; }
	
// GETTERS ========================================================================================
	public function getID()		{ return $this->ID; }
	public function getPseudo()	{ return $this->pseudo; }
	public function getMDP()	{ return $this->MDP; }
	public function getNom()	{ return $this->nom; }
	public function getPrenom()	{ return $this->prenom; }
	public function getCouriel(){ return $this->courriel; }

// AUTRES =========================================================================================
	public function PseudoDejaPris($texte)
	{
	}
	
	public function MDPincorrect($texte)	{ return ($this->MDP != $texte); }
}
