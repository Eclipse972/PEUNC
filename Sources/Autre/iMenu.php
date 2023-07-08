<?php
namespace PEUNC\Autre;

use PEUNC\Http\HttpRoute;

interface iMenu
{
public static function AlphaBeta(HttpRoute $route, $alphaMini, $alphaMaxi);
public static function BetaGamma(HttpRoute $route);

}