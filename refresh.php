<?php
define('page_titre', "Rafraichissement des statistiques utilisateurs &bull; Caisse Foyer");

include_once 'inclus/zoneadmin.inc.php';
$head_HTML = '<script type="text/javascript" src="scripts/admin.js"></script><script type="text/javascript" src="http://code.jquery.com/jquery-1.4.2.js"></script>';
include_once 'inclus/tete.html.php';

?>
  <div class="central bloc stocks">
   <a href="./administration.php" class="maison">Retour</a>
    <h1>Pour corriger les boulettes</h1>
    <?php
	
	$sql->rek( 'SELECT * FROM commandes' );
	$nb_commandes_tot = $sql->nbrlignes();
	
	$sql->rek( 'SELECT * FROM clients ORDER BY nom ASC' );
	$client = array();
	$iterator = 0;
	$nb_commandes = 0;
	$nb_clients = 0;
	
	while($client[$iterator++] = $sql->fetch())
	{}
	
	$nb_clients = $iterator - 1;
	for ($i=0;$i<$nb_clients;$i++)
	{
		$client[$i]['new_litres_bus'] = 0;
		$client[$i]['new_eq_kro'] = 0;
		$client[$i]['new_nb_consos'] = 0;
		
		$sql->rek( 'SELECT c.id_user, c.id_produit, c.qtte_produit AS qtte, p.id, p.vol AS vol, p.taux AS taux FROM commandes c, produits p WHERE c.id_user='.$client[$i]['id'].' AND c.id_produit=p.id');
		while($a = $sql->fetch())
		{
			$client[$i]['new_litres_bus'] +=  number_format($a['qtte']*$a['vol'],2);
			$client[$i]['new_eq_kro'] += number_format($a['qtte']*$a['vol']*$a['taux']/0.042,2);
			$client[$i]['new_nb_consos'] += $a['qtte'];
			$nb_commandes++;
		}
	}

	if ((isset($_GET['valid'])) && ($_GET['valid'] == 1))
	{
		for ($i = 0;$i<$nb_clients;$i++)
		{
			$sql->rek( "UPDATE clients SET `litres_bus`='{$client[$i]['new_litres_bus']}', `eq_kro`='{$client[$i]['new_eq_kro']}' WHERE id='{$client[$i]['id']}'" );
			$client[$i]['litres_bus'] = $client[$i]['new_litres_bus'];
			$client[$i]['eq_kro'] = $client[$i]['new_eq_kro'];
		}
	}
	
?>
<div id="notifs"></div>
<form action="refresh.php" method="GET">
<input type="submit" class="vert" value="Valider la synchronisation" />
<input type="hidden" name="valid" value="1" />
    <table class="clients lignes lignes-hover" id="clients_bis">
    <thead>
     <tr><th></th><th>Nom</th><th>Solde</th><th>Nb consos</th><th>Litres bus (ancienne valeur) </th><th>Eq. Kro (a. v.)</th><th>&nbsp;</th></tr>
     </thead>
<tfoot><tr><td colspan="6">
<?php


echo $nb_clients
?> clients référencés, <?php echo $nb_commandes; ?> commandes prises en comptes sur <?php echo $nb_commandes_tot; ?> (cause : suppression produit)</td></tr>

</tfoot>
     <tbody>
<?php

for ($i = 0;$i<$nb_clients;$i++)
{
$client[$i]['litres_bus'] = number_format($client[$i]['litres_bus'],2);
$client[$i]['eq_kro'] = number_format($client[$i]['eq_kro'],2);
$client[$i]['new_litres_bus'] = number_format($client[$i]['new_litres_bus'],2);
$client[$i]['new_eq_kro'] = number_format($client[$i]['new_eq_kro'],2);

$photo = (file_exists("images/photos/".$client[$i]['id'].".jpg")) ? $client[$i]['id'] :  "sans_photo";
  
  echo "<tr".(($client[$i]['litres_bus']!=$client[$i]['new_litres_bus']) ? ' class="stock-alerte"' : '').">
  <td>
  <img class='ico-client' src='images/photos/$photo.jpg' alt=''>
  <td>{$client[$i]['nom']} {$client[$i]['prenom']}</td>
  <td>{$client[$i]['solde']} €</td>
  <td>{$client[$i]['nb_consos']} ({$client[$i]['new_nb_consos']})</td>
  <td>{$client[$i]['litres_bus']} ({$client[$i]['new_litres_bus']})</td>
  <td>{$client[$i]['eq_kro']} ({$client[$i]['new_eq_kro']})</td>
  </tr>";
 }
  

?>
</tbody>
</table>
</form>
    
  </div>
  

<?php
include 'inclus/pied.html.php';
?>