<?php
define('page_titre', "Stocks &bull; Caisse Foyer");

include_once 'inclus/zoneadmin.inc.php';

$head_HTML = '<script type="text/javascript" src="scripts/admin.js"></script>';

include_once 'inclus/tete.html.php';

?>
  <div class="central bloc stocks">
   <a href="./administration.php" class="maison">Retour</a>
    <h1>On a quoi en réserve ?</h1>
    <?php
    

if(!empty($_GET['efface'])){
    $id = intval($_GET['efface']);
    
    $sql->rek( "DELETE FROM produits WHERE id='$id'" );
    if($sql->nbrchangements() != 1){
      logue("Erreur lors de la suppression d'un produit !","erreur",'critique');
      echo '<div class="notif argh"><strong>Échec</strong>Impossible de supprimer le produit #'.$id.'... C\'est dommage !</div>';
    }
    else{
      logue("Suppression du produit #$id",'stocks','warn');
      echo '<div class="notif yeah estompe"><strong>Produit supprimé</strong>Et un de moins !<br>Les statistiques resteront sauvegardées mais ne seront plus visibles, pour les restaurer : faire appel à MB015 !</div>';
    }
}

if(!empty($_GET['ajout'])){
    if(!empty($_POST['nom'])
    and isset($_POST['prix'])
    and isset($_POST['qtt_reserve'])
    and isset($_POST['qtt_alerte'])
    and $_POST['prix'] > 0
    ){
      $_POST['prix'] = floatval($_POST['prix']);
      $_POST['qtt_reserve'] = floatval($_POST['qtt_reserve']);
      $_POST['qtt_alerte'] = floatval($_POST['qtt_alerte']);
      $_POST['nom'] = htmlentities($_POST['nom'],ENT_QUOTES,'utf-8');
      
      $sql->rek( "INSERT INTO produits (`nom` ,`prix` ,`qtt_reserve`,`qtt_alerte`) VALUES ('{$_POST['nom']}','{$_POST['prix']}','{$_POST['qtt_reserve']}','{$_POST['qtt_alerte']}')" );
      if($sql->nbrchangements() != 1){
	logue("Erreur SQL lors de l'ajout d'un produit !","erreur");
	echo '<div class="notif argh"><strong>Échec</strong>Une erreur s\'est produite, il n\'a pas été possible de rajouter ce produit... Le frigo est plein ?</div>';
      }
      else{
	logue("Ajout du produit \"{$_POST['nom']}\"",'stocks','warn');
	echo '<div class="notif yeah estompe"><strong>Produit ajouté</strong>Et un de plus !</div>';
      }
    }
}
?>
    <form action="stock.php?ajout=1" method="POST">
    <table class="stocks lignes lignes-hover">
    <thead>
     <tr><th>Produit</th><th>Prix</th><th>Quantité en réserve</th><th>Ventes</th><th>&nbsp;</th></tr>
     </thead>
<tfoot><tr><td colspan="5">
<?php

$sql->rek( 'SELECT `id` ,`nom` ,`prix` ,`icone` ,`qtt_reserve`,(qtt_reserve < qtt_alerte) as alerte,`ventes` FROM `produits` ORDER BY (qtt_reserve < qtt_alerte) DESC, `ventes` DESC' );
echo $sql->nbrlignes();
?> produits référencés</td></tr>
<tr id="nouveauproduit"><th colspan="5">Nouveau produit :</th></tr>
     <tr>
      <td>
       <label for="nom">Produit</label>
       <input type="text" name="nom" id="nom" required>
      </td>
      <td>
       <label for="prix">Prix</label><input type="number" min="0" id="prix" name="prix" class="prix" step="0.1" required></td>
      <td>
       <label for="qtt_reserve">En réserve</label>
       <input type="number" min="0" step="1" name="qtt_reserve" id="qtt_reserve" required title="Quantité en réserve"></td>
      <td>
       <label for="qtt_alerte">Seuil d'alerte</label><input type="number" min="0" step="1" id="qtt_alerte" name="qtt_alerte" required></td>
      <td>
      <input type="image" class='action' alt='Ajouter' src='images/icones/icons/cart_add.png' title="Ajouter le produit">
      </td>
     </tr></tfoot>
     <tbody>
<?php
while($a = $sql->fetch()){
  echo "<tr".(($a['alerte']=='1') ? ' class="stock-alerte"' : '')."><td><img class='ico-produit' src='images/produits/{$a['icone']}.png' alt=''>{$a['nom']}</td><td>{$a['prix']}€</td><td>{$a['qtt_reserve']}".(($a['alerte']=='1') ? '<span class="fam-attention" title="La quantité est passée sous le seuil d\'alerte !"></span>' : '')."</td><td>{$a['ventes']}</td><td><img class='action' onclick='edit({$a['id']})' alt='Édition' src='images/icones/icons/pencil.png'><img class='action' onclick='supp({$a['id']})' alt='Supprimer' src='images/icones/icons/delete.png'></td></tr>";
}

?>
</tbody>
</table>
</form>
  </div>
<?php
include 'inclus/pied.html.php';
?>