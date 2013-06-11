<?php
define('page_titre', "Historique des commandes &bull; Caisse Foyer");
define('_NBH_SESSION', 6); // Nombre d'heure sans commande avant le début d'une nouvelle session

include_once 'inclus/zoneadmin.inc.php';

$head_HTML = '<script type="text/javascript" src="scripts/admin.js"></script>';

include_once 'inclus/tete.html.php';

?>
  <div class="central-haut bloc-largeur journal">
   <a href="./administration.php" class="maison">Retour</a>
    <h1>Journal des consommations</h1>
    <table class="lignes events suivi">
    <thead><tr><th>Date</th><th>Client</th><th>Consommation</th></tr></thead>
    <tbody>
<?php
$sess = 'a'; // Bascule la couleur du fond
$last_date = time() + _NBH_SESSION*3600;
$mois = Array('','Janvier','Février','Mars','Avril','Mai','Juin','Juillet','Août','Septembre','Octobre','Novembre','Décembre');

$sql->rek( "SELECT a.`timestamp`,a.`qtte_produit`,b.nom as `nom_produit`,CONCAT(c.prenom,' ',c.nom) as `nom_client` FROM commandes as a, clients as c, produits as b WHERE a.id_user = c.id AND a.id_produit = b.id ORDER BY timestamp DESC,qtte_produit ASC" );
while($a = $sql->fetch()){
  $t = strtotime($a['timestamp']);
  $heure = date('H:i:s (d/m)',$t);
  $session = date('d',$t) . ' ' . $mois[intval(date('m',$t))];
  
  if($last_date - strtotime($a['timestamp']) > _NBH_SESSION*3600 ){ // On change de session
    $sess = ($sess == 'a') ? 'b' : 'a';
    echo "<tr class='spacer-$sess'><td colspan='3'>Nouvelle session : <strong>$session</strong></td></tr>";
    $last_date = strtotime($a['timestamp']);
  }
  echo "<tr class='sess-$sess'><td><time>$heure</time></td><td>{$a['nom_client']}</td><td>{$a['nom_produit']} (<strong>{$a['qtte_produit']}</strong>)</td></tr>";
}

?></tbody></table>
  </div>
<?php
include 'inclus/pied.html.php';