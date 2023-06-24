<?php
namespace PEUNC\Controleur;

use PEUNC\Http\HttpRoute;
use PEUNC\Erreur\Exception;
use PEUNC\Autre\BDD;
use PEUNC\Controleur\Formulaire;

class Connexion extends Formulaire
{
	public function __construct(HttpRoute $route)
	{	parent::__construct($route);	}

// Création du code du formulaire ================================================================
	public function Login($texte = "Login", $champ = "login")
	{	return "<p>{$texte}</p>\n<input type=text name=\"{$champ}\" autofocus />\n";	}

	public function Mot2Passe($texte = "Mot de passe", $champ = "MDP")
	{	return "<p>{$texte}</p>\n<input type=password name=\"{$champ}\" />"; }

// Traitement du formulaire ======================================================================
	public function Traitement($nomCompletClasseUtilisateur = "Utilisateur")
	{
		switch(BDD::SELECT("count(*) FROM Utilisateur WHERE pseudo = ? AND hashMDP = ?", [$this->route->getParam("login"), sha1($this->route->getParam("MDP"))],true))
		{
			case 0:	// aucune correspondance
				$_SESSION["PEUNC_messageConnexion"] = "La connexion a &eacute;chou&eacute;e!";
				header("location: " . $this->route->getURL());	// retour au formulaire
				break;
			case 1:	// une correspondance
				$_SESSION["PEUNC_messageConnexion"] = "";
				$id = BDD::SELECT("ID FROM Utilisateur WHERE pseudo = ? AND hashMDP = ?",
								[$this->route->getParam("login"), sha256($this->route->getParam("MDP"))],
								true);
				$cle = array_pop(explode('\\', $nomCompletClasseUtilisateur));	// récupère le dernier élément du namespace
				$_SESSION[$cle] = serialize(new $nomCompletClasseUtilisateur($id));
				header("location: " . $this->jetonJSON->URLsuivante);	// page suivant la connexion
				break;
			default:// plusieurs correspondances
				throw new Exception(400);
		}
	}

	public static function MessageErreur()
	{	return (isset($_SESSION["PEUNC_messageConnexion"]) && $_SESSION["PEUNC_messageConnexion"] != "") ? "<p style=\"color:red\">{$_SESSION["PEUNC_messageConnexion"]}</p>\n" : "";	}
}
