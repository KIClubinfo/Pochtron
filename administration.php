<?php
define('page_titre', "Administration &bull; Caisse Foyer");

include_once 'inclus/tete.inc.php';
include_once 'inclus/zoneadmin.inc.php';

$head_HTML = '<script type="text/javascript" src="scripts/admin.js"></script>';

include_once 'inclus/tete.html.php';
?>
  <div class="central bloc bienvenue admin">
   <a href="./" class="maison">Retour</a>
    <h1>Administration</h1>
    
      <?php
      
    $sql->rek("SELECT count(*) FROM `produits` WHERE qtt_reserve < qtt_alerte");
    $nb_ruptures = $sql->resultat_ligne(0);
    $sql->rek( "SELECT `val` FROM `bar` WHERE id='caisse'" );
    $solde = $sql->resultat_ligne(0);
    $sql->rek( "SELECT count(*) as nb, sum(solde) as deficit FROM clients WHERE solde < 0" );
    $r = $sql->fetch();
    $nb_deficit = $r['nb'];
    $deficit = $r['deficit'];
    
    if(!empty($_GET['connok']))
      echo '<div class="notif yeah"><strong>Yo !</strong>Tu as maintenant accès à la partie administrative du site.<br>Tu disposes d\'environ '.round(_DELAI_ADMIN/60).' minutes d\'inactivité avant déconnexion automatique.</div>';
    
    if($nb_ruptures>0)
      echo "<div class=\"notif attention estompe\"><strong>Stocks</strong>Attention, $nb_ruptures produit(s) sont à réapprovisionner !</div>";
      
    if($nb_deficit>0)
      echo "<div class=\"notif attention estompe\"><strong>Pochtrons</strong>Attention, $nb_deficit alcooliques doivent recharger leur compte ! Leur déficit cumulé s'élève à <strong>{$deficit}€</strong></div>";
      ?>
    <ul class="vertical">
      <li><?php
	echo " <div class='tip'>{$solde}€</div>";
      ?><a href="caisse.php">Caisse</a></li>
      <li><?php
      if($nb_ruptures>0)
	echo " <div class='tip'>$nb_ruptures alertes</div>";
      ?><a href="stock.php">Stock</a></li>
      <li><?php
      if($nb_deficit>0)
	echo " <div class='tip'>$nb_deficit en déficit</div>";
      ?><a href="clients.php">Comptes clients</a></li>
      <li><a href="clients.php#nouveauclient">Ajouter un client</a></li>
      <li><a href="stock.php#nouveauproduit">Ajouter un produit</a></li>
      <li><a href="journal.php">Voir le journal du logiciel</a></li>
      <li><a href="mailto:mickael.bergem@eleves.enpc.fr,charles.bochet@eleves.enpc.fr?Subject=[App Foyer] ">Contacter le support technique</a></li>
     </ul>
  </div>
<?php
include 'inclus/pied.html.php';
?>