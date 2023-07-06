<?php
namespace PEUNC\Controleur;

class ConnexionControleur extends BaseControleur
{
/**
 * Porte d'entrée du site
 */
public function __construct(HttpRoute $route)
{
    parent::__construct($route);
}

public function Afficher()
{   // construction de la page de connexion
    $this->setVue('connexion.html');
    $this->AjouteCSS('commun');
    $this->AjouteCSS('connexion');
    $this->set('titre', 'Titre de la page (dans le navigateur');
    $this->set('header', 'En-tête de la page');
    $this->set('complement', 'insérer ici du code html à rajouter sous le formulaire');
    $jeton = new jetonCSRF;

}

public function Traitement()
{   // vérification des données du formulaire

}
}
