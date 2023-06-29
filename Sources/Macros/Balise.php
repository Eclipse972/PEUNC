<?php
namespace PEUNC\Macro;

class Balise
{   // pour les balises html
const DOSSIER_IMAGE	= 'images/';
const IMAGE_ABSENTE	= '/images/image_absente.png';

public static function Image($src, $alt = '<b>Image ici</b>', $code = '')
{
    if(substr($src,0,4) != 'http')	// fichier interne?
    {
        //		chemin absolu?				suppression de / au début		ajout dossier image
        $src = (substr($src,0,1) == '/') ? substr($src,1,strlen($src)) : self::DOSSIER_IMAGE . $src;
        $src = (file_exists($src)) ? '/' . $src : self::IMAGE_ABSENTE;
    }
    return '<img src=' . $src . ' alt="' . $alt . '" ' . $code . '>';
}
}