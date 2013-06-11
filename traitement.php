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
define('BAD_REQUEST',300);

//Initialisations
$erreur=-1;
$reponse='';

//Include pour l'objet $bdd notamment
include_once 'inclus/tete.inc.php';

/*/////////////////////////////FONCTIONS/////////////////////////////*/
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
		
		($sql->rek( 'UPDATE clients SET solde=\''.($eleve['solde']-1).'\' WHERE id=\''.$_GET['id'].'\''))
		
		$erreur = AJAX_OK;
		$reponse = "Commande de ".$eleve['prenom']." ".$eleve['nom']." passée avec succès. Nouveau solde : ".($eleve['solde']-1);
		
	
	}
}

//Ajouter du liquide à un élève
function add_cash()
{
	global $erreur;
	global $reponse;
	
	$erreur = AJAX_OK;
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


