<?php
// classe-mère des controleurs de PEUNC
namespace PEUNC\Controleur;

use PEUNC\Http\HttpRoute;
use PEUNC\Erreur\Exception;

class BaseControleur implements iBaseControleur
{
const dossierCSS = 'CSS';
const dossierVue = 'Application/Vue';

protected $T_element = [];	// tableau asociatif des éléments simples à afficher (chaîne de caractères ou nombre)
protected $vue;				// chemin vers la vue
protected $T_CSS = [];		// liste des feuilles CSS
protected $T_nav = [];		// liste des instructions html de la balise nav
protected $route;			// la route http

public function __construct(HttpRoute $route)
{
	$this->route = $route;
	$this->setVue('doctype.html');
}

// implémentation de l'interface ==========================================================================

// les éléments
public function set($nom, $valeur)	{ $this->T_element[$nom] = $valeur; }

public function get($nom = null)
{
	if(is_null($nom))	return $this->T_element;

	if(!array_key_exists($nom, $this->T_element))
		throw new Exception('Controleur: élément ' . $nom . ' inexistant');
	return $this->T_element[$nom];
}

// feuille de style
public function AjouteCSS($feuilleCSS)
{
	$fichier = self::dossierCSS . '/' . $feuilleCSS . '.css';
	// A faire: vérification de l'existence
	$this->T_CSS[] = $fichier;	// ajout d'un CSS à la liste
}

public function getCSS()	{ return $this->T_CSS; }

// vue
public function setVue($fichier)
{
	$fichier = self::dossierVue . '/' . $fichier;
	// test existence à faire
	
	$this->vue = $fichier;
}

public function getVue()	{ return $this->vue; }

// balise nav
public function getNav()	{ return $this->T_nav; }

// fin de l'implémentation de l'interface

}
