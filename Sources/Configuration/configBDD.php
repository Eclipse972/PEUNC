<?php
namespace PEUNC\Configuration;
/**
 * Dans votre application il faudra renommer le namespace en App\Configuration par exmemple.
 * Sauvegarder configBDD.php dans le dossier App/Configuragion
 * Ajouter "use App\Configuration\configBDD" en début de script pour utiliser la classe.
 * L'autoloader la chargera automatiquement pour vous.
 * Pour utiliser une variable: configBDD::variable. Par exemple configBDD::user.
 *      _
 *     / \
 *    / | \     Ce fichier ne DOIT PAS être suivi par git.
 *   /  |  \    
 *  /___o___\   Pour cela ajouter la ligne: App/Configuration/ dans .gitignore
 */
class configBDD
{
    const host = 'serveur.fr';
    const dbname ='base_de_données';
    const user = 'utilisateur';
    const pwd = 'mot_de_passe';
}
