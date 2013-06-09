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
	
	if (!isset($_GET['id']))
	{
		$erreur=UNDEFINED_ID;
		$reponse="Id indéfini";
	}
	
	
	$erreur = AJAX_OK;
	$reponse = "Commande passée avec succès pour l'élève dont l'id est : xx !";
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

//A ôter dès que les fonctions seront correctement implémentées
if ($erreur == AJAX_OK)
{
	$erreur = AJAX_NOT_IMPLEMENTED;
	$reponse = "Cette fonction n'a pas encore été implémentée !";
}

?>
{
	"code_erreur": "<?php echo $erreur; ?>",
	"reponse": "<?php echo $reponse;?>"
}                                                         


