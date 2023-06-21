<?php
namespace PEUNC\Autre;

class Jeton
/* Un jeton est un objet JSON encodé en base64. Il sert à stocker des informations.
 *
 * Il sert de base à d'autres type de jetons
 * CSRF: le jeton d'origine est chiffré avec un algo à clé secrète
 * JWT simplifié: après encodage en base 64, on ajoute une signature numérique prouvant son origine
 */
{
    protected $liste;   // un tableau associatif

    public function __construct()
    {
        $this->liste = [];
    }

    public function Ajouter($cle, $valeur)
    {
        $this->liste[$cle] = $valeur;
    }

    public function Lire($cle)
    {
        return $this->liste[$cle];
    }

    public function Encode()
    {
        return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode(json_encode($this->liste)));
    }

    public function Decode($chaine)
    {
        $this->liste = json_decode(base64_decode($chaine),true);
    }
}
