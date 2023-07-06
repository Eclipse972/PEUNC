<?php
namespace PEUNC\Autre;

use VolEval\Configuration\Chiffrement;
/**
 * Remplacer VomEval par votre application
 * Classe chargée automatiquement par l'autoloader
 * définit les constantes CIPHER, KEY et IV
 */
class JetonCSRF extends Jeton
{
public function __construct(HttpRoute $route)
{
    $this->liste = array(
            'date' => time(),
            'noeud' => [$route->getAlpha(), $route->getBeta(), $route->getGamma()] // position dans l'arborescence
        );
}

public function Chiffre()
{
    return openssl_encrypt(
        json_encode($this->liste),
        Chiffrement::CIPHER,
        Chiffrement::KEY,
        0,
        Chiffrement::IV
    );
}

public function Dechiffre($chaine)
{
    return  json_decode(
        openssl_decrypt($chaine, Chiffrement::CIPHER, Chiffrement::KEY, 0, Chiffrement::IV),
        true
    );
}

public function InsererJeton()
{
    $jeton = $this->Chiffre();
    $_SESSION['PEUNC']['CSRF'] = $jeton;
    return '<input name="CSRF" type="hidden" value="' . $jeton . '">' . "\n";
}
}
