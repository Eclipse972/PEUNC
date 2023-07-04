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

// méthodes pour les menus extraits de l'arboresence
public function MenuAlphaBeta($alphaMini, $alphaMaxi)
{	// menu niveau 1 et 2
	$Liste = // recueille la liste des items du menu et du sous-menu
		BDD::SELECT('	alpha, beta, CONCAT("<li><a href=",URL,">",titre,"</a></li>") AS lien
						FROM Squelette
						WHERE (alpha>='.$alphaMini.' AND alpha<='.$alphaMaxi.' AND beta=0 AND gamma=0) OR (alpha=? AND beta>0 AND gamma=0)
						ORDER BY alpha, beta, gamma',
						[$this->route->getAlpha()]);
	$T_menu = ['<nav>', '<ul>'];
	for ($i=0; $i < count($Liste); $i++)
	{ 
		if ($i>0) // à partir de la 2e ligne
		{
			if (($Liste[$i-1]['beta'] == 0) && ($Liste[$i]['beta'] > 0))
				$T_menu[] = '<ul>';
			elseif (($Liste[$i-1]['beta'] > 0) && ($Liste[$i]['beta'] == 0))
				$T_menu[] = '</ul>';
		}
		$instruction = $Liste[$i]['lien'];
		if ($Liste[$i]['alpha'] == $route->getAlpha())
		{
			if ($Liste[$i]['beta'] == 0)
				$instruction = str_replace('<a href', '<a id=alpha_actif href' , $instruction);
			elseif ($Liste[$i]['beta'] == $route->getBeta())
				$instruction = str_replace('<a href', '<a id=beta_actif href' , $instruction);
		}
		$T_menu[] = $instruction;
	}
	$T_menu[] = '</ul>';
	$T_menu[] = '</nav>';
	return $T_menu;
}

public function MenuBetaGamma($alphaMini, $alphaMaxi)
{
	// menu niveai 2 et 3
}
}
