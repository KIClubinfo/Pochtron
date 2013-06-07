<?php
//Application JSON
header('Content-type: application/json'); 

//Include pour l'objet $bdd notamment
include_once 'inclus/tete.inc.php'; 

//Initialisation
$erreur = -1;
$reponse = '';

/*/////////////////////////////UPLOAD/////////////////////////////*/

if ((isset($_GET['id'])) && (isset($_GET['fb_id'])))
{
	$sql->rek( 'SELECT `id` ,prenom,nom FROM clients WHERE id = '.$_GET['id'].' ORDER BY nom ASC');//On crée la requete
	
	while($a = $sql->fetch())//On la parcourt. A priori, y en a qu'un :p)
	{
		$url = 'https://graph.facebook.com/'.$_GET['fb_id'].'/picture?width=200&height=200';
		$img = 'images/photos/'.$_GET['id'].'.jpg';
		
		if (@file_put_contents($img, file_get_contents($url)))//Transfert de fichier
		{
			$erreur = 0;
			$reponse = "Mignature mise à jour";
		}
		else
		{
			$erreur = 100;
			$reponse = "Impossible de se connecter à internet ou erreur interne";
		}
	}
	
	if ($erreur==-1)
	{
		$erreur=201;
		$reponse="Ids non valides";
	}

}
else
{
	$erreur=200;
	$reponse='Id non défini';
}

/*/////////////////////////////AFFICHAGE DE LA REPONSE/////////////////////////////*/

if ($erreur==-1)
{
?>
{
	"code_erreur": "<?php echo $erreur; ?>",
	"reponse": "<?php echo $reponse;?>"
}    
<?php
}
?>


