<?php		// API de la classe User de PEUNC
namespace PEUNC\Modele;

interface iUtilisateur
{
// créer un destructeur est obligatoire car un utilisateur est toujours lié à d'autres objets

// Assesseurs (setters)
	public function setID($entier);
	public function setPseudo($texte);
	public function setMDP($texte);		// remplacé par le hash dans le futur
	public function setNom($texte);
	public function setPrenom($texte);
	public function setCouriel($texte);

// Mutateur (getters)
	public function getID();
	public function getPseudo();
	public function getMDP();
	public function getNom();
	public function getPrenom();
	public function getCouriel();

// Autres
	public function PseudoExiste($texte);
	public function MDPcorrect($texte);
}
