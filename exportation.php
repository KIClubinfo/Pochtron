<?php

include_once 'inclus/tete.inc.php';

if(!empty($_GET['stocks'])){
  logue("Exportation de la liste des produits.","export");
  
  $sql->exporte_xls('SELECT * FROM produits','stocks.xls');
}
if(!empty($_GET['clients'])){
  logue("Exportation de la liste des clients.","export");
  
  $sql->exporte_xls('SELECT * FROM clients','clients.xls');
}
if(!empty($_GET['events'])){
  logue("Exportation de la liste des évènements.","export");
  
  $sql->exporte_xls('SELECT * FROM evenements','evenements.xls');
}

define('page_titre', "Exporter &bull; Caisse Foyer");

include_once 'inclus/tete.html.php';
?>
  <div class="central bloc bienvenue">
   <a href="./index.php" class="maison">Retour</a>
    <h1>Exportation des données</h1>
    <ul class="vertical">
      <li><a href="exportation.php?stocks=1" target="_blank">Exporter les stocks</a></li>
      <li><a href="exportation.php?clients=1">Exporter les clients</a></li>
      <li><a href="exportation.php?events=1">Exporter les évènements</a></li>
     </ul>
  </div>
<?php
include 'inclus/pied.html.php';
?>