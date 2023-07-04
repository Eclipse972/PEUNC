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
protected $vue = 'doctype.html';	// chemin vers la vue
protected $T_CSS = [];	// liste des feuilles CSS
protected $T_nav = [];	// 
protected $route;	// la route http

public function __construct(HttpRoute $route = null)
{
	$this->route = $route;
}

private function set($nom, $valeur)	 { $this->T_element[$nom] = $valeur; }

public function get($clé)
{
	if(!array_key_exists($clé, $this->T_element))	throw new Exception('Controleur: clé ' . $clé . ' inexistante');
	return $this->T_element[$clé];
}

private function AjouteCSS($feuilleCSS)
{
	$fichier = self::dossierCSS . '/' . $feuilleCSS . '.css';
	// A faire: vérification de l'existence
	$this->T_CSS[] = $fichier;	// ajout d'un CSS à la liste
}

public function setVue($fichier)
{
	$fichier = self::dossierVue . $fichier;
	// test existence à faire
	
	$this->vue = $fichier;
}
public function Vue()			{ return $this->vue; }
}
