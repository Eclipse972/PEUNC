<?php	// Base de données de PEUNC
namespace PEUNC;

include"API_BDD.php";

class BDD implements iBDD
{
	private $BD;
	private static $instance;

	private function __construct()
	{
		require"connexion.php";
		$this->BD = new \PDO("mysql:host={$host};dbname={$dbname};charset=utf8", $user , $pwd);
		if (isset($this->BD))
		{
			$this->BD->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE,	\PDO::FETCH_ASSOC);
			$this->BD->setAttribute(\PDO::ATTR_ERRMODE,				\PDO::ERRMODE_EXCEPTION);
		}
		else
			exit("Erreur fatale: connexion &agrave; la base de données impossible!");
	}

	private static function getInstance()
	{
		if(empty(self::$instance))
		{
			self::$instance = new BDD();
		}
		return self::$instance->BD;
	}

//	Implémentation de l'interface

	public static function SELECT($requete, array $T_parametre = [])
	{
		$pdo = self::getInstance();
		$requete = $pdo->prepare("SELECT " . $requete);
		$requete->execute($T_parametre);
		$reponse = $requete->fetchAll();
		$requete->closeCursor();
		switch(count($reponse))
		{
			case 0:	// aucun résultat
				$résultat = null;
				break;
			case 1:	// une seule réponse				une seule colonne			plusieurs colonnes
				$résultat = (count($reponse[0]) == 1) ? array_shift($reponse[0]) : $reponse[0];
				break;
			default: // plusieurs réponses
				$résultat = $reponse;
		}
		return $résultat;
	}
	
	public static function Liste_niveau($i_sectionne, $alpha = null, $beta = null)
	{
		if(!isset($alpha))
		{	// pour les onglets
			$eqAlpha= ">=0";
			$eqBeta	= "=0";
			$eqGamma= "=0";
			$indice	= "alpha";
			$param	= [];
			$nom	= "alpha_actif";
		}
		elseif(!isset($beta))
		{	// pour le menu
			$eqAlpha= "=?";
			$eqBeta = ">0";
			$eqGamma= "=0";
			$indice = "beta";
			$param	= [$alpha];
			$nom	= "beta_actif";
		}
		else
		{	// pour le sous-menu
			$eqAlpha= "=?";
			$eqBeta = "=?";
			$eqGamma= ">0";
			$indice = "gamma";
			$param	= [$alpha, $beta];
			$nom	= "gamma_actif";
		}
		$Treponse = self::SELECT("{$indice} AS i, code FROM Vue_code_item
							WHERE alpha{$eqAlpha} AND beta{$eqBeta} AND gamma{$eqGamma}
							ORDER BY i",
							$param
						);
		if(!is_array($Treponse)) throw new Exception("Liste niveau: la réponse n&apos;est pas un tableau");

		$Tableau = [];
		if (isset($Treponse))
		{
			foreach($Treponse as $ligne)
			{
				$i = (int)$ligne["i"];
				$Tableau[$i] = ($i == $i_sectionne) ? str_replace('<a', '<a id=' . $nom, $ligne["code"]) : $ligne["code"];
			}
		}
		return $Tableau; // de la forme Tableau[i] = code
	}
}
