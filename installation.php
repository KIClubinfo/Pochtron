<!DOCTYPE html>
<html lang="fr">
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<meta charset="utf-8">
<link rel="stylesheet" href="style/style.css">
<meta name="viewport" content="user-scalable=no, initial-scale = 1, minimum-scale = 1, maximum-scale = 1, width=device-width">
<title>Installation</title>
<link rel="shortcut icon" href="images/favicon.png">
</head><body>
<div class="central bloc bienvenue">
    <h1>Installation du logiciel</h1>
    <h2>Prérequis</h2>
    <ol>
      <li>Un serveur HTTP (Apache de préférence)</li>
      <li>Une base de données (MySQL)</li>
      <!-- <li>La capacité d'envoyer des courriels</li> -->
    </ol>
    <h2>Installation</h2>
    <ol><li>Copier l'intégralité du dossier dans le dossier racine du serveur.</li>
    <li>Créez un utilisateur pour la base de données, et une base de données sur lequel il a tous les droits</li>
    <li>Exécuter le <a href="installation.sql">code SQL d'installation</a> sur le serveur MySQL.</li>
    </ol>
    <h2>Configuration</h2>
    Une fois que tout est copié, renommez config.modele.php en config.inc.php et modifiez les valeurs pour refléter la configuration du serveur utilisé.
  </div>
<footer>
Logiciel réalisé par <big>MB015</big> &amp; <small>CB015</small>, parce que c'était mieux avant !
</footer>
</body>
</html>