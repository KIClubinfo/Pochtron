<?php
define('page_titre', "Caisse &bull; Caisse Foyer");

include_once 'inclus/zoneadmin.inc.php';

// Traitement AJAX
if(isset($_GET['ajax'])
  and !empty($_POST['id'])
  and !empty($_POST['nom'])
  and !empty($_POST['prenom'])
  and isset($_POST['solde'])
  and !empty($_POST['statut'])
  ){
    logue("Modification du client {$_POST['prenom']} {$_POST['nom']} ({$_POST['solde']}€ et {$_POST['statut']})","clients","warn");
    
    $_POST['id'] = intval($_POST['id']);
    $_POST['solde'] = floatval($_POST['solde']);
    $_POST['nom'] = htmlentities(ucwords($_POST['nom']),ENT_QUOTES,'utf-8');
    $_POST['prenom'] = htmlentities(ucwords($_POST['prenom']),ENT_QUOTES,'utf-8');
    $_POST['statut'] = $sql->secur($_POST['statut']);
    $sql->rek( "UPDATE clients SET nom='{$_POST['nom']}',prenom='{$_POST['prenom']}',solde='{$_POST['solde']}',active='{$_POST['statut']}' WHERE id='{$_POST['id']}'" );
    echo $sql->nbrchangements();
    exit();
}




$head_HTML = '<script type="text/javascript" src="scripts/admin.js"></script><script type="text/javascript" src="http://code.jquery.com/jquery-1.4.2.js"></script>';

include_once 'inclus/tete.html.php';

?>
  <div class="central bloc clients">
   <a href="./administration.php" class="maison">Retour</a>
    <h1>Nos beaux pochtrons</h1>
    <?php
    

if(!empty($_GET['efface'])){
    $id = intval($_GET['efface']);
    
    $sql->rek( "DELETE FROM clients WHERE id='$id'" );
    if($sql->nbrchangements() != 1){
      logue("Erreur lors de la suppression d'un client !","erreur",'critique');
      echo '<div class="notif argh"><strong>Échec</strong>Impossible de supprimer ce client #'.$id.'... C\'est dommage ! Demande à la mafia ?</div>';
    }
    else{
      logue("Suppression du client #$id",'clients','warn');
      echo '<div class="notif yeah estompe"><strong>Client supprimé</strong>En espérant que la police viendra pas roder par ici...</div>';
    }
}

if(!empty($_GET['ajout'])){
    if(!empty($_POST['nom'])
    and !empty($_POST['prenom'])
    and isset($_POST['solde'])
    and !empty($_POST['statut'])
    ){
      $_POST['solde'] = floatval($_POST['solde']);
      $_POST['nom'] = htmlentities(ucwords($_POST['nom']),ENT_QUOTES,'utf-8');
      $_POST['prenom'] = htmlentities(ucwords($_POST['prenom']),ENT_QUOTES,'utf-8');
      $_POST['statut'] = $sql->secur($_POST['statut']);
      
      $sql->rek( "INSERT INTO clients (`nom` ,`prenom` ,`solde`,`active`) VALUES ('{$_POST['nom']}','{$_POST['prenom']}','{$_POST['solde']}','{$_POST['statut']}')" );
      if($sql->nbrchangements() != 1){
	logue("Erreur lors de l'ajout d'un client ({$_POST['prenom']} {$_POST['nom']}) !","erreur",'critique');
	echo '<div class="notif argh"><strong>Échec</strong>Une erreur s\'est produite, il n\'a pas été possible d\'enregistrer ce client...</div>';
      }
      else{
	logue("Ajout du client {$_POST['prenom']} {$_POST['nom']}",'clients','warn');
	echo '<div class="notif yeah estompe"><strong>Client ajouté</strong>Et un de plus !</div>';
	}
    }
}
?>
<div id="notifs"></div>
<form onsubmit="return false;">
    <table class="clients lignes lignes-hover" id="clients">
    <thead>
     <tr><th></th><th>Nom</th><th>Prénom</th><th>Solde</th><th>Consos</th><th>État</th><th></th></tr>
     </thead>
<tfoot><tr><td colspan="7">
<?php

$sql->rek( 'SELECT `id` ,prenom,nom,solde,active,nb_consos FROM clients ORDER BY (solde<0) DESC, nom ASC' );
echo $sql->nbrlignes();
?> clients connus</td></tr></tfoot>
     <tbody>
