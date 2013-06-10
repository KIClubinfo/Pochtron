-- phpMyAdmin SQL Dump
-- version OVH
-- http://www.phpmyadmin.net
--
-- Client: mysql51-50.pro
-- Généré le : Lun 03 Juin 2013 à 20:03
-- Version du serveur: 5.1.66
-- Version de PHP: 5.3.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Base de données: `enpcfoyer`
--

-- --------------------------------------------------------

--
-- Structure de la table `bar`
--

CREATE TABLE IF NOT EXISTS `bar` (
  `id` enum('caisse') NOT NULL,
  `val` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Contenu de la table `bar`
--

INSERT INTO `bar` (`id`, `val`) VALUES
('caisse', '0.00');

-- --------------------------------------------------------

--
-- Structure de la table `clients`
--

CREATE TABLE IF NOT EXISTS `clients` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `prenom` varchar(200) COLLATE utf8_bin NOT NULL,
  `nom` varchar(200) COLLATE utf8_bin NOT NULL,
  `solde` decimal(10,2) NOT NULL,
  `active` enum('inactif','activé','bloqué') COLLATE utf8_bin NOT NULL DEFAULT 'activé',
  `nb_consos` int(11) NOT NULL DEFAULT '0',
  `promo` varchar(5) COLLATE utf8_bin NOT NULL,
  `distinction` enum('pookie','petit joueur','habitue','bide-a-biere','pochtron','bitumeux','roidufoyer','legende') COLLATE utf8_bin NOT NULL DEFAULT 'pookie',
  `litres_bus` decimal(6,2) NOT NULL,
  `commentaire` text COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Liste de tous les clients du foyer';

-- --------------------------------------------------------

--
-- Structure de la table `commandes`
--

CREATE TABLE IF NOT EXISTS `commandes` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `id_user` smallint(5) unsigned NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `id_produit` smallint(5) NOT NULL,
  `qtte_produit` int(11) NOT NULL,
  PRIMARY KEY (`id`)
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `evenements`
--

CREATE TABLE IF NOT EXISTS `evenements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `texte` text COLLATE utf8_bin NOT NULL,
  `categorie` varchar(100) COLLATE utf8_bin NOT NULL DEFAULT 'sans',
  `importance` set('info','warn','critique') COLLATE utf8_bin NOT NULL DEFAULT 'info',
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Évènements du logiciel';

--
-- Contenu de la table `evenements`
--

INSERT INTO `evenements` (`id`, `texte`, `categorie`, `importance`) VALUES
(1, 'Installation de la base de donn&eacute; r&eacute;ussie !', 'installation', 'info');

-- --------------------------------------------------------

--
-- Structure de la table `produits`
--

CREATE TABLE IF NOT EXISTS `produits` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(200) NOT NULL,
  `vol` decimal(10,2) NOT NULL,
  `prix` decimal(10,2) NOT NULL,
  `icone` varchar(100) NOT NULL DEFAULT 'inconnu',
  `qtt_reserve` int(11) NOT NULL DEFAULT '0',
  `qtt_alerte` int(11) NOT NULL DEFAULT '10',
  `ventes` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Liste des produits proposés à la vente';

--
-- Contenu de la table `produits`
--

INSERT INTO `produits` (`id`, `nom`, `vol`, `prix`, `icone`, `qtt_reserve`, `qtt_alerte`, `ventes`) VALUES
(20, '1664', '0.33', '1.50', '1664', 80, 20, 0),
(21, 'Bud', '0.33', '1.50', 'bud', 80, 20, 0),
(22, 'Caffrey&#039;s ale', '0.44', '2.00', 'caffrey', 80, 20, 0),
(23, 'Chouffe', '0.33', '2.00', 'chouffe', 80, 20, 0),
(24, 'Coca-cola', '0.33', '0.50', 'coca', 80, 20, 0),
(25, 'Corona Extra', '0.36', '1.50', 'corona', 80, 20, 0),
(26, 'Cuvée des Trolls', '0.25', '1.50', 'troll', 80, 20, 0),
(27, 'Délirium Tremens', '0.33', '2.00', 'delirium', 80, 20, 0),
(28, 'Desperados', '0.33', '1.50', 'desperados', 80, 20, 0),
(29, 'Grimbergen', '0.25', '1.00', 'grimbergen', 80, 20, 0),
(30, 'Heineken', '0.25', '0.70', 'heineken', 80, 20, 0),
(31, 'Hoegaarden', '0.25', '1.00', 'hoegaarden', 80, 20, 0),
(32, 'Kriek', '0.25', '1.30', 'kriek', 80, 20, 0),
(33, 'Kronenbourg', '0.25', '0.50', 'kronenbourg', 80, 20, 0),
(34, 'Kronenbourg', '0.50', '1.00', 'kronenbourg', 80, 20, 0),
(35, 'Kwak', '0.33', '1.70', 'kwak', 80, 20, 0),
(36, 'Leffe blonde', '0.25', '0.70', 'leffe', 80, 20, 0),
(37, 'Leffe triple', '0.25', '0.70', 'leffe', 80, 20, 0),
(38, 'Murphy&#039;s stout', '0.50', '2.20', 'murphy', 80, 20, 0),
(39, 'Pietra', '0.33', '1.70', 'pietra', 80, 20, 0),
(40, 'Smirnoff Ice', '0.33', '1.50', 'smirnoff_ice', 80, 20, 0),
(41, 'Smithwick&#039;s Irish Ale', '0.50', '2.00', 'smithwick', 80, 20, 0),
(42, 'Westmalle Blonde', '0.33', '2.00', 'westmalle', 80, 20, 0);