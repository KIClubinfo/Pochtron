<?php
define('page_titre', "Stocks &bull; Caisse Foyer");


include_once 'inclus/zoneadmin.inc.php';

// Traitement AJAX
if(isset($_GET['ajax'])
    and !empty($_POST['nom'])
    and isset($_POST['prix'])
    and isset($_POST['id'])
    and !empty($_POST['volume'])
    and isset($_POST['qtt_reserve'])
    and $_POST['prix'] >= 0
    and $_POST['volume'] > 0
  ){
    logue("Modification du produit {$_POST['nom']} ({$_POST['prix']}€ et {$_POST['qtt_reserve']} en réserve)","stocks","warn");
    $_POST['id'] = intval($_POST['id']);
    $_POST['prix'] = floatval($_POST['prix']);
    $_POST['volume'] = floatval($_POST['volume']);
    $_POST['qtt_reserve'] = floatval($_POST['qtt_reserve']);
//     $_POST['qtt_alerte'] = floatval($_POST['qtt_alerte']);
    $_POST['nom'] = htmlentities($_POST['nom'],ENT_QUOTES,'utf-8');
    
    $sql->rek( "UPDATE produits SET `nom`='{$_POST['nom']}', `vol`='{$_POST['volume']}', `prix`='{$_POST['prix']}', `qtt_reserve`='{$_POST['qtt_reserve']}' WHERE id='{$_POST['id']}'" ); /*, `qtt_alerte`='{$_POST['qtt_alerte']}'*/
    echo $sql->nbrchangements();
    exit();
}

