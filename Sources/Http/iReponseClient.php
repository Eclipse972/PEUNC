<?php
namespace PEUNC\Http;

use PEUNC\Controleur\BaseControleur;

/**
 * La réponse agrège toutes les "morceaux" construits par le controleur
 * C'est elle qui permet l'affichage de ces morceaux dans la vue.
 * un morceaux peut être
 * - une simple valiable de n'importe quel type (valeur, objet, tableau ...)
 * - une méthode qui fait un travail dont le résultat sera ajouté dans la vue
 * 
 * Utilisation dans chaque vue
 * 	1- résupérer un élément: <?=ReponseClient::Element('nom')?>
 *	2- pour récupérer le résultat d'une méthode: <?=ReponseClient::Méthode()?>
 *
 * A l'avenir il se peut que crée des méthodes avec des paramètres.
 * A mon avis ce n'est pas une bonne idée car c'est au controleur de faire tout le travail.
 * La vue ne doit faire que de l'affichage avec éventuellement de la mise en forme.
 * 
 * Une contrainte
 * Toutes les vues doivent contenir l'instruction "use PEUNC\Http\ReponseClient;".
 * Et si la vue inclus un morceau de vue celui-ci doit aussi contenir la même instruction.
 **/

interface iReponseClient
{
public static function Absorbe(BaseControleur $controleur);

public static function Element($nom);	// 
/**
 * Affiche l'élément nommé (il peut être un tableau).
 * Provoque une erreur si l'élément n'a pas été créé par le controleur (pour imposer un débogage)
 * Renvoie la liste des éléments si aucun paramètre
 */
public static function Existe($nom);	# vérifie l'existence de l'élément nommé

/**
 * Méthodes
 **/
public static function Menu();	# Affiche la balise nav et son contenu créé par le controleur
public static function View();	# renvoie le nom complet de la vue
public static function CSS();	# liste des liens CSS

/**
 * Affiche un message en rouge
 * paramètre: nomMessage = indice sous lequel le message est sauvegardé dans $_SESSION
 * Le message est ensuite supprimé de la session
 * 
 * retour: chaine de caractère contenant le code html du message
 */
public static function Message(string $nomMessage) : string;
}
