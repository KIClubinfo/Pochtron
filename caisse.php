<?php
define('page_titre', "Caisse &bull; Caisse Foyer");

$head_HTML = '<script type="text/javascript" src="scripts/admin.js"></script>';

include_once 'inclus/tete.html.php';

?>
  <div class="central bloc caisse">
   <a href="./administration.php" class="maison">Retour</a>
    <h1>Caisse</h1>
    <?php
// Sélection du solde actuel
$sql->rek( "SELECT `val` FROM `bar` WHERE id='caisse'" );
$solde = $sql->resultat_ligne(0);

if(isset($_GET['rajouter'])){
  
  
    if(!empty($_GET['modif']) and !empty($_POST['retrait'])){
      // Crédit demandé
      $credit = floatval($_POST['retrait']);
      if($credit<0){
	echo '<div class="notif argh"><strong>Échec</strong>C\'est refusé mon gars, tu ne peux pas rajouter '.$credit.'€, arrête de me prendre pour un con !</div>';
      }
      elseif(empty($_POST['motif'])){
	echo '<div class="notif argh"><strong>Échec</strong>Il faut absolument que tu précises un motif pour rajouter de l\'argent en caisse...<br>Si tu veux créditer un compte, c\'est plutôt <a href="clients.php">par là</a>.</div>';
      }
      elseif($credit!=0){
	// Demande accepté
	logue("Crédit de {$credit}€ dans la caisse. Motif : {$_POST['motif']}", "caisse",'warn');
	// Le nouveau solde est de :
	$solde += $credit;
	$sql->rek( "UPDATE `bar` SET `val`='$solde' WHERE id='caisse'" );
	echo '<div class="notif yeah estompe"><strong>Opération effectuée !</strong>Ce crédit a été enregistré.</div>';
      }
    }
    ?>
      <div class="notif">Il est aussi possible de <a href="caisse.php">retirer de l'argent de la caisse</a>, c'est pas compliqué.</div>
    <form method="POST" action="caisse.php?modif=1&amp;rajouter=1">
    <table class="darkbox">
    <tr><td>Solde actuel</td><td class="solde" id="soldeactuel"><?php echo $solde; ?></td></tr>
    <tr><td>Crédit</td><td class="solde"><input type="number" name="retrait" min="0" step="0.10" onchange="modif_solde(-this.value)" value="5"></td></tr>
    <tr><td>Futur solde</td><td class="solde" id="solderestant"><?php echo $solde; ?></td></tr>
    <tr><td colspan="2"><input type="text" name="motif" placeholder="Pourquoi rajouter de l'argent en caisse maintenant ?" title="Motif du crédit" required="required"></td></tr>
    <tr><td colspan="2"><input type="submit" value="Créditer" class="gros"></td></tr>
    </table>
    </form>
  </div>
  <?php
}
else{
    // Sélection du solde actuel
    $sql->rek( "SELECT `val` FROM `bar` WHERE id='caisse'" );
    $solde = $sql->resultat_ligne(0);
  
  
    if(!empty($_GET['modif']) and !empty($_POST['retrait'])){
      // Retrait demandé
      $retrait = floatval($_POST['retrait']);
      if($retrait<0 or $retrait>$solde){
	echo '<div class="notif argh"><strong>Échec</strong>C\'est refusé mon gars, tu ne peux pas prendre '.$retrait.'€ dans la caisse !</div>';
      }
      else{
	// Demande accepté
	logue("Retrait de {$retrait}€/{$solde}€ dans la caisse.", "caisse");
	// Le nouveau solde est de :
	$solde -= $retrait;
	$sql->rek( "UPDATE `bar` SET `val`='$solde' WHERE id='caisse'" );
	echo '<div class="notif yeah estompe"><strong>Opération effectuée !</strong>Ce retrait a été enregistré.<br>Tu peux maintenant te remplir les poches.</div>';
      }
    }
    
    if($solde == 0){
      ?>
      <div class="notif argh"><strong>Misère</strong>Il n'y a rien à prendre dans cette caisse, elle est vide ! Tu peux par contre <a href="caisse.php?rajouter=1">rajouter de l'argent</a> !</div>
      <?php
    }
    else{?>
      <div class="notif">Il est aussi possible de <a href="caisse.php?rajouter=1">placer de l'argent dans la caisse</a>, c'est pas compliqué.</div>
    <?php
    }
    ?>
    <form method="POST" action="caisse.php?modif=1">
    <table class="darkbox">
    <tr><th>Solde actuel</td><td class="solde" id="soldeactuel"><?php echo $solde; ?></td></tr>
    <tr><th>Prélèvement</td><td class="solde"><input type="number" name="retrait" min="0" max="<?php echo $solde; ?>" step="0.10" value="<?php echo $solde; ?>" onchange="modif_solde(this.value)"></td></tr>
    <tr><th>Solde restant</td><td class="solde" id="solderestant">0</td></tr>
    <tr><td colspan="2"><input type="submit" value="Retirer" class="gros"></td></tr>
    </table>
    <small>Attention, seul le trésorier du Foyer est autorisé à <strike>retirer de</strike> se tirer avec l'argent de la caisse !</small>
    </form>
  </div>
<?php

}
include 'inclus/pied.html.php';
?>