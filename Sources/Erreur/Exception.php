<?php // Exception pour les erreurs de l'application
namespace PEUNC\Erreur;

class Exception extends \Exception
{
	public function __construct($param = 0)
	{
		$T_erreurs = array(
			// HttpRoute
			100 => "M&eacute;thode non prise en charge",
			101 => "Jeton CSRF inexistant",
			102 => "Jeton CSRF invalide",

			// ReponseClient
			200 => "La classe de page n&apos;est pas d&eacute;finie dans le squelette.",

			// Formulaire
			300 => "Méthode inatendue pour un formulaire",
			301 => "Configuration chifrement absente",

			// Connexion
			400 => "Utilisateur en doublon",

			// Page
			500 => "Vue inexistante",
			501 => "Controleur non d&eacute;fini",
			502 => "Controleur inexistant",
			503 => "fonction MENU: erreur de param&egrave;tre",
			504 => "fonction MENU: erreur deuxième paramètre",

			// BDD
			600 => "fichier de config de la base des donn&eacute;es absent",
			601 => "requ&ecirc;te avec un type de param&egrave;tre non g&eacute;r&eacute;",
			602 => "Requ&ecirc;te de suppression dangereuse",
			603 => "les param&egrave;tres sont obligatoires pour l'ajout en BDD",
			604 => "les param&egrave;tres sont obligatoires pour la mise &agrave; jour en BDD",

			// Utilisateur
			700 => "Pseudo utilis&eacute; plusieurs fois",

			// inconnue
			0 => "Erreur inconnue"
		);

		if (is_int($param))
			$message = isset($T_erreurs[$param]) ? $T_erreurs[$param] : $T_erreurs[0] . " code: " . $param;
		else
			$message = $param;

		parent::__construct($message);
	}
}
