<?php // Exception pour les erreurs de l'application
namespace PEUNC;

class Exception extends \Exception
{
	public function __construct($message)
	{
		parent::__construct($message);
	}
}
