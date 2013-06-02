<?php

 header('Content-type: application/json'); 

require_once("scripts/facebook.php");
include_once 'inclus/tete.inc.php';

function sa($string)
{
	return str_replace( array('à','á','â','ã','ä', 'ç', 'è','é','ê','ë', 'ì','í','î','ï', 'ñ', 'ò','ó','ô','õ','ö', 'ù','ú','û','ü', 'ý','ÿ', 'À','Á','Â','Ã','Ä', 'Ç', 'È','É','Ê','Ë', 'Ì','Í','Î','Ï', 'Ñ', 'Ò','Ó','Ô','Õ','Ö', 'Ù','Ú','Û','Ü', 'Ý'), array('a','a','a','a','a', 'c', 'e','e','e','e', 'i','i','i','i', 'n', 'o','o','o','o','o', 'u','u','u','u', 'y','y', 'A','A','A','A','A', 'C', 'E','E','E','E', 'I','I','I','I', 'N', 'O','O','O','O','O', 'U','U','U','U', 'Y'), $string); 	
}

$config = array();
$config['appId'] = '326367797490340';
$config['secret'] = '420680b82af60aaeb0be9335bd8b6f1b';

$facebook = new Facebook($config);
$access_token = 'CAAEo1GyisqQBAH9dsIj3wDypcgySxQmQgZAJ3K0RMidsvYL0LU1Xqtzvs58f6AbHvvI9PB4Cc53jqJMiPMHR4UZBKb2SmfQCgplpFV4WCMM6OVGzbRqmpl1ZCllNqPSdLB0y8TLf7Q4b48E6N2D';

function search_in_friends($fullname, $id, $friend_id)
{
	global $facebook;
	global $access_token;
	
	
	$friends = $facebook->api('/'.$friend_id.'/friends',array('access_token'=>$access_token));
	
	for ($i=0;$i < count($friends['data']); $i++)
	{		
		if ($friends['data'][$i]['name'] == $fullname)
		{
			return $friends['data'][$i]['id'];
		}
	}
	
	
	return 0;
}

function search_in_groups($fullname, $id, $group_id)
{
	global $facebook;
	global $access_token;
	
	$graph_url = "https://graph.facebook.com/$group_id/members?access_token=$access_token";
	$results = file_get_contents( $graph_url );
	$json = json_decode($results);
						
	foreach( $json->data as $show ) 
	{
		if ($show->name == $fullname)
			return $show->id;
	}
	
	return 0;
}

$erreur=0;

if (isset($_GET['id']))
{
	$sql->rek( 'SELECT `id` ,prenom,nom FROM clients WHERE id = '.$_GET['id'].' ORDER BY nom ASC');
	$reponse = '';
	while($a = $sql->fetch())
	{
		$fullname = $a['prenom']." ".$a['nom'];
						
		try 
		{
			$pict_groups = search_in_groups($fullname, $a['id'],'470254749665448');
			
			if ($pict_groups)
			{
				$reponse = $pict_groups;
				continue;
			}
			else
			{
				$pict_friends = search_in_friends($fullname, $a['id'],'me');
			
				if ($pict_friends)
				{
					$reponse = $pict_friends;
					continue;
				}
				else
					$erreur = 102;
			}
			
		}
		catch (FacebookApiException $e) 
		{
			error_log($e);
			$fbUser = null;
		}
	}
	
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