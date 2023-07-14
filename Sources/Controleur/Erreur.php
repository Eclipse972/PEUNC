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
// informations fournies par l'exception attrappée par index.php
private $message;
private $code;

public function __construct(HttpRoute $route, $message, $code)
{
	parent::__construct($route);
	$this->message = $message;
	$this->code = $code;
	$this->setVue('erreur.html'); // vue par défaut
}

public function NoeudArborescence()
{	// permet d'afficher le noeud dans la vue s'il existe
	return isset($this->route) ?
			"<p>Noeud : " . $this->route->getAlpha() . 
				" - " . $this->route->getBeta() . 
				" - " . $this->route->getGamma(). 
				" m&eacute;thode http:" . $this->route->getMethodeHttp() . "</p>\n" :
			'';
}

// Les différents types d'erreur
public function Serveur()
{
	$this->set('type', 'serveur');
	$this->set('titre', $this->code.': '.$this->message);
	$this->set('contenu', '<img src=/images/serveur.png style="width:300px" alt="serveur" >');
}

public function Application()
{
	$this->set('type', 'application');
	$this->set('titre', 'Erreur de l&apos;application');
	$this->set('contenu', '<p>'.$this->message."</p>\n".self::NoeudArborescence());
}

public function BDD()
{
	$this->set('type', 'base de donn&eacute;es');
	$this->set('titre', $this->message);
	$this->set('contenu', self::NoeudArborescence());
}

public function Exception()
{
	$this->set('type', 'inconnue');
	$this->set('titre', $this->message);
	$this->set('contenu', self::NoeudArborescence());
}

}
