<?php
// classe-mère des controleurs de PEUNC
namespace PEUNC\Controleur;

use PEUNC\Http\HttpRoute;
use PEUNC\Erreur\Exception;

class BaseControleur implements iBaseControleur
{
// des listes
protected $T_element;	// tableau asociatif des éléments simples à afficher (chaîne de caractères ou nombre)

protected $route;	// la route http

public function __construct(HttpRoute $route = null)
{
	$T_element= array(
		'T_CSS'		=> [],	// liste des feuilles CSS
		'T_nav'		=> [],	// liste du code pour le menu en liste de listes (ul)
		'vue'		=> 'doctype.html',
		'dossierCSS'=> 'CSS'
	);
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
	$fichier = '/' . $this->T_element['dossierCSS'] . '/' . $feuilleCSS . '.css';
	// A faire: vérification de l'existence
	$this->T_element['T_CSS'][] = $fichier;	// ajout d'un CSS à la liste
}
}
