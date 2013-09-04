<?php
//Application JSON
header('Content-type: application/json'); 

//Include pour l'objet $bdd notamment
include_once 'inclus/tete.inc.php';

/*/////////////////////////////FONCTIONS/////////////////////////////*/

//Fonction annexe : suppression des accents
function sa($string)
{
	return str_replace( array('à','á','â','ã','ä', 'ç', 'è','é','ê','ë', 'ì','í','î','ï', 'ñ', 'ò','ó','ô','õ','ö', 'ù','ú','û','ü', 'ý','ÿ', 'À','Á','Â','Ã','Ä', 'Ç', 'È','É','Ê','Ë', 'Ì','Í','Î','Ï', 'Ñ', 'Ò','Ó','Ô','Õ','Ö', 'Ù','Ú','Û','Ü', 'Ý'), array('a','a','a','a','a', 'c', 'e','e','e','e', 'i','i','i','i', 'n', 'o','o','o','o','o', 'u','u','u','u', 'y','y', 'A','A','A','A','A', 'C', 'E','E','E','E', 'I','I','I','I', 'N', 'O','O','O','O','O', 'U','U','U','U', 'Y'), $string); 	
}

//Cherche une personne dans à une url donnée de l'API Facebook.
function search_on_facebook($graph_url, $fullname)
{
	
	if(!$results = @file_get_contents($graph_url))//Requete
	{
		$erreur=101;
		$reponse="Erreur lors de la connexion à FB";
		return 0;
	}
	
	$json = json_decode($results);//Parse

	foreach( $json->data as $show ) 
	{
		if (sa(strtolower($show->name)) == sa(strtolower($fullname)))//Recherche
		{
			return $show->id;
		}
	}
	return 0;
}

//Recherche une personne dans les amis de $friend_id
function search_in_friends($fullname, $friend_id)
{
	global $access_token;
		
	$graph_url = "https://graph.facebook.com/".$friend_id."/friends?access_token=$access_token";//Création de l'url
	$id = search_on_facebook($graph_url, $fullname);//Recherche sur FB
	
	return $id;
}

//Recherche une personne dans les membres du $group_id
function search_in_groups($fullname, $group_id)
{
	global $access_token;
	
	$graph_url = "https://graph.facebook.com/$group_id/members?access_token=$access_token";//Création de l'url
	$id = search_on_facebook($graph_url, $fullname);//Recherche sur FB
	
	return $id;
}



/*/////////////////////////////CONFIGURATION/////////////////////////////*/

//Configuration
$config = array();
$config['appId'] = '326367797490340';//Id de l'application
$config['secret'] = '420680b82af60aaeb0be9335bd8b6f1b';//Code secret de l'application

//Par défaut, pas d'erreur
$erreur=-1;

//Pour utiliser l'API de facebook, il est nécéssaire d'avoir un access_token (une sorte d'id de session) relatif à un compte FB (le mien pour le moment).
//Il se périme au bout de 60 jours. Dans le doute je le renouvelle à chaque fois.

//Afin de le renouveller, il faut l'"échanger" à partir de l'ancien. Donc on le récupère.
if (!$fp = fopen("scripts/facebook/active_access_token","r")) //Ouverture du fichier contenant le token.
{
	$erreur=100;
	$reponse= "Erreur lors du chargement de ./scripts/facebook/active_access_token";
}
else
{
	while(!feof($fp)) 
	{
		$config['old_access_token']= fgets($fp,255);//Récupération à proprement parler
	}
	fclose($fp);//Fermeture
}

//Renouvellement de l'access_token
$req_new_access_token = "https://graph.facebook.com/oauth/access_token?client_id=".$config['appId']."&client_secret=".$config['secret']."&grant_type=fb_exchange_token&fb_exchange_token=".$config['old_access_token'];

if (!$resp = @file_get_contents($req_new_access_token))
{
	$erreur=101;
	$reponse= "Erreur lors de la connexion à FB";
}
else
{
	parse_str($resp,$output);
	$access_token = $output['access_token'];//C'est fait.
}


/*/////////////////////////////RECHERCHE/////////////////////////////*/

if ($erreur==-1) //On vérifie que tout s'est bien déroulé avant
{
	if (isset($_GET['id']))//Que l'id est bien défini
	{
		$_GET['id'] = intval($_GET['id']); // Empêche l'injection SQL
		$sql->rek( 'SELECT `id` ,prenom,nom FROM clients WHERE id = \''.$_GET['id'].'\' ORDER BY nom ASC');//On crée la requete
		$reponse = '';
		
		while($a = $sql->fetch())//On la parcourt. A priori, y en a qu'un :p)
		{
			$fullname = $a['prenom']." ".$a['nom'];
							
			$pict_groups = search_in_groups($fullname,'470254749665448');//Première tentative : recherche dans le groupe KOWLANT'WEI
			
			if ($pict_groups)
			{
				$erreur=0;
				$reponse = $pict_groups;
				continue;
			}
			else
			{
				$pict_groups = search_in_groups($fullname,'359646667495742');//Deuxième tentative : recherche dans le groupe WEI'T SPIRIT
			
				if ($pict_groups)
				{
					$erreur=0;
					$reponse = $pict_groups;
					continue;
				}
				else
				{
					$pict_friends = search_in_friends($fullname,'me');//Troisième tentative : recherche dans les amis de Charles BOCHET
				
					if ($pict_friends)
					{
						$erreur = 0;
						$reponse = $pict_friends;
						continue;
					}
					else
					{
						$erreur = 300;
						$reponse = "Aucun résultat. Désolé";
					}
				}
			}
		}
		
		if ($erreur==-1)
		{
			$erreur = 201;
			$reponse = "Id invalide";
		}
		
	}
	else
	{ 
		$erreur=200;
		$reponse='Id non défini';
	}
}


/*/////////////////////////////AFFICHAGE DES ERREURS/////////////////////////////*/


?>
{
	"code_erreur": "<?php echo $erreur; ?>",
	"reponse": "<?php echo $reponse;?>"
}    
