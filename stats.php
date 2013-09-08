<?php
define('page_titre', "Statistiques &bull; Caisse Foyer");

$head_HTML = '<script src="scripts/highcharts/js/highcharts.js"></script><script type="text/javascript" src="scripts/highcharts/js/themes/gray.js"></script><script type="text/javascript" src="scripts/admin.js"></script>';

include_once 'inclus/tete.html.php';
?>
  <div class="central bloc">
   <a href="./index.php" class="maison">Retour</a>
    <h1>Statistiques</h1>
    <div class="notif estompe">Vous pouvez ici consulter l'évolution de la consommations des différents produits depuis le début du logiciel.</div>
    <div class="darkbox" id="consos"></div>
    <table class="lignes" id="bestdrunks">
    <caption><span>Meilleurs buveurs du foyer</span></caption>
    <thead>
    <tr><th>Rang</th><th>Nom</th><th>Litres ingérés</th></tr>
    </thead>
    <tbody>
    <?php
$sql->rek( "select CONCAT(prenom,' ',nom) as nom, promo, litres_bus from clients WHERE active='activé' ORDER BY litres_bus DESC LIMIT 10;" );
$r = 1;
while($a = $sql->fetch()){
    echo '<tr><td>'.($r++).'</td><td>'.$a['nom'].(empty($a['promo']) ? '':" {$a['promo']}]").'</td><td>'.$a['litres_bus'].'</td></tr>';
}
    ?>
    </tbody>
    </table>
    <table class="lignes" id="bestproducts">
    <caption><span>Meilleurs ventes du foyer</span></caption>
    <thead>
    <tr><th>Rang</th><th>Nom</th><th>Produits vendus</th></tr>
    </thead>
    <tbody>
    <?php
$sql->rek( "SELECT SUM(qtte) AS nb, nom FROM
(
	SELECT c.qtte_produit as qtte, CONCAT(p.nom,' ',p.vol,'L') AS nom
	FROM commandes as c, produits as p
	WHERE c.id_produit = p.id
	
	UNION ALL
	
	SELECT c.qtte_produit as qtte, CONCAT(p.nom,' ',p.vol,'L') AS nom
	FROM commandes_externes as c, produits as p
	WHERE c.id_produit = p.id

) t
GROUP BY nom ORDER BY nb DESC LIMIT 10;" );
$r = 1;
while($a = $sql->fetch()){
    echo '<tr><td>'.($r++).'</td><td>'.$a['nom'].'</td><td>'.$a['nb'].'</td></tr>';
}
    ?>
    </tbody>
    </table>
    <div class="darkbox" id="consos2"></div>
    </div>
<?php

// Affichage du graphe
$consommations = Array();
$litres = Array();
$dates = Array();

// Remplissage du graphe affichant l'évolution de chaque produit
//Pré-requète : nombre de dates à afficher (afin d'initialiser les tableaux avec des 0)
$sql->rek( "SELECT date
FROM (
	SELECT DATE(SUBTIME(timestamp,'0 6:0:0')) as date FROM commandes
	UNION ALL
	SELECT DATE(SUBTIME(timestamp,'0 6:0:0')) as date FROM commandes_externes
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
	WHERE  a.id_produit = b.id

    UNION ALL

    SELECT DATE(SUBTIME(a.timestamp,'0 6:0:0')) as date, a.qtte_produit as qtte_produit, CONCAT(b.nom,' ',b.vol,'L') as nom_produit, a.id_produit 
	FROM commandes_externes as a, produits as b 
	WHERE  a.id_produit = b.id
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
	WHERE c.id_produit = p.id
	
	UNION ALL
	SELECT p.vol * c.qtte_produit AS volume, DATE(SUBTIME(c.timestamp,'0 6:0:0')) AS date
	FROM commandes_externes AS c, produits AS p
	WHERE c.id_produit = p.id
	) T
GROUP BY date
ORDER BY date ASC;");

while($a = $sql->fetch()){
    // On ajoute le point du graphe correspondant
    $litres[] = '[Date.UTC('.date('Y,m,d',strtotime("-1 month", strtotime($a['date']))).'),'.$a['volume_tot'].']';
}


?>
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
            text: 'Ils pourraient pas tous boire de la kro, comme tout le monde ?',
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
	    $nom = str_replace("'",'',$nom); // Pour empêcher l'injection JS
	    
            $series[] = "{ name: '$nom', data: [" . implode(',',$js_data) . ']}';
        }
        echo implode(',',$series)
        ?>
        
        ]
    });
    
    
    $('#consos2').highcharts({
        chart: {
            type: 'spline'
        },
        title: {
            text: 'Nombre de litres de boisson ingérés',
            align: 'left',
            y: 5
        },
        subtitle: {
            text: 'La bière ça fait pisser',
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
	    dateTimeLabelFormats: {
                    month: '%b %e',
		    day: '%A %e %B',
                    year: '%b'
            }
        },
        series: [{ name: 'Volume ingéré', data: [<?php echo implode(',',$litres); ?>]}]
    });
</script>
<?php
include 'inclus/pied.html.php';