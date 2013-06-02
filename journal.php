<?php
define('page_titre', "Journal &bull; Caisse Foyer");

include_once 'inclus/zoneadmin.inc.php';

$head_HTML = '<script type="text/javascript" src="scripts/admin.js"></script>';

include_once 'inclus/tete.html.php';

?>
  <div class="central-haut bloc-largeur journal">
   <a href="./administration.php" class="maison">Retour</a>
    <h1>Logs système</h1>
    <table class="lignes events">
    <thead><tr><th>Date</th><th>Évènement</th><th>Catégorie</th></tr></thead>
    <tbody>
<?php
$sql->rek( 'SELECT `texte`,`categorie`,`importance`,`date` FROM evenements ORDER BY date DESC' );
while($a = $sql->fetch()){
  $date = date('H:i:s d/m/y',strtotime($a['date']));
  echo "<tr class='tr-{$a['importance']}'><td><time>$date</time></td><td>{$a['texte']}</td><td>{$a['categorie']}</td></tr>";
}

?></tbody></table>
  </div>
<?php
include 'inclus/pied.html.php';