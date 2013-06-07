<?php
define('AJAX_OK',0);
define('AJAX_UNKNOW_FAIL',1);
define('AJAX_NOT_IMPLEMENTED',2);

 //if ($_GET['action'] == "ChercherDansMaBaseDeDonnees") { ChercherDansMaBaseDeDonnees(); }

	$erreur = 0;
	$reponse = "Commande passée avec succès pour l'élève dont l'id est : ".$_GET['id']." !";
 
$erreur = AJAX_NOT_IMPLEMENTED;
$reponse = "Cette fonction n'a pas encore été implémentée !";

  header('Content-type: application/json');   
  ?>
  {
		"code_erreur": "<?php echo $erreur; ?>",
		"reponse": "<?php echo $reponse;?>"
  }                                                         


