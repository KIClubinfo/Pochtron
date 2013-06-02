<?php

 //if ($_GET['action'] == "ChercherDansMaBaseDeDonnees") { ChercherDansMaBaseDeDonnees(); }

	$erreur = 0;
	$reponse = "Commande passée avec succès pour l'élève dont l'id est : ".$_GET['id']." !";
 


  header('Content-type: application/json');   
  ?>
  {
		"code_erreur": "<?php echo $erreur; ?>",
		"reponse": "<?php echo $reponse;?>"
  }                                                         


