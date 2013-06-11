ALTER TABLE clients
  ADD `promo` varchar(5) COLLATE utf8_bin NOT NULL,
  ADD `distinction` enum('pookie','petit joueur','habitue','bide-a-biere','pochtron','bitumeux','roidufoyer','legende') COLLATE utf8_bin NOT NULL DEFAULT 'pookie',
  ADD `litres_bus` decimal(6,2) NOT NULL,
  ADD `commentaire` text COLLATE utf8_bin NOT NULL;

ALTER TABLE commandes ADD  `id_produit` smallint(5) NOT NULL, ADD  `qtte_produit` int(11) NOT NULL, DROP commande;

ALTER TABLE `bar` CHANGE `id` `id` ENUM( 'caisse', 'version' ) NOT NULL;

INSERT INTO `bar` (`id`, `val`) VALUES
('version', '0.3');