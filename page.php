<!DOCTYPE html>

<?php include_once 'inclus/tete.inc.php'; ?>

<html lang="fr">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8">
		<meta charset="utf-8">

		<link rel="stylesheet" href="style/style.css">
		<link rel="stylesheet" href="style/responsive.css">


		<title></title>
		<link rel="shortcut icon" href="images/favicon.png">
	</head>
	<body>
		<div id="hello">
			Bonjour
		</div>

<?php

	
	for ($a=0;$a<100;$a++)
	{
		if(file_exists("images/photos/".$a.".jpg"))
		{
		?>
			<img src="./images/photos/<?php echo $a; ?>.jpg" />
		<?php
		}
		else
		{
			
		}
	}

	$sql->rek( 'SELECT `id` ,prenom,nom,solde,active,nb_consos FROM clients ORDER BY nom ASC' );
	echo '0/'.$sql->nbrlignes().'éléments trouvés';
?>
		
 <script type="text/javascript">
	//alert('bonjour');
</script> 

	</body>
</html>