$head_HTML = '<script type="text/javascript" src="scripts/admin.js"></script><script type="text/javascript" src="http://code.jquery.com/jquery-1.4.2.js"></script>';

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
    and !empty($_POST['volume'])
    and isset($_POST['qtt_reserve'])
    and isset($_POST['qtt_alerte'])
    and $_POST['prix'] >= 0
    ){
      $_POST['prix'] = floatval($_POST['prix']);
      $_POST['qtt_reserve'] = floatval($_POST['qtt_reserve']);
      $_POST['qtt_alerte'] = floatval($_POST['qtt_alerte']);
      $_POST['nom'] = htmlentities($_POST['nom'],ENT_QUOTES,'utf-8');
      $_POST['volume'] = floatval($_POST['volume']);
      
      $sql->rek( "INSERT INTO produits (`nom` ,`prix` ,`qtt_reserve`,`qtt_alerte`, `vol`) VALUES ('{$_POST['nom']}','{$_POST['prix']}','{$_POST['qtt_reserve']}','{$_POST['qtt_alerte']}','{$_POST['volume']}')" );
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
<div id="notifs"></div>
<form onsubmit="return false;">
    <table class="stocks lignes lignes-hover" id="stocks">
    <thead>
     <tr><th>Produit</th><th>Prix</th><th>Quantité en réserve</th><th>Ventes</th><th>Volume</th><th>&nbsp;</th></tr>
     </thead>
<tfoot><tr><td colspan="6">
<?php

$sql->rek( 'SELECT `id` ,`nom` ,`vol` ,`prix` ,`icone` ,`qtt_reserve`,(qtt_reserve < qtt_alerte) as alerte,`ventes` FROM `produits` ORDER BY (qtt_reserve < qtt_alerte) DESC, `ventes` DESC' );
echo $sql->nbrlignes();
?> produits référencés</td></tr>
</tfoot>
     <tbody>
<?php
while($a = $sql->fetch()){
  echo "<tr".(($a['alerte']=='1') ? ' class="stock-alerte"' : '')."><td><span class=\"miniature\"><img class='ico-produit' src='images/produits/{$a['icone']}.png' alt=''></span> <input name='nom' type='text' value='{$a['nom']}'><input name='id' type='hidden' value='{$a['id']}'></td><td><input type='number' min='0' name='prix' step='0.1' value='{$a['prix']}'>€</td><td><input type='number' min='0' name='qtt_reserve' value='{$a['qtt_reserve']}'>".(($a['alerte']=='1') ? '<span class="fam-attention" title="La quantité est passée sous le seuil d\'alerte !"></span>' : '')."</td><td>{$a['ventes']}</td><td><input type='number' min='0.01' step='0.01' name='volume' value='{$a['vol']}'></td><td><input title='Appliquer les modifications' type='image' class='action invisible' alt='Édition' src='images/icones/icons/pencil_go.png'>
  <img title='Éditer cette ligne' class='action' name='edit' alt='Édition' src='images/icones/icons/pencil.png'>
  <img title='Supprimer cette ligne' class='action' onclick='suppp({$a['id']})' alt='Supprimer' src='images/icones/icons/delete.png'></td></td></tr>";
}

?>
</tbody>
</table>
</form>
    <form action="stock.php?ajout=1" method="POST">
    <table class="lignes" id="nouveauproduit">
<caption><span>Nouveau produit</span></caption>
<thead><tr>
      <th>
       <label for="nom">Produit</label>
      </th>
      <th>
       <label for="prix">Prix</label>
      <th>
       <label for="qtt_reserve">En réserve</label>
      </th>
      <th>
       <label for="qtt_alerte">Seuil d'alerte</label>
      </th>
      <th>
       <label for="volume">Volume</label>
      </th>
     </tr>
     </thead>
     <tbody>
     <tr>
      <td>
       <input type="text" name="nom" id="nom" required>
      </td>
      <td><input type="number" min="0" id="prix" name="prix" class="prix" step="0.1" required></td>
      <td>
       <input type="number" min="0" step="1" name="qtt_reserve" id="qtt_reserve" required title="Quantité en réserve"></td>
      <td><input type="number" min="0" step="1" id="qtt_alerte" name="qtt_alerte" required></td>
      <td>
       <input type="number" min="0.01" step="0.01" name="volume" id="volume" required title="Volume du produit servi"></td>
      </tr>
     <tr><td colspan="5">
      <input type="submit" class="vert ajout-produit" value="Ajouter ce produit">
      <input type="reset" value="On efface tout">
      </td></tr>
     </tbody></table>
 
</form>
  </div>
  
<script>
function setupRows(context) {
    $("span.view", context).remove();
    $('input[type="text"], input[type="number"], select', context).each(function () {
        $("<span />", {
            text: this.value,
            "class": "view"
        }).insertAfter(this);
        $(this).hide();
    });
    $('input[type="image"]', context).each(function () {
        $(this).hide();
    });
}


// Estomper les éléments qui le demandent
$(document).ready(function(){
//   $(".estompe").css("opacity","0");
  $(".invisible").hide();
  
  setupRows('#stocks');
});

$("table#stocks input, table#stocks select").focus(function(){
  $(this).closest("tr").find("img[name='edit']").hide();
  $(this).closest("tr").find("input[type='image']").show();
});

$("img[name='edit']").toggle(function () {
    $(this).closest("tr").find("input[type='text'], input[type='number'], select,  span.view").toggle();
}, function () {
    setupRows($(this).closest("tr"));
});

$("table#stocks input[type='image']").click(function(){
  $.post("stock.php?ajax=1",
  {
    id: $(this).closest("tr").find("input[name='id']").val(),
    nom: $(this).closest("tr").find("input[name='nom']").val(),
    prix: $(this).closest("tr").find("input[name='prix']").val(),
    volume: $(this).closest("tr").find("input[name='volume']").val(),
    qtt_reserve: $(this).closest("tr").find("input[name='qtt_reserve']").val()
  },
  function(data,status){
    if(status=="success" && data=="1"){
      $("#notifs").prepend('<div class="notif yeah estompe"><strong>Modification effectuée</strong>Ce produit a bien été modifié.</div>');
      $("img[name='edit']").show();
      $("input[type='image']").hide();
      setTimeout( "jQuery('.estompe').fadeOut();",4000 );
      $(".notif").click(function(){$(this).fadeOut();});
      
      setupRows('#stocks');
    }
    else{
      $("#notifs").prepend('<div class="notif argh estompe"><strong>Aucun produit n\'a été modifié</strong>T\'as raté un truc, toi...</div>');
      $("img[name='edit']").show();
      $("input[type='image']").hide();
      setupRows('#stocks');
    }
  });
});

</script>
<?php
include 'inclus/pied.html.php';
?>