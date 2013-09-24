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

UPDATE `enpcfoyer`.`bar` SET `val` = '0.70' WHERE `bar`.`id` = 'version';

DROP TABLE IF EXISTS `produits`;
CREATE TABLE IF NOT EXISTS `produits` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(200) NOT NULL,
  `vol` decimal(10,2) NOT NULL,
  `taux` decimal(3,3) unsigned NOT NULL,
  `prix` decimal(10,2) NOT NULL,
  `icone` varchar(100) NOT NULL DEFAULT 'inconnu',
  `qtt_reserve` int(11) NOT NULL DEFAULT '0',
  `qtt_alerte` int(11) NOT NULL DEFAULT '10',
  `ventes` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Liste des produits proposés à la vente' AUTO_INCREMENT=64 ;

--
-- Contenu de la table `produits`
--

INSERT INTO `produits` (`id`, `nom`, `vol`, `taux`, `prix`, `icone`, `qtt_reserve`, `qtt_alerte`, `ventes`) VALUES
(20, '1664', '0.33', '0.055', '1.00', '1664', 45, 0, 21),
(21, 'Bud', '0.33', '0.050', '1.50', 'bud', 37, 0, 9),
(22, 'Caffrey&#039;s ale', '0.44', '0.048', '2.00', 'caffrey', 10, 0, 1),
(23, 'Chouffe', '0.33', '0.080', '2.00', 'chouffe', -2, 0, 41),
(24, 'Coca-cola', '0.33', '0.000', '0.80', 'coca', 45, 0, 6),
(25, 'Corona Extra', '0.36', '0.046', '1.50', 'corona', 14, 0, 47),
(26, 'Cuv&eacute;e des Trolls', '0.25', '0.070', '1.70', 'troll', -7, 0, 45),
(27, 'D&eacute;lirium Tremens', '0.33', '0.090', '2.20', 'delirium', -15, 0, 75),
(28, 'Desperados', '0.33', '0.059', '1.50', 'desperados', -10, 0, 70),
(29, 'Grimbergen', '0.25', '0.067', '1.00', 'grimbergen', 26, 0, 35),
(30, 'Heineken', '0.25', '0.050', '0.70', 'heineken', -2, 0, 85),
(31, 'Hoegaarden', '0.25', '0.049', '1.00', 'hoegaarden', 15, 0, 59),
(32, 'Kriek', '0.25', '0.035', '1.30', 'kriek', 22, 0, 48),
(33, 'Kronenbourg', '0.25', '0.042', '0.50', 'kronenbourg', -9, 0, 9),
(34, 'Kronenbourg', '0.50', '0.042', '1.00', 'kronenbourg', -828, 0, 828),
(35, 'Kwak', '0.33', '0.084', '1.70', 'kwak', 8, 0, 52),
(36, 'Leffe blonde', '0.25', '0.066', '0.70', 'leffe', 8, 0, 72),
(37, 'Leffe triple', '0.25', '0.085', '0.70', 'leffe', 0, 0, 9),
(38, 'Murphy&#039;s stout', '0.50', '0.040', '2.20', 'murphy', 8, 0, 1),
(39, 'Pietra', '0.33', '0.055', '1.70', 'pietra', 9, 0, 11),
(40, 'Smirnoff Ice', '0.33', '0.050', '1.50', 'smirnoff_ice', 14, 0, 52),
(41, 'Smithwick&#039;s Irish Ale', '0.50', '0.045', '2.00', 'smithwick', 1, 0, 1),
(42, 'Westmalle Blonde', '0.33', '0.095', '2.00', 'westmalle', 16, 0, 34),
(43, 'Goudale', '0.33', '0.072', '1.70', 'inconnu', 3, 5, 27),
(44, 'Schweppes', '0.25', '0.000', '1.00', 'inconnu', 11, 5, 8),
(45, 'Guiness', '0.33', '0.080', '2.20', 'inconnu', 33, 5, 9),
(47, 'fut d&eacute;lirium', '0.50', '0.085', '2.00', 'inconnu', -59, 0, 59),
(48, 'Biero', '1.00', '0.050', '4.00', 'inconnu', -41, 0, 41),
(49, 'cidre', '0.33', '0.025', '1.50', 'inconnu', 13, 0, 7),
(50, 'rince cochon', '0.33', '0.085', '2.20', 'inconnu', 15, 5, 30),
(51, 'Duff', '0.33', '0.000', '2.00', 'inconnu', 17, 0, 10),
(62, 'Vin blanc', '0.38', '0.000', '2.00', 'inconnu', 13, 5, 0),
(63, 'gulden draak', '0.33', '0.000', '2.00', 'inconnu', 5, 0, 10);
