<?php
namespace PEUNC\Autre;

use PEUNC\Http\HttpRoute;

class Menu implements iMenu
{
/**
 * menu sur deux niveaux
 **/
public static function AlphaBeta(HttpRoute $route, $alphaMini, $alphaMaxi)
{	// menu sur 2 niveaux: premier alpha et deuxième beta
    return self::ConversionEnMenu(
        BDD::SELECT('	alpha AS niveau1, beta AS niveau2, CONCAT("<li><a href=",URL,">",titre,"</a></li>") AS lien
                        FROM Vue_route
                        WHERE (alpha>='.$alphaMini.' AND alpha<='.$alphaMaxi.' AND beta=0 AND gamma=0) OR (alpha=? AND beta>0 AND gamma=0)
                        ORDER BY alpha, beta',
                        [$route->getAlpha()]),
        $route->getAlpha(),
        $route->getBeta()
    );
}

public static function BetaGamma(HttpRoute $route)
{

}

/**
 * Menu sur un niveau
 **/
public static function Alpha(HttpRoute $route, $alphaMini, $alphaMaxi)
{	// menu sur le niveau alpha
    return self::ConversionEnMenu(
        BDD::SELECT('	alpha AS niveau1, beta AS niveau2, CONCAT("<li><a href=",URL,">",titre,"</a></li>") AS lien
                        FROM Vue_route
                        WHERE alpha>='.$alphaMini.' AND alpha<='.$alphaMaxi.' AND beta=0 AND gamma=0
                        ORDER BY alpha, beta',
                        [$route->getAlpha()]),
        $route->getAlpha(),
        $route->getBeta()
    );
}



private static function ConversionEnMenu(array $Liste, $selectionNiveau1, $selectionNiveau2)
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
