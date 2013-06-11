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
if(!empty($_GET['suivi'])){
  logue("Exportation de la liste des commandes.","export");
  
  $sql->exporte_xls("SELECT DATE_FORMAT(a.`timestamp`,'%d/%m/%Y %H:%i:%s') as date,a.`qtte_produit`,b.nom as `nom_produit`,b.vol as `vol_produit`,CONCAT(c.prenom,' ',c.nom) as `nom_client` FROM commandes as a, clients as c, produits as b WHERE a.id_user = c.id AND a.id_produit = b.id ORDER BY DATE(SUBTIME(a.timestamp,'0 6:0:0')) DESC, timestamp ASC, qtte_produit ASC;",'commandes.xls');
}
if(!empty($_GET['ventes'])){
  logue("Exportation de la liste des ventes.","export");

  $sql->exporte_xls("SELECT DATE_FORMAT(DATE(SUBTIME(a.timestamp,'0 6:0:0')),'%d/%m/%Y') as Date,b.nom as `Produit`,b.vol as `Volume`,SUM(a.`qtte_produit`) as `Quantité` FROM commandes as a, clients as c, produits as b WHERE a.id_user = c.id AND a.id_produit = b.id GROUP BY DATE(SUBTIME(a.timestamp,'0 6:0:0')),b.nom,b.vol ORDER BY timestamp ASC, qtte_produit ASC;",'ventes.xls');
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
      <li><a href="exportation.php?suivi=1">Exporter toutes les commandes passées</a></li>
      <li><a href="exportation.php?ventes=1">Exporter toutes les ventes</a></li>
     </ul>
  </div>
<?php
include 'inclus/pied.html.php';
?>