<?php

define('page_titre', "Accueil &bull; Caisse Foyer");

include_once 'inclus/tete.html.php';
?>
  <div class="central bloc bienvenue">
    <h1>Gestion du bar du foyer</h1>
    <?php
    $sql->rek( "SELECT `val` FROM bar WHERE `id`='version'" );
    $version = '0.1 (au mieux)';
    if($sql->nbrlignes()==0 or ($version = $sql->resultat_ligne(0)) != _VERSION){
	echo '<div class="argh notif"><strong>Nomdidjiû !</strong>La base de donnée est actuellement à la version '.htmlentities($version,ENT_QUOTES,'utf-8').', alors que la version du logiciel est '._VERSION.'.<br>Veuillez contacter votre DSI préféré pour régler ce problème au plus vite !<br><span class="small">(qu\'il mette à jour le fichier php de configuration, ainsi que la base de données)</span></div>';
    }
    ?>
    <ul class="horizontal">
      <li><a href="gestionnaire.php">Gestion courante</a></li>
      <li><a href="administration.php">Administration</a></li>
     </ul>
    <ul class="horizontal">
      <li><a href="futs.php">Gestion des fûts</a></li>
      <li><a href="exportation.php">Exporter les données</a></li>
     </ul>
    <ul class="horizontal">
      <li><a href="stats.php">Statistiques</a></li>
      <li><a href="apropos.php">À Propos...</a></li>
     </ul>
  </div>
<?php
include 'inclus/pied.html.php';
?>