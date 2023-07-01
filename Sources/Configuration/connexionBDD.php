<?php
namespace PEUNC\Configuration; // à remplacer par Application\Configuration

/**
 * Fichier de configuration avec PDO
 * =================================
 * Cette classe permet de sauvegarder deux configurations: une pour le serveur de développement
 * et l'autre pour le serveur de production.
 *              
 * Sauvegarde:
 * Il doit être sauvegardé dans un dossier où l'autoloader pourra aller le chercher.
 * Le dossier Application/configuration par exemple.
 *      _
 *     / \      Ce fichier ne DOIT PAS être suivi par git.
 *    / | \     
 *   /  |  \    Pour cela ajouter le dossier de configuration dans .gitignore.
 *  /___o___\
 * 
 * Configuration:
 * Sur le serveur de développement, créez à la racine du site un fichier nommé "developpement".
 * Sur linux un "touch developpement" suffira à créer le fichier a mettre à la racine du site.
 * C'est la détection de ce fichier qui permet de savoir sur quel type de serveur on se trouve.
 * 
 * Les deux configurations sont sauvegardées dans une constante appelée configuration.
 * C'est un tableau associatif 2D. De la forme configuration[type_serveur][variable]
 * 
 * Utilisation:
 * Cette classe est un singleton donc pas besoin de l'instancier.
 * On utilise directement les quatres méthodes suivantes pour récupérer les paramètres de connexion
 * host     -> connexionBDD::host()
 * dbname   -> connexionBDD::dbname()
 * user     -> connexionBDD::user()
 * pwd      -> connexionBDD::pwd()
 * 
 * Remarque: se sont des méthodes donc il ne faut pas oublier les parenthèses.
 */

class connexionBDD
{
const configuration = array(
    'developpement' => [
        'host'  => 'localhost',
        'dbname'=> 'base_de_donnee',
        'user'  => 'utilisateur',
        'pwd'   => 'mot_de_passe'],

    'production'    => [
        'host'  => 'serveur',
        'dbname'=> 'base_de_donnee',
        'user'  => 'utilisateur',
        'pwd'   => 'mot_de_passe']
);

private static $_instance = null;

private $en_developpement;

private function __construct()  { $this->en_developpement = file_exists('developpement'); }

public static function getInstance()
{
    if(is_null(self::$_instance))   self::$_instance = new connexionBDD();  
    return self::$_instance;
}

 // les méthodes
public static function host()   { return self::variable('host'); }
public static function dbname() { return self::variable('dbname'); }
public static function user()   { return self::variable('user'); }
public static function pwd()    { return self::variable('pwd'); }

private static function variable($nom)
{
    if (!in_array($nom, ['host', 'dbname', 'user', 'pwd'])) die('variable de connexion BDD inconnue');
    $objet = self::getInstance();
    return ($objet->en_developpement ?
            self::configuration['developpement'][$nom] :
            self::configuration['production'][$nom]);
}
}
