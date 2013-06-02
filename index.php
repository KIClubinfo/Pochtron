<?php

define('page_titre', "Accueil &bull; Caisse Foyer");

include_once 'inclus/tete.html.php';
?>
  <div class="central bloc bienvenue">
    <h1>Gestion du bar du foyer</h1>
    <ul class="horizontal">
      <li><a href="gestionnaire.php">Gestion courante</a></li>
      <li><a href="administration.php">Administration</a></li>
     </ul>
    <ul class="horizontal">
      <li><a href="exportation.php">Exporter les données</a></li>
      <li><a href="apropos.php">À Propos...</a></li>
     </ul>
  </div>
<?php
include 'inclus/pied.html.php';
?>