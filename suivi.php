<?php
define('page_titre', "Historique des commandes &bull; Caisse Foyer");
define('_NBH_SESSION', 6); // Nombre d'heure sans commande avant le début d'une nouvelle session

include_once 'inclus/zoneadmin.inc.php';

// Affichage sous forme graphique des produits vendus
function affiche_consos($consos,$session){
    echo '<table class="lignes consos"><thead><tr><th colspan="2">Foyer du '.$session.'</th></tr><tr><th>Produit</th><th>Quantitée vendue</th></tr></thead><tbody>';
    
    foreach($consos[$session] as $produit => $qtt){
	echo "<tr><td>$produit</td><td>$qtt</td></tr>";
    }
    
    echo '</tbody></table>';
}

$head_HTML = '<script type="text/javascript" src="scripts/admin.js"></script>';

include_once 'inclus/tete.html.php';

?>
  <div class="central-haut bloc-largeur journal suivi">
   <a href="./administration.php" class="maison">Retour</a>
    <h1>Journal des consommations</h1>
    
<?php
if(empty($_GET['affiche_tous'])){
    ?><div class="notif estompe">Seuls les foyers des trois derniers mois sont affichés, mais vous pouvez choisir de <a href="suivi.php?affiche_tous=1">tous les afficher</a>.</div><?php
}

ob_start();

// Comptabilisation des produits consommés
$consos = Array();
$sessions = Array();

$sess = '0'; // Bascule la couleur du fond
$last_date = 0;
$mois = Array('','Janvier','Février','Mars','Avril','Mai','Juin','Juillet','Août','Septembre','Octobre','Novembre','Décembre');
$jours = Array('','Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi','Dimanche');

$d_limit = (isset($_GET['affiche_tous'])) ?  '' : 'AND TO_DAYS(NOW()) - TO_DAYS(a.timestamp) <= 90';

$sql->rek( "SELECT a.`timestamp`,a.`qtte_produit`,CONCAT(b.nom,' ',b.vol) as `nom_produit`,CONCAT(c.prenom,' ',c.nom) as `nom_client` FROM commandes as a, clients as c, produits as b WHERE a.id_user = c.id AND a.id_produit = b.id $d_limit ORDER BY DATE(SUBTIME(a.timestamp,'0 6:0:0')) DESC, timestamp ASC, qtte_produit ASC" );

while($a = $sql->fetch()){
  $t = strtotime($a['timestamp']);
  $heure = date('H:i:s (d/m)',$t);
  
  if(abs($last_date - strtotime($a['timestamp'])) > _NBH_SESSION*3600 or $sess=='0'){ // On change de session
    
    if($sess!='0'){
	echo '</tbody></table>';
	
	affiche_consos($consos,$session);
	
    }
    
    // Nouvelle session
    $session = $jours[intval(date('N',$t))] . ' ' . date('d',$t) . ' ' . $mois[intval(date('n',$t))];
    $sessions[$session] = $t;
    
    $sess = ($sess == 'a') ? 'b' : 'a';
    echo '<table class="lignes events suivi" id="'.$t.'"><thead><tr><th colspan="3">Foyer du '.$session.'</th></tr>';
    echo '<tr><th>Date</th><th>Client</th><th>Consommation</th></tr></thead><tbody>';
    $last_date = strtotime($a['timestamp']);
    $consos[$session] = Array();
  }
  
  // On ajoute la conso sans se plaindre du fait que le compteur n'a pas été initialisé
  @($consos[$session][$a['nom_produit']] += $a['qtte_produit']);
  
  echo "<tr class='sess-$sess'><td><time>$heure</time></td><td>{$a['nom_client']}</td><td>{$a['nom_produit']} (<strong>{$a['qtte_produit']}</strong>)</td></tr>";
}

echo '</tbody></table>';
affiche_consos($consos,$session);
$contenu = ob_get_clean();

echo '<h2>Liste des foyers</h2><ul class="liste-foyers">';
foreach($sessions as $session_nom => $session_id) {
    $_SERVER['REQUEST_URI'] = str_replace("'",'',$_SERVER['REQUEST_URI']); // On empêche l'injection de code
    echo "<li>Foyer du <a href='{$_SERVER['REQUEST_URI']}#$session_id'>$session_nom</a></li>";
}

echo '<li><a href="suivi.php?affiche_tous=1">Voir tous la liste complète des foyers</a></li>
</ul><h2>Détail des commandes</h2>';
echo $contenu;
?>
  </div>
<?php
include 'inclus/pied.html.php';