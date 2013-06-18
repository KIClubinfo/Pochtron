<?php
//Inclusions nécéssaires
$head_HTML = '<link rel="stylesheet" href="style/ui.css"><script type="text/javascript" src="scripts/progress.js"></script><script type="text/javascript" src="scripts/ajax.js"></script>';
include_once 'inclus/tete.html.php';
?>

	<div class="central bloc clients">
		<a href="./administration.php" class="maison">Retour</a>
		<h1>Synchronisation photos</h1>

		<div id="notifs"></div>
		<table class="clients lignes lignes-hover" id="clients">
			<thead>
				<tr><th></th><th>Photo actuelle</th><th>Prénom</th><th>Nom</th><th>Propositions photos</th><th>Valider changement</th></tr>
			</thead>
			<tfoot>
				<tr>
					<td colspan="6" id="results">
						<?php
						//Nombre d'élèves
						$sql->rek( 'SELECT `id` ,prenom,nom,solde,active,nb_consos FROM clients ORDER BY (solde<0) DESC, nom ASC' );
						echo '0/'.$sql->nbrlignes().'éléments trouvés'; ?>
					</td>
				</tr>
				<tr>
					<td colspan="6">
						<div id="progress_bar" class="ui-progress-bar ui-container">
							<div class="ui-progress" style="width: 79%;">
								<span class="ui-label" style="display:none;">Progression <b class="value">79%</b></span>
							</div>
						</div>
					</td>
				</tr>
			</tfoot>
			<tbody>
				<?php
					while($a = $sql->fetch())//Boucle d'affichage des lignes
					{
						if(file_exists("images/photos/".$a['id'].".jpg")) 
							$photo = $a['id'];
						else
							$photo = "sans_photo";
						?>
						<tr>
							<td></td>
							<td class="prec_tof"><img class='ico-client' src="images/photos/<?php echo $photo.'.jpg'; ?>" alt=''></td>
							<td><?php echo $a['prenom']; ?></td><td><?php echo $a['nom']; ?></td>
							<td class="new_tof" data-id="<?php echo $a['id']; ?>"></td><td class="checkbox"></td>
						</tr>
					<?php	
					} ?>

			</tbody>
		</table>
	</div>	

 <script type="text/javascript">

$(function() //Chargée au chargement
{ 
  $('#progress_bar .ui-progress .ui-label').hide();//On masque le label de la barre de progression
  $('#progress_bar .ui-progress').css('width', '0%');//On initialise la barre de progression à 0

}); 

//Intialisation de la barre de progression
var avancement = 0;
var nb_done = 0;
var nb_tot = <?php echo $sql->nbrlignes(); ?>;
var pas = 100/nb_tot;

//Fonction principale  Synchronisation des photos
$("#clients tr .new_tof" ).each(function (i) 
{
	search_pict($(this).attr("data-id"));	//Pour chaque élève, recherche d'une photo de profil FB.
}); 

//Progression & Rafrachissement de la barre de progression
function refresh_prog_bar()
{
	avancement+=pas;
	 $('#progress_bar .ui-progress').animateProgress(avancement, function() {});
}

//Fonction de callback après recherche (après réponse d'image_search.php)
function search_callback(data, GET_args)
{
	if (data.code_erreur == "0")
	{
		 $("#clients tr .new_tof[data-id=\""+GET_args['id']+"\"]").html('<img src="https://graph.facebook.com/'+data.reponse+'/picture" title="Nouvelle photo" />').siblings(".checkbox").html('<input type="checkbox" checked="checked" />');     
		 
		 nb_done++;
	}	 
	 
	 refresh_prog_bar();
	 
	 $("#results").text(nb_done+'/'+nb_tot+' éléments trouvés');
	 
	 if (avancement >= 99)
	 $('#progress_bar .ui-progress').animateProgress(avancement, function() 
	 {
		$('.ui-label').delay(1500).text('Clickez-ici pour télécharger les photos').css({'display':'block','cursor':'pointer'}).click(function(){
			upload_pict();
		});
	});
	
}

//Fonction de traitement d'erreur après recherche (après réponse d'image_search.php)
function search_error(data, GET_args)
{
	$("#clients tr .new_tof[data-id=\""+GET_args['id']+"\"]").html(data.reponse);
	refresh_prog_bar();
}

//Fonction de callback après upload (après réponse d'image_upload.php)
function upload_callback(data, GET_args)
{
	if (data.code_erreur == "0")
	{
		$("#clients tr .new_tof[data-id=\""+GET_args['id']+"\"]").siblings(".checkbox").html('<img src="images/icones/icons/accept.png" />');
		$("#clients tr .new_tof[data-id=\""+GET_args['id']+"\"]").siblings(".prec_tof").html('<img class="ico-client" src="images/photos/'+GET_args['id']+'.jpg" />');
								 
		nb_done++;
	}	 
	
	avancement+=pas;
	$('#progress_bar .ui-progress').animateProgress(avancement, function() {});
	 
	$("#results").text(nb_done+'/'+nb_tot+' éléments mis à jours');
}

//Fonction de traitement d'erreur après upload (après réponse d'image_upload.php)
function upload_error(data, GET_args)
{
	search_error(data, GET_args);
}

//Fonction de recherche d'image
function search_pict(my_id)
{
	var GET_args =  {'id' : my_id};//Arguments de la requète GET
	ajax_url("image_search.php", GET_args, search_callback, search_error);//Appel AJAX
}

//Fonction d'upload après recherche
function upload_pict()
{
	//Réinitialisation de la barre de progression
	avancement = 0;
	nb_tot = nb_done;
	nb_done = 0;
	pas=100/nb_tot;
	
	$('#progress_bar .ui-progress').css('width','0');
	$('.ui-label').css({'display':'block','cursor':'normal'}).unbind("click");

	
	$("#clients tr .checkbox").each(function (i) //Pour chaque élève
	{
		var my_user_id=$(this).siblings(".new_tof").attr("data-id");//On récupère l'id
		var check_box = this;
				
		if (($(this).html() != '') && ($("input",this).prop("checked") == true))//On vérifie que la check-box n'est pas cochée
		{
			var my_fb_id = $(this).siblings(".new_tof").children("img").attr("src").split('/')[3];//On récupère l'id FB
			
			var GET_args =  {'id' : my_user_id,  'fb_id':my_fb_id}; //Arguments de la requète GET
			ajax_url("image_upload.php", GET_args, upload_callback, upload_error);//Appel AJAX
		}
	});
}

</script>