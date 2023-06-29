<?php
namespace PEUNC\Macros;

use PEUNC\Erreur\Exception;

class Fonction
{
// Intervalle pour  le niveau alpha (les onglets)
const ALPHA_MINI = 10;
const ALPHA_MAXI = 20;

public static function MENU(HttpRoute $route, $niveau, $profondeur, $alphaMini = self::ALPHA_MINI, $alphaMaxi = self::ALPHA_MAXI)
{
	if (($niveau <= 0) || ($profondeur < 0) || ($niveau + $profondeur > 3))	throw new Exception(503);

	switch($niveau)
	{
		case 1:
			$ancienTableau = BDD::Liste_niveau($route->getAlpha());
			// suppression des lignes en dehors de l'intervalle
			$Tableau = [];
			foreach($ancienTableau as $i => $ligne)
				if(($i >= $alphaMini) && ($i <= $alphaMaxi))
					$Tableau[$i] = $ligne;
			break;
		case 2:
			$Tableau = BDD::Liste_niveau($route->getBeta(), $route->getAlpha());
			break;
		case 3:
			$Tableau = BDD::Liste_niveau($route->getGamma(), $route->getAlpha(), $route->getBeta());
			break;
		default: throw new Exception(504);
	}
	if (count($Tableau) > 0)
	{
		self::DebutMenu($T_code);
		foreach($Tableau as $i => $ligne)
		{
			self::AjouteItem($T_code, $ligne);

			if ((substr($ligne, 0, 6) == '<a id=') && ($niveau < 4) && ($profondeur > 0))	// y a-t-il un niveau inférieur à afficher?
			//	pour	chaque ligne de code du sous-menu s'il existe			on rajoute le sous-code
				foreach(self::MENU($route, $niveau+1, $profondeur-1) as $ligne)	$T_code[] = $ligne;
		}
		self::FinMenu($T_code);
		return $T_code;
	} else return [];
}

// pour les méthodes suivantes le tableau en paramètre est passé par variable
public static function DebutMenu(&$Tableau)	{ $Tableau[] = '<ul>'; }

public static function FinMenu(&$Tableau)	{ $Tableau[] = '</ul>'; }

public static function AjouteItem(&$Tableau, $ligne){ $Tableau[] = '<li>' . $ligne . '</li>'; }
}