<?php
$erreur = 0;
$reponse = "All's right";

header('Content-type: application/json');  

if ((isset($_GET['id'])) && (isset($_GET['fb_id'])))
{

$url = 'https://graph.facebook.com/'.$_GET['fb_id'].'/picture?width=200&height=200';
$img = 'images/photos/'.$_GET['id'].'.jpg';
file_put_contents($img, file_get_contents($url));

	if (!$erreur)
	{
		?>
		{
			"code_erreur": "<?php echo $erreur; ?>",
			"reponse": "<?php echo $reponse;?>"
		}                                                         
		<?php
	}
}
else
{
	$erreur=101;
	$reponse='Id non défini';
}

if ($erreur)
{
?>
	{
		"code_erreur": "<?php echo $erreur; ?>",
		"reponse": "<?php echo $reponse;?>"
	}    
<?php
}
?>


