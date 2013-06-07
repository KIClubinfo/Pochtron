<?php
// Déconnexion MySQL
$sql->deconnecte();
?>
<footer>
Logiciel réalisé par MB015 &amp; <small>CB015</small>, parce que c'était mieux avant !
</footer>
<?php
if((!empty($_SERVER['SCRIPT_URL']) and $_SERVER['SCRIPT_URL'] == '/gestionnaire.php')
  or (!empty($_SERVER['SCRIPT_NAME']) and $_SERVER['SCRIPT_NAME'] == '/gestionnaire.php')){
?>
<script src="scripts/scroll.js"></script>
<script type="text/javascript">
    $(document).ready(function()
	{
        $('.scroll_bar').tinyscrollbar();
		resize_boxes();
    });
</script>
<?php } ?>
</body>
</html>