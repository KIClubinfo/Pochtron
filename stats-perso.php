<?php
define('page_titre', "Statistiques personnelles &bull; Caisse Foyer");

$iduser = (empty($_GET['id'])) ? 1 : intval($_GET['id']);

$head_HTML = '<script src="scripts/highcharts/js/highcharts.js"></script><script src="scripts/highcharts/js/themes/gridgray.js"></script><script type="text/javascript" src="scripts/highcharts/js/themes/gray.js"></script><script type="text/javascript" src="scripts/admin.js"></script>';


include_once 'inclus/tete.html.php';

// Affichage du graphe
$consommations = Array();
$litres = Array();
$litres_cumules = Array();
$dates = Array();

// Remplissage du graphe affichant l'évolution de chaque produit
//Pré-requète : nombre de dates à afficher (afin d'initialiser les tableaux avec des 0)
$sql->rek( "SELECT date
FROM (
	SELECT DATE(SUBTIME(timestamp,'0 6:0:0')) as date FROM commandes where id_user = '$iduser'
) t
GROUP BY date;");

while($a = $sql->fetch())
{
	$dates[] = $a['date'];
}

//Récupération des stats à proprement parler
$sql->rek( "SELECT date, SUM(qtte_produit) as qtte_produit, nom_produit, id_produit
FROM (
    SELECT DATE(SUBTIME(a.timestamp,'0 6:0:0')) as date, a.qtte_produit as qtte_produit, CONCAT(b.nom,' ',b.vol,'L') as nom_produit, a.id_produit 
	FROM commandes as a, produits as b 
	WHERE  a.id_produit = b.id and id_user = '$iduser'
) as t
GROUP BY date, id_produit
ORDER BY date DESC,id_produit;" );


while($a = $sql->fetch()){

	$a['nom_produit'] = htmlspecialchars_decode($a['nom_produit'], ENT_QUOTES);
    // Si le produit n'est pas encore enregistré on l'enregistre
	if(!isset($consommations[$a['nom_produit']])) 
	{
		$consommations[$a['nom_produit']] = Array();
		for ($i = 0;$i<count($dates);$i++)//On remplit avec des 0
			$consommations[$a['nom_produit']][$dates[$i]] = '[Date.UTC('.date('Y,m,d',strtotime("-1 month", strtotime($dates[$i]))).'),0]';
	}
    // Et on ajoute le point du graphe correspondant
    $consommations[$a['nom_produit']][$a['date']] = '[Date.UTC('.date('Y,m,d',strtotime("-1 month", strtotime($a['date']))).'),'.$a['qtte_produit'].']';
}

// Remplissage du graphe (tous types confondus)
$sql->rek( "SELECT SUM(volume) as volume_tot, date
FROM (
	SELECT p.vol * c.qtte_produit AS volume, DATE(SUBTIME(c.timestamp,'0 6:0:0')) AS date
	FROM commandes AS c, produits AS p
	WHERE c.id_produit = p.id and id_user = '$iduser'
	) T
GROUP BY date
ORDER BY date ASC;");

$litres_cumules_tmp = 0;
while($a = $sql->fetch()){
    // On ajoute le point du graphe correspondant
    $litres[] = '[Date.UTC('.date('Y,m,d',strtotime("-1 month", strtotime($a['date']))).'),'.$a['volume_tot'].']';
    $litres_cumules_tmp += floatval($a['volume_tot']);
    $litres_cumules[] = '[Date.UTC('.date('Y,m,d',strtotime("-1 month", strtotime($a['date']))).'),'.$litres_cumules_tmp.']';
}

$max_consos = 0;
$best_conso = "Aucune";
// Remplissage du graphe
$sql->rek( "select produits.nom, sum(qtte_produit) as nb from commandes inner join produits on commandes.id_produit = produits.id where id_user='$iduser' group by id_produit limit 15;");

$lignes = Array();
while($a = $sql->fetch()){
    if($a['nb'] > $max_consos){
	$max_consos = $a['nb'];
	$best_conso = $a['nom'];
    }
    $a['nom'] = str_replace("'","\'",html_entity_decode($a['nom'],ENT_QUOTES,'utf-8'));
    // On ajoute la ligne
    $lignes[] = "['{$a['nom']}',{$a['nb']}]";
}

?>
  <div class="central bloc">
<?php
$sql->rek( "select prenom, nom from clients where id='$iduser';");

if($sql->nbrlignes() == 0){
  ?><div class="notif argh"><strong>Erreur</strong>Cette personne est introuvable !</div><?php
  include 'inclus/pied.html.php';
  exit(0);
}
else{
    $identuser = $sql->fetch();
}
?>
   <a href="./index.php" class="maison">Retour</a>
    <h1>Statistiques personnelles : <?php echo $identuser['prenom'].' '.$identuser['nom']; ?></h1>
    <div class="darkbox" id="consos"></div>
    <table class="lignes" id="stats-generales">
    <caption><span>Statistiques générales</span></caption>
    <?php
    
// Remplissage du graphe
$sql->rek( "select 'consos' as a,sum( qtte_produit ) as b from commandes where id_user='$iduser' union select 'solde',CONCAT(solde,' €') from clients where id='$iduser' union select 'litresbus', litres_bus from clients where id='$iduser';");

$titres = Array('consos' => 'Nombre de consos'
		,'solde' => 'Solde actuel'
		,'litresbus' => 'Litres ingérés');
		
while($a = $sql->fetch()){
    // On ajoute la ligne
    if($a['a'] == 'consos') $a['b'] = intval($a['b']);
    echo "<tr><th>{$titres[$a['a']]}</th></tr><tr><td>{$a['b']}</td></tr>";
}
echo "<tr><th>Boisson préférée</th></tr><tr><td>$best_conso ($max_consos)</td></tr>";
?>
<!--    <tr>
     <th>Classement général</th>
    </tr>
    <tr>
     <td>En attente...</td>
    </tr>-->
    </table>
    <div class="darkbox" id="repartition-produits"></div>
    <div class="darkbox" id="consos_empile"></div>
    <script>
    Highcharts.setOptions(Highcharts.theme_gray);
    Highcharts.setOptions({
	lang: {
		shortMonths: ['Jan.', 'Fév.', 'Mars', 'Avr.', 'Mai', 'Juin', 
			'Juil.', 'Août', 'Sept.', 'Oct.', 'Nov.', 'Déc.'],
		months: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 
			'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
		weekdays: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi']
	}
});
    $('#consos').highcharts({
        chart: {
            type: 'spline'
        },
        title: {
            text: 'Consommation par produits',
            align: 'left',
            y: 5
        },
        subtitle: {
            text: '',
            align: 'right',
            verticalAlign: 'top',
            y: 5
        },
        legend: {
//             enabled: false
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
	    
	    $nom = str_replace("'","\'",html_entity_decode($nom,ENT_QUOTES,'utf-8')); // Pour empêcher l'injection JS
	    
            $series[] = "{ name: '$nom', data: [" . implode(',',$js_data) . ']}';
        }
        echo implode(',',$series)
        ?>
        
        ]
    });
    
    Highcharts.getOptions().colors = Highcharts.map(Highcharts.getOptions().colors, function(color) {
	return {
	    radialGradient: { cx: 0.5, cy: 0.3, r: 0.7 },
	    stops: [
		[0, color],
		[1, Highcharts.Color(color).brighten(-0.3).get('rgb')] // darken
	    ]
	};
    });
    $('#repartition-produits').highcharts({
        chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: true
        },
        title: {
            text: 'Vos consommations',
            align: 'left',
            y: 5
        },
        subtitle: {
            text: 'Dis-moi ce que tu bois, je te dirai qui tu es...',
            align: 'right',
            verticalAlign: 'top',
            y: 5
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
                text: 'Volume en Litres'
            },
            min: 0
        },
	tooltip: {
		pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
	},
	plotOptions: {
	    pie: {
		allowPointSelect: true,
		cursor: 'pointer',
		dataLabels: {
		    color: '#FDFDFF',
		    connectorColor: '#E8E8E9',
		    formatter: function() {
			return '<b>'+ this.point.name +'</b> : '+ this.y;
		    }
		}
	    }
	},
        series: [{ type: 'pie', name: 'Consos', data: [<?php
        
echo implode(',',$lignes);
        ?>]}]
    });
    
    var highchartsOptions = Highcharts.setOptions(Highcharts.theme_gridgray);
    $('#consos_empile').highcharts({
        chart: {
            type: 'area'
        },
        title: {
            text: 'Nombre de litres de boisson ingérés (cumulés)',
            align: 'left',
            y: 5
        },
        subtitle: {
            text: 'Tss...',
            align: 'right',
            verticalAlign: 'top',
            y: 5
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
                text: 'Volume en Litres'
            },
            min: 0
        },
        tooltip: {
            pointFormat: 'Volume vendu : <strong>{point.y:,.0f}L</strong>',
            dateTimeLabelFormats: {
                    month: '%b %e',
            day: '%A %e %B',
                    year: '%b'
            }
        },
        series: [{ name: 'Volume ingéré', data: [<?php echo implode(',',$litres_cumules); ?>]}]
    });
</script>
<?php
include 'inclus/pied.html.php';