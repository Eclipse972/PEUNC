<?php
namespace PEUNC\Autre;

use PEUNC\Http\HttpRoute;

class JetonCSRF extends Jeton
{
    const CONFIG = 'Config/config_chiffrement.php';

    public function __construct(HttpRoute $route)
    {
        $this->liste = array(
                'date' => time(),
                'URL' => $route->getURL();
            );
    }

    public function Chiffre()
    {
        require self::CONFIG;
        return openssl_encrypt(json_encode($this->liste), $cipher, $key, 0, $iv);
    }

    public function Dechiffre($chaine)
    {
        require self::CONFIG;
        $this->liste = json_decode(openssl_decrypt($chaine, $cipher, $key, 0, $iv),true);
    }
}
