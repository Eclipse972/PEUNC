-- phpMyAdmin SQL Dump
-- version 3.1.5
-- http://www.phpmyadmin.net
--
-- Généré le : Mer 28 Décembre 2022 à 00:51
-- Version du serveur: 5.0.83
-- Version de PHP: 5.3.9

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

-- --------------------------------------------------------

--
-- Structure de la table `Squelette`
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
  `methode` varchar(99) collate latin1_general_ci NOT NULL default 'GET',
  `paramAutorise` varchar(99) collate latin1_general_ci NOT NULL default '[]',
  `dureeCache` int(11) NOT NULL default '0' COMMENT 'en heure',
  PRIMARY KEY  (`ID`),
  UNIQUE KEY `navigation` (`alpha`,`beta`,`gamma`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=1 ;


