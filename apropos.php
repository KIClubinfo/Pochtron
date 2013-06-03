<?php

define('page_titre', "À propos de ce logiciel... &bull; Caisse Foyer");

include_once 'inclus/tete.html.php';
?>
  <div class="central bloc stocks apropos">
   <a href="./index.php" class="maison">Retour</a>
    <h1>À propos de ce logiciel...</h1>
    <div class="bloc">
    Logiciel réalisé pour le foyer de l'École des Ponts, par <strong>Mickaël Bergem</strong> et <strong>Charles Bochet</strong> (promo 015) pendant leur stage chiant.
    </div>
    <div class="bloc">
    <div class="logo-foyer"><img src="images/logo_foyer.png" alt=""><strong class="version">Version <?php echo _VERSION; ?></strong></div>
    </div>
  </div>
<?php
include 'inclus/pied.html.php';
?>