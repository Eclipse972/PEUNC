<?php
namespace PEUNC\Http;
use PEUNC\Erreur\Exception;
use PEUNC\Autre\JetonCSRF;

class RequeteHttp {
private string $URL;
private string $methode;
private array $param;

public function __construct() {
	$decompositionURI = parse_url($_SERVER['REQUEST_URI']);
	$this->URL = $decompositionURI['path'];
	$this->methode = $_SERVER['REQUEST_METHOD'];
	
	switch ($this->methode) {
		case 'GET':
			$this->param = $_GET;
			break;
		case 'POST':
			if (!JetonCSRF::Verifier())	throw new Exception(901);
			# par défaut tous les formulaire sont traité par index.php
			$jeton = JetonCSRF::Dechiffre();
			$this->URL = $jeton['URL']; # le jeton contient entre autre l'URL de la page qui a créé le jeton
			$this->param = $_POST;
			break;
		default: # méthode inconnue
			throw new Exception(900, $this->methode);
			break;
	}
}

public function getURL() : string { return $this->URL; }
public function getMethode() : string { return $this->methode; }
public function getParam() : array { return $this->param; }
}
