<?php	// Base de données de PEUNC\Autre
use PEUNC\Erreur\Exception;

namespace PEUNC\Autre;

include"API_BDD.php";

class BDD implements iBDD
{
	private $BD;
	private static $instance;

	private function __construct()
	{
		$fichier = 'Config/connexionBDD.php';
		if(!file_exists($fichier))	throw new Exception(600);

		require $fichier;
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

	public static function PDO_param($var)
	{
		switch(gettype($var))
		{
			case "integer":
				$valeur = \PDO::PARAM_INT;
				break;
			case "string":
				$valeur = \PDO::PARAM_STR;
				break;
			case "NULL":
				$valeur = \PDO::PARAM_NULL;
				break;
			default: throw new Exception(601);
		}
		return $valeur;
	}

//	Implémentation de l'interface

	public static function SELECT($requete, array $T_parametre = [], $B_postTraitement = false)
	{
		$pdo = self::getInstance();
		$requete = $pdo->prepare("SELECT " . $requete);

		if (($T_parametre != []) && (isset($T_parametre[0])))
		{
			foreach($T_parametre as $clé => $valeur)	// clé est un entier
				$requete->bindValue($clé+1, $valeur, BDD::PDO_param($valeur));
		}
		else
		{
			foreach($T_parametre as $clé => $valeur)	// clé est une chaine de caractères
				$requete->bindValue($clé, $valeur, BDD::PDO_param($valeur));
		}
		$requete->execute();
		$reponse = $requete->fetchAll();
		$requete->closeCursor();

		if ($B_postTraitement)
		{
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
		}
		else		$résultat = $reponse;
		return $résultat;
	}

	public static function INSERT_INTO($requete, array $T_valeurs)
	{
		if($T_valeurs == [])	throw new Exception(603);

		$pdo = self::getInstance();
		$requete = $pdo->prepare("INSERT INTO " . $requete);
		$Tableau = (count($T_valeurs) == count($T_valeurs, COUNT_RECURSIVE)) ? [$T_valeurs] : $T_valeurs;
		foreach($Tableau as $T_valeurs)
		{
			if (($T_valeurs != []) && (isset($T_valeurs[0])))
			{
				foreach($T_valeurs as $clé => $valeur)	// clé est un entier
					$requete->bindValue($clé+1, $valeur, BDD::PDO_param($valeur));
			}
			else
			{
				foreach($T_valeurs as $clé => $valeur)	// clé est une chaine de caractères
					$requete->bindValue($clé, $valeur, BDD::PDO_param($valeur));
			}
			$requete->execute();
		}
		$requete->closeCursor();
	}

	public static function DELETE_FROM($requete, array $T_parametre)
	{
		if($T_parametre == [])	throw new Exception(602);

		$pdo = self::getInstance();
		$requete = $pdo->prepare("DELETE FROM " . $requete);
		if (isset($T_parametre[0]))
		{
			foreach($T_parametre as $clé => $valeur)	// clé est un entier
				$requete->bindValue($clé+1, $valeur, BDD::PDO_param($valeur));
		}
		else
		{
			foreach($T_parametre as $clé => $valeur)	// clé est une chaine de caractères
				$requete->bindValue($clé, $valeur, BDD::PDO_param($valeur));
		}
		$requete->execute();
		$requete->closeCursor();
	}

	public static function UPDATE($requete, array $T_parametre)
	{
		if($T_parametre == [])	throw new Exception(604);

		$pdo = self::getInstance();
		$requete = $pdo->prepare('UPDATE ' . $requete);
		if (isset($T_parametre[0]))
		{
			foreach($T_parametre as $clé => $valeur)	// clé est un entier
				$requete->bindValue($clé+1, $valeur, BDD::PDO_param($valeur));
		}
		else
		{
			foreach($T_parametre as $clé => $valeur)	// clé est une chaine de caractères
				$requete->bindValue($clé, $valeur, BDD::PDO_param($valeur));
		}
		$requete->execute();
		$requete->closeCursor();

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
		foreach($Treponse as $ligne)
		{
			$i = (int)$ligne["i"];
			$Tableau[$i] = ($i == $i_sectionne) ? str_replace('<a', '<a id=' . $nom, $ligne["code"]) : $ligne["code"];
		}
		return $Tableau;
	}
}
