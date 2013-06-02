<?php
define('page_titre', "Authentification &bull; Caisse Foyer");

include_once 'inclus/tete.inc.php';

if(!empty($_SESSION['connexion'])
and !empty($_SESSION['t_connexion'])
and $_SESSION['connexion'] == _MDP_ADMIN
and time() - $_SESSION['t_connexion'] < _DELAI_ADMIN){
  header('Location: administration.php');
  exit();
}

ob_start();

$head_HTML = '<script type="text/javascript" src="scripts/admin.js"></script>';
include_once 'inclus/tete.html.php';

?>
  <div class="central bloc caisse">
   <a href="./index.php" class="maison">Retour</a>
    <h1>Identification</h1>
    <?php

if(!empty($_POST['passe'])){
  
  
    if(sha1($_POST['passe']) == _MDP_ADMIN){
	logue("Connexion à l'interface d'administration","connexion","info");
	$_SESSION['connexion'] = sha1($_POST['passe']);
	$_SESSION['t_connexion'] = time();
	ob_end_clean();
	header("Location: administration.php?connok=1");
	exit();
    }
    else{
	logue("Tentative échouée de connexion à l'interface d'administration","connexion","warn");
	?><div class="notif argh estompe"><strong>Échec</strong>C'est refusé mon gars, ce n'est pas le bon mot de passe !</div><?php
      }
}
else{
?><div class="notif warn">L'accès à la partie administrative est restreinte, il va falloir entrer un mot de passe !</div><?php
}
    ?>
      
    <form method="POST" action="connexion.php" class="bloc">
    <label for="mdp">Mot de passe</label><input autofocus type="password" name="passe" id="mdp"><br>
    <input type="submit" value="C'est parti" class="vert">
    </form>
   </div>
<?php
include 'inclus/pied.html.php';