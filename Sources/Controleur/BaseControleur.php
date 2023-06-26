<?php
// classe-mère des controleurs de PEUNC
namespace PEUNC\Controleur;

use PEUNC\Http\HttpRoute;
use PEUNC\Erreur\Exception;
use PEUNC\Autre\BDD;

class BaseControleur implements iBaseControleur
{
	// des listes
	protected $T_CSS	= [];	// feuilles CSS
	protected $T_nav	= [];	// intructions d'un menu qui est une liste de listes (<ul>, <li>) 
	protected $T_element= [];	// tableau asociatif des éléments simples à afficher (chaîne de caractères ou nombre)
	
	protected $vue;		// la vue pour l'affichage
	protected $route;	// la route http

	public function __construct(HttpRoute $route = null)
	{
		$this->vue = 'Application/Vue/doctype.html';
		$this->route = $route;
	}
/* ***************************
 * MUTATEURS (SETTER)
 * ***************************/
	public function setNav(array $code)		{ $this->T_nav = $code;	}

	public function setView($nomCompletFifichier)	{ $this->vue = $nomCompletFifichier; }

/* ***************************
 * ASSESSURS (GETTER)
 * ***************************/
	public function getView()	{ return $this->vue; }

	public function getCSS()	{ return $this->T_CSS; }

	public function getNav()	{ return $this->T_nav; }

	public function get($clé)
	{
		if(array_key_exists($clé, $T_element))
			return $T_element[$clé];
		else throw new PEUNC\Erreur\Exception('Controleur: clé ' . $clé . ' inexistante');
	}
/*****************************
 * Autres
 *****************************/	
	public function AjouteCSS($nomCompletFeuilleCSS)
	{
		$this->T_CSS[] = $nomCompletFeuilleCSS;	// ajout d'un CSS à la liste sans vérification
	}
}
