<?php
define('page_titre', "Statistiques &bull; Caisse Foyer");

$head_HTML = '<script src="scripts/highcharts/js/highcharts.js"></script><script type="text/javascript" src="scripts/highcharts/js/themes/gray.js"></script><script type="text/javascript" src="scripts/admin.js"></script>';

include_once 'inclus/tete.html.php';
?>
  <div class="central bloc">
   <a href="./index.php" class="maison">Retour</a>
    <h1>Statistiques</h1>
    <div class="notif estompe">Vous pouvez ici consulter l'évolution de la consommations des différents produits depuis le début du logiciel.</div>
    <div class="darkbox moitie" id="container">
    </div>
<?php

// Affichage du graphe
$consommations = Array();

// Remplissage du graphe (tous types confondus)
$sql->rek( "SELECT DATE(SUBTIME(a.timestamp,'0 6:0:0')) as date,a.`qtte_produit`,CONCAT(b.nom,' ',b.vol,'L') as `nom_produit` FROM commandes as a, produits as b WHERE  a.id_produit = b.id GROUP BY a.id_produit,DATE(SUBTIME(a.timestamp,'0 6:0:0')) ORDER BY DATE(SUBTIME(a.timestamp,'0 6:0:0')) DESC, a.id_produit;" );
while($a = $sql->fetch()){
    // Si le produit n'est pas encore enregistré on l'enregistre
    if(!isset($consommations[$a['nom_produit']])) $consommations[$a['nom_produit']] = Array();
    // Et on ajoute le point du graphe correspondant
    $consommations[$a['nom_produit']][] = '[Date.UTC('.date('Y,m,d',strtotime($a['date'])).'),'.$a['qtte_produit'].']';
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
                text: 'Nombre de consommations'
            },
            min: 0
        },
        tooltip: {
	    dateTimeLabelFormats: {
                    month: '%b %e',
		    day: '%A %e %B',
                    year: '%b'
            }
        },
        series: [
        <?php
        $series = Array();
        foreach($consommations as $nom => $js_data) {
	    $nom = str_replace("'",'',$nom); // Pour empêcher l'injection JS
	    
            $series[] = "{ name: '$nom', data: [" . implode(',',$js_data) . ']}';
        }
        echo implode(',',$series)
        ?>
        
        ]
    });
</script>
<?php
include 'inclus/pied.html.php';