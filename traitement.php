<?php
//Application JSON
header('Content-type: application/json'); 

//Codes Erreurs
define('AJAX_OK',0);
define('AJAX_UNKNOW_FAIL',1);
define('AJAX_NOT_IMPLEMENTED',2);
define('UNDEFINED_ACTION',100);
define('INVALID_ACTION',101);
define('UNDEFINED_ID',200);
define('INVALID_ID',201);
define('EMPTY_ORDER',300);
define('INVALID_ORDER',301);

//Initialisations
$erreur=-1;
$reponse='';
date_default_timezone_set('Europe/Paris');

//Include pour l'objet $bdd notamment
include_once 'inclus/tete.inc.php';

/*/////////////////////////////FONCTIONS/////////////////////////////*/
//Conversion de la chaîne de commande en un tableau
function parse_order($string_order)
{
	$array_temp = explode(',',$string_order);
	$array_order = array();
	
	for ($i=1;$i<count($array_temp)-1;$i++)
	{
		$array_order[$i] = explode(':',$array_temp[$i]);
	}
	
	return $array_order;
}

//Prendre une nouvelle commande
function new_order()
{
	global $erreur;
	global $reponse;
	global $sql;
	
	if (!isset($_GET['id']))
	{
		$erreur=UNDEFINED_ID;
		$reponse="Id indéfini";
		return;
	}
	
	$_GET['id'] = intval($_GET['id']); // Empêche l'injection SQL
	$sql->rek( 'SELECT * FROM clients WHERE id=\''.$_GET['id'].'\'');//Requète
	
	if ($sql->nbrlignes() != 1)
	{
		$erreur=INVALID_ID;
		$reponse="Id invalide";
	}
	else
	{
		$eleve = $sql->fetch();
		
		if (!isset($_GET['consom']))
		{
			$erreur=EMPTY_ORDER;
			$reponse="Commande vide";
			return;
		}
		
		$order = parse_order(htmlspecialchars($_GET['consom'], ENT_QUOTES, 'UTF-8'));
		$new_solde=$eleve['solde'];
		$new_nb_consos = $eleve['nb_consos'];
		$new_litres_bus = $eleve['litres_bus'];
		
		if (count($order) < 1)
		{
			$erreur=INVALID_ORDER;
			$reponse="Commande invalide";
			return;
		}

		array_multisort($order, SORT_ASC);
		
		$rek_in='';
		for ($i=0;$i<count($order);$i++) $rek_in = $rek_in.$order[$i][0].',';
		$rek_in = substr($rek_in, 0, strlen($rek_in)-1);
		
		$i=0;
		
		$sql->rek( 'SELECT * FROM produits WHERE id IN ('.$rek_in.') ORDER BY id ASC');
		while($products = $sql->fetch())
		{
			if ($products['id']==$order[($i)][0])
			{
				$new_solde -= $products['prix']*$order[$i][1];
				$new_nb_consos += $order[$i][1];
				$new_litres_bus += $products['vol']*$order[$i][1];
				$sql->rek('UPDATE produits SET qtt_reserve=\''.($products['qtt_reserve']-$order[$i][1]).'\', ventes=\''.($products['ventes']+$order[$i][1]).'\' WHERE id=\''.$products['id'].'\'', false);
				$sql->rek('INSERT INTO commandes (id_user, timestamp, id_produit, qtte_produit) VALUES (\''.$_GET['id'].'\',\''.date("Y-m-d H:i:s").'\',\''.$products['id'].'\',\''.$order[$i][1].'\')', false);
			}
			$i++;
		}

		$sql->rek( 'UPDATE clients SET solde=\''.($new_solde).'\', litres_bus=\''.($new_litres_bus).'\', nb_consos=\''.($new_nb_consos).'\' WHERE id=\''.$_GET['id'].'\'');
		
		$erreur = AJAX_OK;
		$reponse = "Commande de ".$eleve['prenom']." ".$eleve['nom']." passée avec succès. Nouveau solde : ".($new_solde);
	}
}

//Ajouter du liquide à un élève
function add_cash()
{
	global $erreur;
	global $reponse;
	
	$erreur = AJAX_NOT_IMPLEMENTED;
	$reponse = "Le solde de l'élève a bien été augmenté";
}

//Annuler une commande
function cancel()
{
	$_GET['id'] = intval($_GET['id']);
	global $erreur;
	global $reponse;
	global $sql;
	
	$sql->rek( 'SELECT c.id_user, c.id_produit, c.qtte_produit, p.prix, p.vol FROM commandes c, produits p WHERE c.id=\''.$_GET['id'].'\' AND p.id=c.id_produit');//Requète
	
	if ($sql->nbrlignes() != 1)
	{
		$erreur=INVALID_ID;
		$reponse="Id invalide";
	}
	else
	{
		$commande = $sql->fetch();
		$sql->rek('DELETE FROM commandes WHERE id=\''.$_GET['id'].'\'');
		//$sql->rek('UPDATE  FROM commandes WHERE id=\''.$commande['user_id'].'\'');
		
	}
	
	$erreur = AJAX_NOT_IMPLEMENTED;
	$reponse = "Fonction non encore implémentée";
}

/*/////////////////////////////CORPS/////////////////////////////*/
if (isset($_GET['action']))
{
	switch ($_GET['action']) 
	{
		case "order":
			new_order();
			break;
		case "add_cash":
			add_cash();
			break;
		case "cancel":
			cancel();
			break;
		default:
		   $erreur = INVALID_ACTION;
		   $reponse = "Action incorrecte";
	}
}
else
{
	$erreur = UNDEFINED_ACTION;
	$reponse = "Action indéfinie";
}

?>
{
	"code_erreur": "<?php echo $erreur; ?>",
	"reponse": "<?php echo $reponse;?>"
}                                                         


