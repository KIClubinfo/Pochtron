ALTER TABLE `bar` CHANGE `id` `id` ENUM( 'caisse', 'version', 'PIN1', 'PIN2' ) NOT NULL;

INSERT INTO `bar` (`val` ,`id`) VALUES ('120393', 'PIN1'), ('011091', 'PIN2');