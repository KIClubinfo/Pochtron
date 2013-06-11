<?php
define('page_titre', "Historique des commandes &bull; Caisse Foyer");

include_once 'inclus/zoneadmin.inc.php';

$head_HTML = '<script type="text/javascript" src="scripts/admin.js"></script>';

include_once 'inclus/tete.html.php';

?>
  <div class="central-haut bloc-largeur journal">
   <a href="./administration.php" class="maison">Retour</a>
    <h1>Journal des consommations</h1>
    <table class="lignes events">
    <thead><tr><th>Date</th><th>Client</th><th>Consommation</th></tr></thead>
    <tbody>
<?php
$sql->rek( "SELECT a.`timestamp`,a.`qtte_produit`,b.nom as `nom_produit`,CONCAT(c.prenom,' ',c.nom) as `nom_client` FROM commandes as a, clients as c, produits as b WHERE a.id_user = c.id AND a.id_produit = b.id ORDER BY timestamp,qtte_produit DESC" );
while($a = $sql->fetch()){
  $heure = date('H:i:s d/m/y',strtotime($a['timestamp']));
  $session = date('d/m',strtotime($a['timestamp']));
  echo "<tr><td><time>$heure</time> (session : $session)</td><td>{$a['nom_client']}</td><td>{$a['qtte_produit']} {$a['nom_produit']}</td></tr>";
}

?></tbody></table>
  </div>
<?php
include 'inclus/pied.html.php';