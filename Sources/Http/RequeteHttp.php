<?php
namespace PEUNC\Http;

class RequeteHttp {
private string $URL;
private string $methode;
private array $param;

public function __contruct() : void {
	$decompositionURI = parse_url($_SERVER['REQUEST_URI']);
	$this->URL = $decompositionURI['path'];
	$this->methode = $_SERVER['REQUEST_METHOD'];
	
	if ($this->methode == 'GET') $this->param = $_GET;
	elseif ($this->methode == 'POST') $this->param = $_POST;
	else $this->param = [];
}

public function getURL() : string { return $this->URL; }
public function getMethode() : string { return $this->methode; }
public function getParam() : array { return $this->param; }
}