<?php
while($a = $sql->fetch()){
  
  $photo = (file_exists("images/photos/".$a['id'].".jpg")) ? $a['id'] :  "sans_photo";
  
  echo "<tr".(($a['solde']<0) ? ' class="stock-alerte"' : '')." id='c{$a['id']}'>
  <td>
  <img class='ico-client' src='images/photos/$photo.jpg' alt=''>
  <input type='hidden' value='{$a['id']}' name='id'></td>
  <td><input name='nom' type='text' value='{$a['nom']}'></td>
  <td><input name='prenom' type='text' value='{$a['prenom']}'></td>
  <td><input name='solde' type='number' step='0.1' value='{$a['solde']}'>€".(($a['solde']<0) ? '<span class="fam-attention" title="Le solde est négatif !"></span>' : '')."</td>
  <td>{$a['nb_consos']}</td>
  <td class='statut'><select name='statut'>
      <option value='activé'".(($a['active']=='activé') ? ' selected' : '').">Normal</option>
      <option value='inactif'".(($a['active']=='inactif') ? ' selected' : '').">Désactivé</option>
      <option value='bloqué'".(($a['active']=='bloqué') ? ' selected' : '').">Bloqué</option>
      </select> ".(($a['active']!='activé') ? '<span class="fam-attention" title="Ce client n\'est pas activé !"></span>' : '')."</td>
  <td><input title='Appliquer les modifications' type='image' class='action invisible' alt='Édition' src='images/icones/icons/pencil_go.png'>
  <img title='Éditer cette ligne' class='action' name='edit' alt='Édition' src='images/icones/icons/pencil.png'>
  <img title='Supprimer cette ligne' class='action' onclick='suppc({$a['id']})' alt='Supprimer' src='images/icones/icons/delete.png'></td></tr>";
  /*
  
  echo "<tr".(($a['solde']<0) ? ' class="stock-alerte"' : '')." id='c{$a['id']}'><td><img class='ico-client' src='images/produits/photos/{$a['id']}.png' alt=''></td><td><span name='nom{$a['id']}'>{$a['nom']}</span></td><td><span name='prenom'>{$a['prenom']}</span></td><td>{$a['solde']}€".(($a['solde']<0) ? '<span class="fam-attention" title="Le solde est négatif !"></span>' : '')."</td><td>{$a['nb_consos']}</td><td class='statut'>{$a['active']} ".(($a['active']!='activé') ? '<span class="fam-attention" title="Ce client n\'est pas activé !"></span>' : '')."</td><td><button>Edit</button><img class='action' id='edit' onclick='editc({$a['id']})' alt='Édition' src='images/icones/icons/pencil.png'><img class='action' onclick='suppc({$a['id']})' alt='Supprimer' src='images/icones/icons/delete.png'></td></tr>";*/
}

?>
</tbody>
</table>
</form>
<form action="clients.php?ajout=1" method="POST">
<table class="lignes" id="nouveauclient">
<caption><span>Enregistrer un client</span></caption>
<thead><tr>
      <th>
       <label for="prenom">Prénom</label>
      </th>
      <th>
       <label for="nom">Nom</label></th>
      <th>
       <label for="solde">Solde de départ</label></th>
      <th>
       <label for="statut">Statut</label></th>
     </tr>
     </thead>
     <tbody>
     <tr>
      <td>
       <input type="text" name="prenom" id="prenom" required>
      </td>
      <td>
       <input type="text" name="nom" id="nom" required></td>
      <td>
       <input type="number" value="0" min="0" step="0.1" name="solde" id="solde" required title="Solde placé sur le compte pour l'ouverture"> €</td>
      <td><select name="statut" id="statut">
      <option value="activé">Normal</option>
      <option value="inactif">Désactivé</option>
      <option value="bloqué">Bloqué</option>
      </select></td>
     </tr>
     <tr><td colspan="4">
      <input type="submit" class="vert ajout-client" value="Enregistrer ce client">
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
  
  setupRows('#clients');
});

$("table#clients input, table#clients select").change(function(){
  $(this).closest("tr").find("img[name='edit']").hide();
  $(this).closest("tr").find("input[type='image']").show();
});

$("img[name='edit']").toggle(function () {
    $(this).closest("tr").find("input[type='text'], input[type='number'], select,  span.view").toggle();
}, function () {
    setupRows($(this).closest("tr"));
});

$("table#clients input[type='image']").click(function(){
  $.post("clients.php?ajax=1",
  {
    id: $(this).closest("tr").find("input[name='id']").val(),
    prenom: $(this).closest("tr").find("input[name='prenom']").val(),
    nom: $(this).closest("tr").find("input[name='nom']").val(),
    solde: $(this).closest("tr").find("input[name='solde']").val(),
    statut: $(this).closest("tr").find("select[name='statut']").val()
  },
  function(data,status){
    if(status=="success" && data=="1"){
      $("#notifs").prepend('<div class="notif yeah estompe"><strong>Modification effectuée</strong>Ce client a bien été modifié.</div>');
      $("img[name='edit']").show();
      $("input[type='image']").hide();
      setTimeout( "jQuery('.estompe').fadeOut();",4000 );
      $(".notif").click(function(){$(this).fadeOut();});
      
      setupRows('#clients');
    }
    else{
      $("#notifs").prepend('<div class="notif argh estompe"><strong>Modification ratée</strong>Comme qui dirait, Something Went Wrong...</div>');
    }
  });
});

</script>
<?php
include 'inclus/pied.html.php';
?>