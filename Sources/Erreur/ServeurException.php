<?php // Exception pour les erreurs serveurs
namespace PEUNC\Erreur;

class ServeurException extends \Exception
{
	const LISTE = array(
		403 => "Acc&egrave;s interdit",
		404 => "Cette page n&apos;existe pas",
		405 => "M&eacute:thode non prise en charge",
		500 => "Serveur satur&eacute;"
		// rajouter ici les nouvelles erreurs reconnues
	);

	public function __construct($code)
	{		
		$message = array_key_exists($code, self::LISTE) ? self::LISTE[$code] : "Erreur serveur N°" . $code;
		parent::__construct($message, $code);
	}
}
