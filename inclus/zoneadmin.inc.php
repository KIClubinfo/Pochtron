<?php
// On considère qu'on est en zone admin !

include_once 'inclus/tete.inc.php';

if(empty($_SESSION['connexion'])
or empty($_SESSION['t_connexion'])
or $_SESSION['connexion'] != _MDP_ADMIN
or time() - $_SESSION['t_connexion'] > _DELAI_ADMIN)
{
  header('Location: connexion.php');
  exit();
}
$_SESSION['t_connexion'] = time();