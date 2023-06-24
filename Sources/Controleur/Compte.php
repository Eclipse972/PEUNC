<?php
namespace PEUNC\Controleur;

use PEUNC\Autre\BDD;
use PEUNC\Http\HttpRoute;

class Compte extends BaseControleur
{
    // $route défini chez le parent

    public function __construct(HttpRoute $route)
    {
        parent::__construct($route);
    }

    public function Creer()  // formulaire pour créer un compte
    {

    }
 
    public function MettreEnAttente() // Enregistrer le compte en attente de confirmation
    {

    }

    public function Activer()   // activer le compte
    {

    }

   public function Connecter() // formulaire de connexion
    {

    }

    public function Autoriser() // autorise l'accès ou pas
    {

    }

    public function Recuperer() // formulaire oubli de MDP
    {

    }

    public function Recuperer2()    // vérifier la récupération
    {

    }

    public function Deconnecter()
    {

    }

    public function Montrer()
    {

    }

    public function Supprimer()
    {

    }

    // méthodes annexes
}
