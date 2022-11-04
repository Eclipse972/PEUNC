<?php // Exception pour les erreurs serveurs
namespace PEUNC;

class ServeurException extends \Exception
{
	public function __construct($code)
	{
		$ListeErreurs = array(
				403 => "Acc&egrave;s interdit",
				404 => "Cette page n&apos;existe pas",
				500 => "Serveur satur&eacute;"
				// rajouter ici les nouvelles erreurs reconnues
			);
		$message = isset($ListeErreurs[$code]) ? $ListeErreurs[$code] : "Erreur serveur inconnue";
		parent::__construct($message, $code);
	}
}
