<?php
define('page_titre', "Gestion des fûts &bull; Caisse Foyer");

$head_HTML = '<script src="scripts/highcharts/js/highcharts.js"></script><script type="text/javascript" src="scripts/highcharts/js/themes/gray.js"></script>';

include_once 'inclus/tete.html.php';

$notif = '<div class="notif estompe">Merci de signaler ici chaque fût sorti de la torche :)</div>';

if(!empty($_GET['rajouter']) and !empty($_POST['nb']) and !empty($_POST['type']) and isset($_POST['respo'])){
    // Sécurisation des données
    $_POST['nb'] = intval($_POST['nb']);
    $_POST['type'] = $sql->secur(htmlentities($_POST['type'],ENT_QUOTES,'utf-8'));
    $_POST['respo'] = $sql->secur(htmlentities($_POST['respo'],ENT_QUOTES,'utf-8'));
    // Requête
    $sql->rek( "INSERT INTO futs (`nb` ,`type` ,`respo`) VALUES ('{$_POST['nb']}','{$_POST['type']}','{$_POST['respo']}');" );
    if($sql->nbrchangements() != 1){
	logue("Impossible d'enregistrer un fût ! [INSERT INTO futs (`nb` ,`type` ,`respo`) VALUES ('{$_POST['nb']}','{$_POST['type']}','{$_POST['respo']}')]");
	$notif.='<div class="notif argh"><strong>Erreur</strong>Impossible d\'enregistrer ce fût !! Prends un stylo et écris sur le mur...</div>';
    }
    else{
	logue("{$_POST['nb']} fût(s) de {$_POST['type']} ajouté par {$_POST['respo']}.");
	header("Location: futs.php?ok=1");
	exit();
    }
}

if(!empty($_GET['ok']))
    $notif.='<div class="notif yeah estompe"><strong>All right, boy !</strong>C\'est bon, tu peux rincer ces assoifés la conscience tranquille...</div>';
?>
  <div class="central bloc">
   <a href="./index.php" class="maison">Retour</a>
    <h1>Gestion des fûts</h1>
    <?php echo $notif; ?>
    <div class="ajout-futs">
    <form method="POST" action="futs.php?rajouter=1" class="darkbox moitie">
    <label for="nb">Nombre de fûts</label>
    <input type="number" value="1" step="1" min="0" name="nb" id="nb">
    <br>
    <label for="type">Type de fût</label>
    <input type="text" value="Kro (standard)" name="type" id="type">
    <br>
    <label for="respo">Transporteur</label>
    <input type="text" placeholder="Qui es-tu ?" name="respo" id="respo">
    <br>
    <input type="submit" value="Enregistrer" class="gros vert">
    </form>
    <div class="darkbox moitie txt-fut" id="container">
    </div></div>
    
    <table class="lignes"><thead><tr><th>Date</th><th>Type</th><th>Nombre</th><th>Par</th></tr></thead><tbody>
<?php

// Affichage du graphe
$js_data = Array();

$d_limit = (isset($_GET['affiche_tous'])) ?  '' : ' AND TO_DAYS(NOW()) - TO_DAYS(date) <= 90';

$sql->rek( "SELECT date, nb, respo, type FROM futs WHERE nb > 0$d_limit ORDER BY date DESC" );

while($a = $sql->fetch()){
  $t = strtotime($a['date']);
  $date = date('d/m/y (H:i)',$t);
  if(empty($a['respo']))
    $respo = "<td class='grise'>Non renseigné...</td>";
  else
    $respo = "<td>{$a['respo']}</td>";
  
  echo "<tr><td><time>$date</time></td><td>{$a['type']}</td><td>{$a['nb']}</td>$respo</tr>";
  
//   $t -= _NBH_SESSION * 3600; // Pour avoir un foyer sur un seul jour
//   $js_data[] = '[Date.UTC('.date('Y,m,d',$t).'),'.$a['nb'].']';
}
echo '</tbody></table>';

// Remplissage du graphe (tous types confondus)
$sql->rek( "SELECT min(date) as date, sum(nb) as nb FROM futs WHERE nb > 0$d_limit GROUP BY DATE(SUBTIME(date,'0 6:0:0'))" );
while($a = $sql->fetch()){
  $js_data[] = '[Date.UTC('.date('Y,m,d',strtotime($a['date'])).'),'.$a['nb'].']';
//   echo "<br>Trouvé {$a['nb']} fûts pour le ".date('Y,m,d',strtotime($a['date']));
}
?>
  </div>
    <script>
    Highcharts.setOptions({
	lang: {
		shortMonths: ['Jan.', 'Fév.', 'Mars', 'Avr.', 'Mai', 'Juin', 
			'Juil.', 'Août', 'Sept.', 'Oct.', 'Nov.', 'Déc.'],
		months: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 
			'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
		weekdays: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi']
	}
});
    $('#container').highcharts({
        chart: {
            type: 'spline'
        },
        title: {
            text: ''
        },
        subtitle: {
            text: ''
        },
        legend: {
            enabled: false
        },
        xAxis: {
	    type: 'datetime',
	    dateTimeLabelFormats: {
                    month: '%b %e',
                    year: '%b'
                }
        },
        yAxis: {
            title: {
                text: 'Nombre de futs'
            },
            min: 0
        },
        series: [{
            name: 'Volume consommé',
            data: [<?php echo implode(',',$js_data); ?>]
        }]
    });
</script>
<?php
include 'inclus/pied.html.php';