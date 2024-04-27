<?php // Exception pour les erreurs de l'application
namespace PEUNC\Erreur;

class Exception extends \Exception
{
	const LISTE = array(
		# requête http
		100 => 'M&eacute;thode http inconnue',
		101 => 'Jeton CSRF inexistant ou invalide',

		// ReponseClient
		200 => 'La classe de page n&apos;est pas d&eacute;finie dans le squelette.',

		// Formulaire
		300 => 'Méthode inatendue pour un formulaire',

		// Connexion
		400 => 'Utilisateur en doublon',

		// Controleur
		500 => 'Vue inexistante',
		501 => 'Controleur non d&eacute;fini',
		502 => 'Controleur inexistant',
		503 => 'fonction MENU: erreur de param&egrave;tre',
		504 => 'fonction MENU: erreur deuxième paramètre',

		// BDD
		600 => 'fichier de config de la base des donn&eacute;es absent',
		601 => 'requ&ecirc;te avec un type de param&egrave;tre non g&eacute;r&eacute;',
		602 => 'Requ&ecirc;te de suppression dangereuse',
		603 => 'les param&egrave;tres sont obligatoires pour l&apos;ajout en BDD',
		604 => 'les param&egrave;tres sont obligatoires pour la mise &agrave; jour en BDD',

		// Utilisateur
		700 => 'Pseudo utilis&eacute; plusieurs fois',

		# Routeur
		800 => 'le controleur n&apos;existe pas',
		801 => 'la m&eacute;thode de controleur n&apos;existe pas',
		
		// inconnue
		0 => 'Erreur inconnue'
	);

	public function __construct($param, $complement = '')
	{
		if (is_int($param))
		{
			$message = array_key_exists($param, self::LISTE) ?
						self::LISTE[$param] : 
						self::LISTE[0] . ' code: ' . $param;
			$message .= '<br>' . $complement;
		}
		else $message = $param;

		parent::__construct($message);
	}
}
