<?php


if(!file_exists('inclus/config.inc.php')){
  include 'installation.php';
  exit();
}

// Fichiers d'en-tête

include_once 'inclus/config.inc.php';
include_once 'inclus/fonctions.inc.php';

include_once 'inclus/bdd.classe.php';
$sql = new rekSQL;

if(!_ENLOCAL)
  $sql->rek( 'SET NAMES UTF8' );

if(!isset($_SESSION))
  session_start();