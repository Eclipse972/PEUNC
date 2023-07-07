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

class Erreur extends BaseControleur
{
public function __construct(HttpRoute $route)
{
	parent::__construct($route);
	$this->setVue('erreur.html'); // vue par défaut
}

public function NoeudArborescence()
{	// permet d'afficher le noeud dans la vue s'il existe
	if (isset($this->route))
		$code = "<p>Noeud : " . $this->route->getAlpha() . " - " . $this->route->getBeta() . " - " . $this->route->getGamma()
				. " m&eacute;thode http:" . $this->route->getMethodeHttp() . "</p>\n";
	else $code ="";	// pas de route http pour les erreurs serveurs
	return $code;
}

// vue erreur à concevoir

// Les différents types d'erreur
public function Serveur()
{
	$this->set('type', 'serveur');
}

public function Application()
{
	$this->set('type', 'application');
}

public function BDD()
{
	$this->set('type', 'base de donn&eacute;es');
}

public function Exception()
{
	$this->set('type', 'inonnue');
}

}
