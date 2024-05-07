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
            'date'=> time(),  # utile pour vérifier un temps de remplissage
            'URL' => $route->getURL()
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

public static function Dechiffre()
{
    return json_decode(
        openssl_decrypt($_POST['CSRF'],
						Chiffrement::cipher,
						Chiffrement::key,
						0,
						Chiffrement::iv),
        true
    );
}

public function InsererJeton()
{
    $jeton = $this->Chiffre();
    return '<input name="CSRF" type="hidden" value="' . $jeton . '">' . "\n";
}

public static function Verifier(int $duréeMax = 600)
{
    return array_key_exists('CSRF', $_POST)
		&& ($jeton = self::Dechiffre()) !== null
		&& isset($jeton['date'])
		&& $jeton['date'] - time() < $duréeMax
		&& isset($jeton['URL']);
}
}
