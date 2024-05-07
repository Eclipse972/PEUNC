<?php
namespace PEUNC\Http;
use PEUNC\Erreur\Exception;
use PEUNC\Autre\JetonCSRF;

class RequeteHttp {
private string $URL;
private string $methode;
private array $param;

public function __construct(string $URI, string $méthode) {
	$decompositionURI = parse_url($URI);
	$this->URL = $decompositionURI['path'];
	$this->methode = $méthode;
	
	switch ($this->methode) {
		case 'GET':
			$this->param = $_GET;
			break;
		case 'POST':
			if (!JetonCSRF::Verifier())	throw new Exception(101);
			# par défaut tous les formulaire sont traité par index.php
			$jeton = JetonCSRF::Dechiffre();
			$this->URL = $jeton['URL']; # le jeton contient entre autre l'URL de la page qui a créé le jeton
			$this->param = $_POST;
			break;
		default: # méthode inconnue
			throw new Exception(100, $this->methode);
			break;
	}
}

public function getURL() : string { return $this->URL; }
public function getMethode() : string { return $this->methode; }
public function getParam() : array { return $this->param; }
}
