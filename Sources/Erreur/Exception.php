<?php // Exception pour les erreurs de l'application
namespace PEUNC\Erreur;

class Exception extends \Exception
{
	const LISTE = array(
		// HttpRoute
		100 => 'M&eacute;thode non prise en charge',
		101 => 'Jeton CSRF inexistant',
		102 => 'Jeton CSRF invalide',

		// ReponseClient
		200 => 'La classe de page n&apos;est pas d&eacute;finie dans le squelette.',

		// Formulaire
		300 => 'Méthode inatendue pour un formulaire',
		301 => 'Jeton CSFR invalide',

		// Connexion
		400 => 'Utilisateur en doublon',

		// Page
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
