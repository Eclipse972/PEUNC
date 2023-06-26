<?php
namespace PEUNC\Configuration;
/**
 * Dans votre application il faudra renommer le namespace en App\Configuration par exmemple.
 * Sauvegarder connexionBDD.php dans le dossier App/Configuragion
 * Ajouter "use App\Configuration\connexionBDD" en début de script pour utiliser la classe.
 * L'autoloader la chargera automatiquement pour vous.
 * Pour utiliser une variable: connexionBDD::variable. Par exemple connexionBDD::user.
 *      _
 *     / \
 *    / | \     Ce fichier ne DOIT PAS être suivi par git.
 *   /  |  \    
 *  /___o___\   Pour cela ajouter la ligne: App/Configuration/ dans .gitignore
 */
class connexionBDD
{
    const host  = 'serveur.fr';
    const dbname= 'base_de_données';
    const user  = 'utilisateur';
    const pwd   = 'mot_de_passe';
}
