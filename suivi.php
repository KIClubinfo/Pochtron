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
    
    <tbody>
<?php
$thead = '<table class="lignes events suivi"><thead><tr><th>Date</th><th>Client</th><th>Consommation</th></tr></thead>';
$tend = '</tbody></table>';

echo $thead;

$sess = '0'; // Bascule la couleur du fond
$last_date = 0;
$mois = Array('','Janvier','Février','Mars','Avril','Mai','Juin','Juillet','Août','Septembre','Octobre','Novembre','Décembre');

$sql->rek( "SELECT a.`timestamp`,a.`qtte_produit`,b.nom as `nom_produit`,CONCAT(c.prenom,' ',c.nom) as `nom_client` FROM commandes as a, clients as c, produits as b WHERE a.id_user = c.id AND a.id_produit = b.id ORDER BY DATE(SUBTIME(a.timestamp,'0 6:0:0')) DESC, timestamp ASC, qtte_produit ASC" );

while($a = $sql->fetch()){
  $t = strtotime($a['timestamp']);
  $heure = date('H:i:s (d/m)',$t);
  $session = date('d',$t) . ' ' . $mois[intval(date('m',$t))];
  
  if(abs($last_date - strtotime($a['timestamp'])) > _NBH_SESSION*3600 or $sess=='0'){ // On change de session
    
    if($sess!='0'){
	echo $tend;
	
	echo $thead;
    }
    $sess = ($sess == 'a') ? 'b' : 'a';
    echo "<tr class='spacer-$sess'><td colspan='3'>Session : <strong>$session</strong></td></tr>";
    $last_date = strtotime($a['timestamp']);
  }
  echo "<tr class='sess-$sess'><td><time>$heure</time></td><td>{$a['nom_client']}</td><td>{$a['nom_produit']} (<strong>{$a['qtte_produit']}</strong>)</td></tr>";
}

echo $tend;
?>
  </div>
<?php
include 'inclus/pied.html.php';