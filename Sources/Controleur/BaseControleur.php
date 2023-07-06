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

// implémetation de l'interface
public function set($nom, $valeur)	 { $this->T_element[$nom] = $valeur; }

public function get($clé)
{
	if(!array_key_exists($clé, $this->T_element))	throw new Exception('Controleur: clé ' . $clé . ' inexistante');
	return $this->T_element[$clé];
}

public function ToutRecuperer()
{
	return array(
		'T_element' => $T_element,
		'vue'		=> $vue,
		'T_CSS'		=> $T_CSS,
		'T_nav'		=> $T_nav,
		'route'		=> $route
	);
}

public function AjouteCSS($feuilleCSS)
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
// fin de l'implémentation de l'interface

public function Vue()			{ return $this->vue; }

/**
 * Méthodes pour créer des menus automatiquement à partir du Squelette et d'une route http
 * Le menu est une liste de liste de liens en html (balises ul, li et a)
 */
public function MenuAlphaBeta($alphaMini, $alphaMaxi)
{	// menu sur 2 niveaux: premier alpha et deuxième beta
	$Liste = // recueille la liste des items du menu et du sous-menu
		BDD::SELECT('	alpha AS niveau1, beta AS niveau2, CONCAT("<li><a href=",URL,">",titre,"</a></li>") AS lien
						FROM Squelette
						WHERE (alpha>='.$alphaMini.' AND alpha<='.$alphaMaxi.' AND beta=0 AND gamma=0) OR (alpha=? AND beta>0 AND gamma=0)
						ORDER BY alpha, beta',
						[$this->route->getAlpha()]);
	$T_menu = ['<nav>', '<ul>'];
	for ($i=0; $i < count($Liste); $i++)
	{ 
		if ($i>0) // à partir de la 2e ligne
		{
			if (($Liste[$i-1]['niveau2'] == 0) && ($Liste[$i]['niveau2'] > 0))
				$T_menu[] = '<ul>';
			elseif (($Liste[$i-1]['niveau2'] > 0) && ($Liste[$i]['niveau2'] == 0))
				$T_menu[] = '</ul>';
		}
		$instruction = $Liste[$i]['lien'];
		if(($Liste[$i]['niveau1'] == $this->route->getAlpha())
			&& (($Liste[$i]['niveau2'] == 0) || ($Liste[$i]['niveau2'] == $this->route->getBeta())))
		{
			$instruction = str_replace('<a href', '<a id=item_actif href' , $instruction);
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

public static function ConversionEnMenu(array $Liste, $selectionNiveau1, $selectionNiveau2)
{	/**
	 * Construit la liste d'instructions html à partir de la liste
	 * Chaque ligne contient id_niveau1 id_niveau2 lien_htmel
	 */
	$T_menu = ['<nav>', '<ul>'];
	for ($i=0; $i < count($Liste); $i++)
	{ 
		if ($i>0) // à partir de la 2e ligne
		{
			if (($Liste[$i-1]['niveau2'] == 0) && ($Liste[$i]['niveau2'] > 0))
				$T_menu[] = '<ul>';
			elseif (($Liste[$i-1]['niveau2'] > 0) && ($Liste[$i]['niveau2'] == 0))
				$T_menu[] = '</ul>';
		}
		$instruction = $Liste[$i]['lien'];
		if (($Liste[$i]['niveau1'] == $selectionNiveau1)
			&& (($Liste[$i]['niveau2'] == 0) || ($Liste[$i]['niveau2'] == $selectionNiveau2)))
		{
				$instruction = str_replace('<a href', '<a id=item_actif href' , $instruction);
		}
		$T_menu[] = $instruction;
	}
	$T_menu[] = '</ul>';
	$T_menu[] = '</nav>';
	return $T_menu;
}
}
