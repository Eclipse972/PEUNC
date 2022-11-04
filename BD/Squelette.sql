--
-- Structure de la table `Squelette` l'ossature de PEUNC
--

CREATE TABLE IF NOT EXISTS `Squelette` (
  `ID` int(11) NOT NULL auto_increment,
  `alpha` int(11) NOT NULL,
  `beta` int(11) NOT NULL default '0',
  `gamma` int(11) NOT NULL default '0',
  `texteMenu` varchar(99) collate latin1_general_ci NOT NULL,
  `imageMenu` varchar(99) collate latin1_general_ci NOT NULL default 'Vue/images/nom_du_fichier.png' COMMENT 'associée à la page',
  `ptiNom` varchar(99) collate latin1_general_ci NOT NULL,
  `classePage` varchar(99) collate latin1_general_ci NOT NULL default 'Page',
  `controleur` varchar(99) collate latin1_general_ci NOT NULL,
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `navigation` (`alpha`,`beta`,`gamma`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=6 ;

--
-- Contenu de la table `Squelette`
--

INSERT INTO `Squelette` (`ID`, `alpha`, `beta`, `gamma`, `texteMenu`, `imageMenu`, `ptiNom`, `classePage`, `controleur`) VALUES
(1, -1, 500, 0, 'Serveur satur&eacute;', '', 'Serveur_sature', 'PageErreur', 'erreur_serveur.php'),
(2, -1, 404, 0, 'Cette page n&apos;existe pas', '', 'Page_inexistante', 'PageErreur', 'erreur_serveur.php'),
(3, -1, 403, 0, 'Acc&egrave;s interdit', '', 'Acces_interdit', 'PageErreur', 'erreur_serveur.php'),
(4, -1, 0,	 0, 'Erreur inconnue', '', 'Erreur', 'PageErreur', 'erreur_serveur.php'),
(5,  0, 0,	 0, 'Page d&apos;accueil', '', 'home', 'home', 'home.php');
