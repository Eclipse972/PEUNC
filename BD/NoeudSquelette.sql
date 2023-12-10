-- Adminer 4.8.1 MySQL 8.0.35-0ubuntu0.22.04.1 dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

DROP TABLE IF EXISTS `NoeudSquelette`;
CREATE TABLE `NoeudSquelette` (
  `ID` int NOT NULL AUTO_INCREMENT,
  `site` varchar(16) CHARACTER SET latin1 COLLATE latin1_general_cs NOT NULL,
  `alpha` int NOT NULL,
  `beta` int NOT NULL,
  `gamma` int NOT NULL,
  `titre` varchar(99) CHARACTER SET latin1 COLLATE latin1_general_cs NOT NULL DEFAULT 'sans titre',
  `ptinom` varchar(99) CHARACTER SET latin1 COLLATE latin1_general_cs NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `alpha_beta_gamma_site` (`alpha`,`beta`,`gamma`,`site`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `NoeudSquelette` (`ID`, `site`, `alpha`, `beta`, `gamma`, `titre`, `ptinom`) VALUES
(1,	'API',	0,	0,	0,	'présentation de l\'API',	'home'),
(2,	'site',	0,	0,	0,	'Connexion',	'home'),
(3,	'API',	1,	0,	0,	'connexion API',	'connexion'),
(4,	'site',	1,	0,	0,	'Inscription',	'inscription'),
(5,	'API',	1,	1,	0,	'jeton API',	'jetonAPI'),
(6,	'API',	2,	0,	0,	'aide compétence',	'referentiel'),
(7,	'site',	2,	0,	0,	'Activation de compte',	'activation'),
(8,	'API',	2,	1,	0,	'lire compétence',	'GET'),
(9,	'API',	3,	0,	0,	'aide groupe',	'groupe'),
(10,	'site',	3,	0,	0,	'Mot de passe oubli&eacute;',	'oubliMDP'),
(11,	'API',	3,	1,	0,	'lire groupe',	'GET'),
(12,	'API',	4,	0,	0,	'Aide activités',	'activite'),
(13,	'site',	4,	0,	0,	'Formulaire de contact',	'contact'),
(14,	'API',	4,	1,	0,	'lire activité',	'GET'),
(15,	'API',	4,	2,	0,	'créer activité',	'POST'),
(16,	'API',	4,	3,	0,	'modifier activité',	'PUT'),
(17,	'API',	4,	4,	0,	'effacer activité',	'DELETE'),
(18,	'API',	5,	0,	0,	'aide évaluation',	'evaluation'),
(19,	'site',	5,	0,	0,	'D&eacute;connexin',	'deconnexion'),
(20,	'API',	5,	1,	0,	'lire évaluation',	'GET'),
(21,	'API',	5,	2,	0,	'modifier évaluation',	'PUT'),
(22,	'API',	5,	3,	0,	'créer évaluation',	'POST'),
(23,	'API',	5,	4,	0,	'supprimer évaluation',	'DELETE'),
(24,	'API',	6,	0,	0,	'aide notes',	'note'),
(25,	'site',	6,	0,	0,	'Profil',	'profil'),
(26,	'API',	6,	1,	0,	'lire note',	'GET'),
(27,	'API',	6,	2,	0,	'créer note',	'POST'),
(28,	'API',	6,	3,	0,	'modifier note',	'PUT'),
(29,	'API',	6,	4,	0,	'supprimer note',	'DELETE'),
(30,	'site',	10,	0,	0,	'Accueil',	'accueil'),
(31,	'site',	11,	0,	0,	'Grille d&apos;&eacute;valuation',	'grille'),
(32,	'site',	11,	1,	0,	'détail d\'une grille',	'detail'),
(33,	'site',	12,	0,	0,	'Formateur',	'formateur'),
(34,	'site',	13,	0,	0,	'Groupes',	'groupe'),
(35,	'site',	14,	0,	0,	'Bilans',	'bilan'),
(36,	'site',	14,	1,	0,	'Candidat',	'candidat'),
(37,	'site',	14,	2,	0,	'Comp&eacute;tence',	'competence'),
(38,	'site',	14,	3,	0,	'Grille',	'grille'),
(39,	'site',	15,	0,	0,	'R&eacute;f&eacute;rentiel',	'referentiel');

-- 2023-12-10 05:10:09
