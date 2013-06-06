<?php

 header('Content-type: application/json'); 

include_once 'inclus/tete.inc.php';

function sa($string)
{
	return str_replace( array('à','á','â','ã','ä', 'ç', 'è','é','ê','ë', 'ì','í','î','ï', 'ñ', 'ò','ó','ô','õ','ö', 'ù','ú','û','ü', 'ý','ÿ', 'À','Á','Â','Ã','Ä', 'Ç', 'È','É','Ê','Ë', 'Ì','Í','Î','Ï', 'Ñ', 'Ò','Ó','Ô','Õ','Ö', 'Ù','Ú','Û','Ü', 'Ý'), array('a','a','a','a','a', 'c', 'e','e','e','e', 'i','i','i','i', 'n', 'o','o','o','o','o', 'u','u','u','u', 'y','y', 'A','A','A','A','A', 'C', 'E','E','E','E', 'I','I','I','I', 'N', 'O','O','O','O','O', 'U','U','U','U', 'Y'), $string); 	
}

$config = array();
$config['appId'] = '326367797490340';
$config['secret'] = '420680b82af60aaeb0be9335bd8b6f1b';

if (!$fp = fopen("scripts/facebook/active_access_token","r")) 
{
	echo "Echec de l'ouverture du fichier";
}
else
{
	while(!feof($fp)) 
	{
		$config['old_access_token']= fgets($fp,255);
	}
	fclose($fp);
}



$req_new_access_token = "https://graph.facebook.com/oauth/access_token?client_id=".$config['appId']."&client_secret=".$config['secret']."&grant_type=fb_exchange_token&fb_exchange_token=".$config['old_access_token'];
$resp = file_get_contents($req_new_access_token);
parse_str($resp,$output);
$access_token = $output['access_token'];

function search_in_friends($fullname, $friend_id)
{
	global $access_token;
		
	$graph_url = "https://graph.facebook.com/".$friend_id."/friends?access_token=$access_token";
	$id = search_on_facebook($graph_url, $fullname);
	
	return $id;
}

function search_in_groups($fullname, $group_id)
{
	global $access_token;
	
	$graph_url = "https://graph.facebook.com/$group_id/members?access_token=$access_token";
	$id = search_on_facebook($graph_url, $fullname);
	
	return $id;
}

function search_on_facebook($graph_url, $fullname)
{
	
	$results = file_get_contents($graph_url);
	$json = json_decode($results);

	foreach( $json->data as $show ) 
	{
		if (strtolower($show->name) == strtolower($fullname))
		{
			return $show->id;
		}
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
			$pict_groups = search_in_groups($fullname,'470254749665448');
			
			if ($pict_groups)
			{
				$reponse = $pict_groups;
				continue;
			}
			else
			{
				$pict_friends = search_in_friends($fullname,'me');
			
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