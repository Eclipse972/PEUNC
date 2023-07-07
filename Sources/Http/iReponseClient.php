<?php
namespace PEUNC\Http;

/**
 * La réponse agrège toutes les éléments construits par le controleur
 * C'est elle qui permet l'affichage de ces éléments dans la vue
 */

interface iReponseClient
{
public function Element($nom);  // affiche l'élément nommé (il peut être un tableau)
public function Menu();         // Affiche la balise nav et son contenu
public function View();         // renvoie le nom complet de la vue
public function CSS();          // liste des liens CSS
}
