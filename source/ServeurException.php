<?php // Exception pour les erreurs serveurs
namespace PEUNC;

class ServeurException extends \Exception
{
	public function __construct($code)
	{
		$ListeErreurs = array(
				403 => "Acc&egrave;s interdit",
				404 => "Cette page n&apos;existe pas",
				405 => "M&eacute:thode non prise en charge",
				500 => "Serveur satur&eacute;"
				// rajouter ici les nouvelles erreurs reconnues
			);
		$message = isset($ListeErreurs[$code]) ? $ListeErreurs[$code] : "Erreur serveur N°" . $code;
		parent::__construct($message, $code);
	}
}
