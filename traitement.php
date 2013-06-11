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
		
		if (count($order) < 1)
		{
			$erreur=INVALID_ORDER;
			$reponse="Commande invalide";
			return;
		}
		
		$sql->rek( 'SELECT * FROM produits');
		while($products = $sql->fetch())
		{
			for ($i=1;$i<=count($order);$i++)
			{
				if ($products['id']==$order[($i)][0])
				{
					$new_solde -= $products['prix']*$order[$i][1];
					$sql->rek( 'UPDATE produits SET qtt_reserve=\''.($products['qtt_reserve']-$order[$i][1]).'\', ventes=\''.($products['ventes']+$order[$i][1]).'\' WHERE id=\''.$products['id'].'\'');
				}
			}
		}
		
		
		$sql->rek( 'UPDATE clients SET solde=\''.($new_solde).'\' WHERE id=\''.$_GET['id'].'\'');
		
		$erreur = AJAX_OK;
		$reponse = "Commande de ".$eleve['prenom']." ".$eleve['nom']." passée avec succès. Nouveau solde : ".($new_solde);
	}
}

//Ajouter du liquide à un élève
function add_cash()
{
	global $erreur;
	global $reponse;
	
	$erreur = AJAX_OK;t
	$reponse = "Le solde de l'élève a bien été augmenté";
}

//Annuler une commande
function cancel()
{
	global $erreur;
	global $reponse;
	
	$erreur = AJAX_OK;
	$reponse = "Commande annulée avec succès";
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


