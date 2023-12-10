-- Adminer 4.8.1 MySQL 8.0.35-0ubuntu0.22.04.1 dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

DROP TABLE IF EXISTS `MethodeSquelette`;
CREATE TABLE `MethodeSquelette` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `methodeHttp` varchar(9) CHARACTER SET latin1 COLLATE latin1_general_cs NOT NULL,
  `controleur` varchar(99) CHARACTER SET latin1 COLLATE latin1_general_cs NOT NULL COMMENT 'avec namespace',
  `methodeControleur` varchar(99) CHARACTER SET latin1 COLLATE latin1_general_cs NOT NULL,
  `paramAutorise` varchar(255) CHARACTER SET latin1 COLLATE latin1_general_cs NOT NULL DEFAULT '[]' COMMENT 'tableau JSON',
  `noeudID` int NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `noeudID_methodeHttp` (`noeudID`,`methodeHttp`(1)),
  CONSTRAINT `MethodeSquelette_ibfk_2` FOREIGN KEY (`noeudID`) REFERENCES `NoeudSquelette` (`ID`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `MethodeSquelette` (`ID`, `methodeHttp`, `controleur`, `methodeControleur`, `paramAutorise`, `noeudID`) VALUES
(1,	'GET',	'VolEval\\Controleur\\APIControleur',	'Documenter',	'[]',	1),
(2,	'GET',	'VolEval\\Controleur\\APIControleur',	'Connecter',	'[\"courriel\",\"hashMDP\"]',	3),
(3,	'GET',	'VolEval\\Controleur\\APIControleur',	'jetonAPI',	'[\"profID\"]',	5),
(4,	'GET',	'VolEval\\Controleur\\APIControleur',	'Documenter',	'[]',	6),
(5,	'GET',	'VolEval\\Controleur\\APIControleur',	'Repondre',	'[\"id\", \"corbeille\", \"date\", \"token\"]',	8),
(6,	'GET',	'VolEval\\Controleur\\APIControleur',	'Documenter',	'[]',	9),
(7,	'GET',	'VolEval\\Controleur\\APIControleur',	'Repondre',	'[\"id\", \"corbeille\", \"date\", \"token\"]',	11),
(8,	'GET',	'VolEval\\Controleur\\APIControleur',	'Documenter',	'[]',	12),
(9,	'GET',	'VolEval\\Controleur\\APIControleur',	'Repondre',	'[\"id\", \"corbeille\", \"date\", \"token\"]',	14),
(10,	'GET',	'VolEval\\Controleur\\APIControleur',	'Repondre',	'[\"id\", \"nom\", \"date\", \"competence1ID\", \"competence2ID\", \"competence3ID\", \"token\", \"competence4ID\", \"competence5ID\", \"competence6ID\", \"competence7ID\", \"competence8ID\", \"competence9ID\"]',	15),
(11,	'GET',	'VolEval\\Controleur\\APIControleur',	'Repondre',	'[\"id\", \"nom\", \"date\", \"competence1ID\", \"competence2ID\", \"competence3ID\", \"token\", \"competence4ID\", \"competence5ID\", \"competence6ID\", \"competence7ID\", \"competence8ID\", \"competence9ID\"]',	16),
(12,	'GET',	'VolEval\\Controleur\\APIControleur',	'Repondre',	'[\"id\", \"token\"]',	17),
(13,	'GET',	'VolEval\\Controleur\\APIControleur',	'Documenter',	'[]',	18),
(14,	'GET',	'VolEval\\Controleur\\APIControleur',	'Repondre',	'[\"id\", \"corbeille\", \"date\", \"token\"]',	20),
(15,	'GET',	'VolEval\\Controleur\\APIControleur',	'Repondre',	'[\"id\",  \"MAJ\", \"activite\", \"date\", \"token\"]',	21),
(16,	'GET',	'VolEval\\Controleur\\APIControleur',	'Repondre',	'[\"id\", \"nom\", \"date\", \"activiteID\", \"groupeID\", \"token\"]',	22),
(17,	'GET',	'VolEval\\Controleur\\APIControleur',	'Repondre',	'[\"id\", \"token\"]',	23),
(18,	'GET',	'VolEval\\Controleur\\APIControleur',	'Documenter',	'[]',	24),
(19,	'GET',	'VolEval\\Controleur\\APIControleur',	'Repondre',	'[\"id\", \"corbeille\", \"date\", \"token\"]',	26),
(20,	'GET',	'VolEval\\Controleur\\APIControleur',	'Repondre',	'[\"id\",\"competenceID\", \"MAJ\", \"note1\", \"note2\", \"note3\", \"note4\", \"note5\", \"note6\", \"note7\", \"note8\", \"note9\", \"token\"]',	27),
(21,	'GET',	'VolEval\\Controleur\\APIControleur',	'Repondre',	'[\"id\",\"competenceID\", \"MAJ\", \"note1\", \"note2\", \"note3\", \"note4\", \"note5\", \"note6\", \"note7\", \"note8\", \"note9\", \"token\"]',	28),
(22,	'GET',	'VolEval\\Controleur\\APIControleur',	'Repondre',	'[\"id\", \"token\"]',	29),
(23,	'GET',	'VolEval\\Controleur\\CompteControleur',	'Connecter',	'[]',	2),
(24,	'POST',	'VolEval\\Controleur\\CompteControleur',	'Connecter',	'[\"login\", \"MDP\", \"CSRF\"]',	2),
(25,	'GET',	'VolEval\\Controleur\\CompteControleur',	'Creer',	'[]',	4),
(26,	'POST',	'VolEval\\Controleur\\CompteControleur',	'Creer',	'[]',	4),
(27,	'POST',	'VolEval\\Controleur\\CompteControleur',	'Activer',	'[\"code\"]',	7),
(28,	'GET',	'VolEval\\Controleur\\CompteControleur',	'Activer',	'[]',	7),
(29,	'POST',	'VolEval\\Controleur\\CompteControleur',	'Recuperer',	'[]',	10),
(30,	'GET',	'VolEval\\Controleur\\CompteControleur',	'Recuperer',	'[]',	10),
(31,	'POST',	'VolEval\\Controleur\\ContactControleur',	'Traiter',	'[\"pseudo\", \"courriel\", \"message\", \"validation\", \"CSRF\"]',	13),
(32,	'GET',	'VolEval\\Controleur\\ContactControleur',	'Afficher',	'[]',	13),
(33,	'GET',	'VolEval\\Controleur\\CompteControleur',	'Deconnecter',	'[]',	19),
(34,	'POST',	'VolEval\\Controleur\\CompteControleur',	'Profil',	'[\"CSFR\", \"nom\", \"prenom\", \"courriel\", \"MDP\"]',	25),
(35,	'GET',	'VolEval\\Controleur\\CompteControleur',	'Profil',	'[]',	25),
(36,	'GET',	'VolEval\\Controleur\\CompteControleur',	'Accueil',	'[]',	30),
(37,	'GET',	'VolEval\\Controleur\\GrilleControleur',	'AfficherListeGrille',	'[\"id\"]',	31),
(38,	'GET',	'VolEval\\Controleur\\GrilleControleur',	'AfficherUneGrille',	'[\"id\"]',	32),
(39,	'GET',	'VolEval\\Controleur\\FormateurControleur',	'Afficher',	'[\"id\"]',	33),
(40,	'GET',	'VolEval\\Controleur\\GroupeControleur',	'Afficher',	'[\"id\"]',	34),
(41,	'GET',	'VolEval\\Controleur\\SyntheseControleur',	'Presenter',	'[]',	35),
(42,	'GET',	'VolEval\\Controleur\\SyntheseControleur',	'Candidat',	'[]',	36),
(43,	'GET',	'VolEval\\Controleur\\SyntheseControleur',	'Competence',	'[]',	37),
(44,	'GET',	'VolEval\\Controleur\\SyntheseControleur',	'Grille',	'[\"id\"]',	38),
(45,	'GET',	'VolEval\\Controleur\\ReferentielControleur',	'Afficher',	'[\"id\"]',	39);

-- 2023-12-10 05:03:36
