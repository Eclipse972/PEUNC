<?php
namespace PEUNC\Configuration;
/**
 * Dans votre application il faudra renommer le namespace en App\Configuration par exmemple.
 * Sauvegarder Chiffrement.php dans le dossier App/Configuragion
 * Ajouter "use App\Configuration\Chiffrement" en début de script pour utiliser la classe.
 * L'autoloader la chargera automatiquement pour vous.
 * Pour utiliser une variable: Chiffrement::variable. Par exemple Chiffrement::cipher.
 *      _
 *     / \
 *    / | \     Ce fichier ne DOIT PAS être suivi par git.
 *   /  |  \    
 *  /___o___\   Pour cela ajouter la ligne: App/Configuration/ dans .gitignore
 */
class Chiffrement
{
    const cipher = 'AES-128-CBC';
    const key ='clé_de_chiffrement';
    const iv = "vecteur_d_initialisation";
}
