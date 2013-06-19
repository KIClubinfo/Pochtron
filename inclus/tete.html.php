<?php
include_once 'inclus/tete.inc.php';
?><!DOCTYPE html>
<html lang="fr">
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<meta charset="utf-8">
<base href="<?php echo _BASEHREF; ?>">
<link rel="stylesheet" href="style/style.css">
<link rel="stylesheet" href="style/responsive.css">
<script type="text/javascript" src="scripts/jquery-1.9.1.js"></script>
<meta name="viewport" content="user-scalable=no, initial-scale = 1, minimum-scale = 1, maximum-scale = 1, width=device-width">
<title><?php echo (defined('page_titre')) ? page_titre : _TITRE_PAR_DEFAUT; ?></title>
<link rel="shortcut icon" href="images/favicon.png">
<?php if(!empty($head_HTML)) echo $head_HTML; ?>
</head><body onKeyPress="if (event.keyCode == 13) enter_pressed()" >