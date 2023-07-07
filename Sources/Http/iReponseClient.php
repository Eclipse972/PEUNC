<?php
namespace PEUNC\Http;

/**
 * La réponse agrège toutes les éléments construits par le controleur
 * C'est elle qui gère l'affichage de ces élément dan la vue
 */

interface iReponseClient
{
public function Element($nom);  // affiche l'élément nommé
public function Menu();         // Affiche la balise nav et son contenu
}