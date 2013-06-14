
CREATE TABLE IF NOT EXISTS `futs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `nb` int(11) NOT NULL DEFAULT '0',
  `respo` varchar(200) NOT NULL DEFAULT 'Non défini...',
  `type` varchar(200) NOT NULL DEFAULT 'Kro',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Historique des fûts sortis de la torche.' AUTO_INCREMENT=3 ;

UPDATE `bar` SET `val` = '0.4' WHERE `id` = 'version';