<?php	// Base de données de PEUNC
namespace PEUNC;

include"API_BDD.php";

class BDD implements iBDD
{
	private $BD;
	private static $instance;

	private function __construct()
	{
		if(!file_exists("connexionBDD.php"))	throw new Exception(600);

		require"connexionBDD.php";
		$this->BD = new \PDO("mysql:host={$host};dbname={$dbname};charset=utf8", $user , $pwd);
		if (isset($this->BD))
		{
			$this->BD->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE,	\PDO::FETCH_ASSOC);
			$this->BD->setAttribute(\PDO::ATTR_ERRMODE,				\PDO::ERRMODE_EXCEPTION);
		}
		else
			exit("Erreur fatale: connexion &agrave; la base de donn&eacute;es impossible!");
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

		if (($T_parametre != []) && (isset($T_parametre[0])))
		{
			foreach($T_parametre as $clé => $valeur)	// clé est un entier
				$requete->bindValue($clé+1, $valeur);
		}
		else
		{
			foreach($T_parametre as $clé => $valeur)	// clé est une chaine de caractères
				$requete->bindValue($clé, $valeur);
		}
		$requete->execute();
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

	public static function INSERT_INTO($requete, $valeur)
	{
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
		$Tableau = [];	// réponse de la forme Tableau[i] = code
		switch(count($Treponse, COUNT_RECURSIVE))
		{
			case 0: // réponse vide
				break;
			case 2: // une seule réponse avec 2 colonnes i et code
				$Tableau[0] = $Treponse["code"];
				break;
			default: // plusieurs réponses
				foreach($Treponse as $ligne)
				{
					$i = (int)$ligne["i"];
					$Tableau[$i] = ($i == $i_sectionne) ? str_replace('<a', '<a id=' . $nom, $ligne["code"]) : $ligne["code"];
				}
		}
		return $Tableau;
	}
}
