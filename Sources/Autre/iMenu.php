<?php
namespace PEUNC\Autre;

use PEUNC\Http\HttpRoute;

interface iMenu
{
/**
 * Un menu est une simple liste de liens. Ces liens sont ensuite formatés pour leur donner leur apparence
 * Mais le code html d'un menu sur deux niveaux ressemble à:
 * <ul>
 * <li><a href=URL>item 1</a></li>
 * <li><a href=URL>item 2</a></li>
 *	<ul>
 *		<li><a href=URL>item 2.1</a></li>
 *		<li><a href=URL>item 2.1</a></li>
 *		<li><a href=URL>item 2.1</a></li>
 * </ul>
 * <li><a href=URL>item 3</a></li>
 * <li><a href=URL>item 4</a></li>
 * <li><a href=URL>item 5</a></li>
 * </ul>
 **/


// menu sur deux niveaux
public static function AlphaBeta(HttpRoute $route, $alphaMini, $alphaMaxi);
public static function BetaGamma(HttpRoute $route);

/**
 * Menu sur un niveau
 * Utilisation possibles:
 * 1- générer une liste d'onglet
 * 2- Créer un menu auquel on rajoutera un sous-menu manuellement
 **/
public static function Alpha(HttpRoute $route, $alphaMini, $alphaMaxi);

/**
 * Création d'un menu de toutes pièces
 **/
}