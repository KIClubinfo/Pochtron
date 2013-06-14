<?php

// Logiciel
define('_VERSION',0.4);
define('_TITRE_PAR_DEFAUT','Caisse foyer '._VERSION); // Titre par défaut pour les pages du site

// Accès administration
define('_MDP_ADMIN','011d8466a10d2b8f16ff0bc2cf36a1e29cd2cc69');
define('_DELAI_ADMIN',10*60); // Temps max d'inactivité avant déconnexion, en secondes

// Afficher les erreurs MySQL ?
define('_AFF_ERR_SQL',true);

 // Nombre d'heure sans commande avant le début d'une nouvelle session
define('_NBH_SESSION', 8);

// Connexion au serveur MySQL
if($_SERVER['REMOTE_ADDR']=='127.0.0.1'){ // Serveur local
  define('_ENLOCAL', true);
  define('_oOoMySQL','localhost');
  define('_oOoMySQLBDD','');
  define('_oOoMySQLUSER','');
  define('_oOoMySQLPASS','');
  
  define('_BASEHREF','/dev-foyer/');
}
else{
  define('_ENLOCAL', false);
  define('_oOoMySQL','');
  define('_oOoMySQLBDD','');
  define('_oOoMySQLUSER','');
  define('_oOoMySQLPASS','');
  define('_BASEHREF','/');
}