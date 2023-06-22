<?php
namespace PEUNC\Autre;

class JetonCSRF extends Jeton
{
    require 'Config/config_chiffrement.php';
    /* fichier qui ne doit pas être suivi par git et défini les constantes
     * CIPHER
     * KEY
     * IV
     */

    public function __construct()
    {
        $this->liste = array(
                'date' => time(),
                // d'autres paramètres doivent être ajoutés comme l'URL de traitement
            );
    }

    public function Chiffre()
    {
        return openssl_encrypt(json_encode($this->liste), self::CIPHER, self::KEY, 0, self::IV);
    }

    public function Dechiffre($chaine)
    {
        $this->liste = json_decode(openssl_decrypt($chaine, self::CIPHER, self::KEY, 0, self::IV),true);
    }

    public function InsererJeton()
    {
        $jeton = $this->Chiffre();
        $_SESSION['PEUNC']['CSRF'] = $jeton;
        return '<input name="CSRF" type="hidden" value="' . $jeton . '">' . "\n";
    }
}
