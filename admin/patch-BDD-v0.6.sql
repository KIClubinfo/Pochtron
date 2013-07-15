-- phpMyAdmin SQL Dump
-- version OVH
-- http://www.phpmyadmin.net
--
-- Client: mysql51-50.pro
-- Généré le : Lun 15 Juillet 2013 à 20:19
-- Version du serveur: 5.1.66
-- Version de PHP: 5.3.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Base de données: `enpcfoyer`
--

-- --------------------------------------------------------

--
-- Structure de la table `commandes_externes`
--

CREATE TABLE IF NOT EXISTS `commandes_externes` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name_user` varchar(50) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `id_produit` smallint(6) NOT NULL,
  `qtte_produit` smallint(6) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
