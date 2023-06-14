<?php
namespace PEUNC\Controleur;

/* PEUNC est capable de traiter un certain nombre d'erreur sous forme d'envoi d'exception. voir la partie catch de index.php
 * 
 * Les exceptions gérée par PEUNC :
 * Les erreurs serveur
 * BDD via PDOException
 * les erreur application de PEUNC
 * */
use PEUNC\Http\HttpRoute;

class Erreur extends Page
{
	public function __construct(HttpRoute $route = null)	{ parent::__construct($route); }

	public function NoeudArborescence()
	{	// permet d'afficher le noeud dans la vue s'il existe
		if (isset($this->route))
			$code = "<p>Noeud : " . $this->route->getAlpha() . " - " . $this->route->getBeta() . " - " . $this->route->getGamma()
					. " m&eacute;thode http:" . $this->route->getMethode() . "</p>\n";
		else $code ="";	// pas de route http pour les erreurs serveurs
		return $code;
	}
}
