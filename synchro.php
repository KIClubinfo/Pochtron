<?php
//Inclusions nécéssaires
$head_HTML = '<link rel="stylesheet" href="style/ui.css"><script type="text/javascript" src="scripts/progress.js"></script>';
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

//Parcours des élèves et recherche de photo
$("#clients tr .new_tof" ).each(function (i) 
{
	search_pict($(this).attr("data-id"));	
}); 


function ajax_url(my_url, GET_arg, callback_function, error_function)
{
	$.ajax({
		url: my_url,
        data   : {id:GET_arg['id'],fb_id:GET_arg['fb_id']},
          cache: false,
          dataType: "json",
        error : function(request, error) { error_function(GET_arg['id']);   },
        success: function(data) { callback_function(GET_arg['id'], data.code_erreur, data.reponse); }
		   });
	  
}

function upload_error(id)
{
	search_error(id);
}

function search_error(id)
{
	$("#clients tr .new_tof[data-id=\""+id+"\"]").html('error');
	refresh_prog_bar();
}

function refresh_prog_bar()
{
	avancement+=pas;
	 $('#progress_bar .ui-progress').animateProgress(avancement, function() {});
}

function search_callback(id, code_erreur, reponse)
{
	if (code_erreur == "0")
	{
		 $("#clients tr .new_tof[data-id=\""+id+"\"]").html('<img src="https://graph.facebook.com/'+reponse+'/picture" title="Nouvelle photo" />').siblings(".checkbox").html('<input type="checkbox" checked="checked" />');     
		 
		 nb_done++;
	}	 
	 
	 refresh_prog_bar();
	 
	 $("#results").text(nb_done+'/'+nb_tot+' éléments trouvés');
	 
	 if (avancement >= 100)
	 $('#progress_bar .ui-progress').animateProgress(avancement, function() 
	 {
		$('.ui-label').delay(1500).text('Clickez-ici pour télécharger les photos').css({'display':'block','cursor':'pointer'}).click(function(){
			upload_pict();
		});
	});
	
}

function upload_callback(id, code_erreur, reponse)
{
	if (code_erreur == "0")
	{
		$("#clients tr .new_tof[data-id=\""+id+"\"]").siblings(".checkbox").html('<img src="images/icones/icons/accept.png" />');
		$("#clients tr .new_tof[data-id=\""+id+"\"]").siblings(".prec_tof").html('<img class="ico-client" src="images/photos/'+id+'.jpg" />');
								 
		nb_done++;
	}	 
	
	avancement+=pas;
	$('#progress_bar .ui-progress').animateProgress(avancement, function() {});
	 
	$("#results").text(nb_done+'/'+nb_tot+' éléments mis à jours');
}

function upload_pict()
{
	
	avancement = 0;
	nb_tot = nb_done;
	nb_done = 0;
	pas=100/nb_tot;

	$('#progress_bar .ui-progress').css('width','0');
	$('.ui-label').css({'display':'block','cursor':'normal'}).unbind("click");

	$("#clients tr .checkbox").each(function (i) 
	{
		var my_user_id=$(this).siblings(".new_tof").attr("data-id");
		var check_box = this;
				
		if (($(this).html() != '') && ($("input",this).prop("checked") == true))
		{
			var my_fb_id = $(this).siblings(".new_tof").children("img").attr("src").split('/')[3];
			
			var GET_arg = new Array();
			GET_arg['id']=my_user_id;
			GET_arg['fb_id']=my_fb_id;
			ajax_url("image_upload.php", GET_arg, upload_callback, upload_error);
		}
	});
}

function search_pict(my_id)
{
	var GET_arg = new Array();
	GET_arg['id']=my_id;
	ajax_url("image_search.php", GET_arg, search_callback, search_error);
}


</script>