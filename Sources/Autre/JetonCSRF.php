<?php
namespace PEUNC\Autre;

use PEUNC\Http\HttpRoute;
use VolEval\Configuration\Chiffrement;
/**
 * Remplacer VomEval par votre application
 * Classe chargée automatiquement par l'autoloader
 * définit les constantes cipher, key et iv
 */
class JetonCSRF extends Jeton
{
public function __construct(HttpRoute $route)
{
    $this->liste = array(
            'date'  => time(),
            'URL'   => $route->getURL() // URL du formulaire
        );
}

public function Chiffre()
{
    return openssl_encrypt(
        json_encode($this->liste),
        Chiffrement::cipher,
        Chiffrement::key,
        0,
        Chiffrement::iv
    );
}

public static function Dechiffre($chaine)
{
    return  json_decode(
        openssl_decrypt($chaine, Chiffrement::cipher, Chiffrement::key, 0, Chiffrement::iv),
        true
    );
}

public function InsererJeton()
{
    $jeton = $this->Chiffre();
    $_SESSION['PEUNC']['CSRF'] = $jeton;
    return '<input name="CSRF" type="hidden" value="' . $jeton . '">' . "\n";
}

public static function Verifier($chaine)
{
    return ($_SESSION['PEUNC']['CSRF'] != $chaine);
}
}
