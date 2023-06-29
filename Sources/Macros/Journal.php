<?php
namespace PEUNC\Macro;

class Journal
{
const $table = 'JournalRequete';

public static function Inscrire($auteur, $titre, $contenu)
{
    BDD::INSERT_INTO(self::table . ' (auteur, titre, contenu) VALUE (?,?,?)', [$auteur, $titre, $contenu]);
}
}