<?php
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

						$sql->rek( 'SELECT `id` ,prenom,nom,solde,active,nb_consos FROM clients ORDER BY (solde<0) DESC, nom ASC' );
						echo '0/'.$sql->nbrlignes().'éléments trouvés'; ?>
					</td>
				</tr>
				<tr>
					<td colspan="6">
						<div id="progress_bar" class="ui-progress-bar ui-container">
							<div class="ui-progress" style="width: 79%;">
								<span class="ui-label" style="display:none;">Processing <b class="value">79%</b></span>
							</div><!-- .ui-progress -->
						</div>
					</td>
				</tr>
			</tfoot>
			<tbody>
				<?php
				
					while($a = $sql->fetch())
					{
						if(file_exists("images/photos/".$a['id'].".jpg")) 
							$photo = $a['id'];
						else
							$photo = "sans_photo";
						?>
						<tr><td></td><td class="prec_tof"><img class='ico-client' src="images/photos/<?php echo $photo.'.jpg'; ?>" alt=''></td><td><?php echo $a['prenom']; ?></td><td><?php echo $a['nom']; ?></td>
							
						<?php
						

						echo '<td class="new_tof" data-id="'.$a['id'].'"></td><td class="checkbox"></td></tr>';
						
					}
				
				 ?>
			</tbody>
		</table>
	</div>	

 <script type="text/javascript">

var avancement = 0;
var nb_found = 0;
var nb_tof = <?php echo $sql->nbrlignes(); ?>;
var pas = 100/nb_tof;

$("#clients tr .new_tof" ).each(function (i) 
{
	search_pict($(this).attr("data-id"));	
}); 

function upload_pict()
{
	var upload_avancement = 0;
	var nb_uploaded = 0;
	var nb_to_upload = nb_found;
	var upload_pas = 100/nb_to_upload;
	
	$('#progress_bar .ui-progress').css('width','0');
	$('.ui-label').css({'display':'block','cursor':'normal'}).unbind("click");

	$("#clients tr .checkbox").each(function (i) 
	{
		var my_user_id=$(this).siblings(".new_tof").attr("data-id");
		var check_box = this;
				
		if (($(this).html() != '') && ($("input",this).prop("checked") == true))
		{
			var my_fb_id = $(this).siblings(".new_tof").children("img").attr("src").split('/')[3];
			$.ajax({
				url: "image_upload.php",
				data   : {id:my_user_id,fb_id:my_fb_id},
				  cache: false,
				  dataType: "json",
				error : function(request, error) {          
						 $("#clients tr .new_tof[data-id=\""+my_id+"\"]").html('error');
						},
				  success: function(data) {    
					
							if (data.code_erreur == "0")
							{
								  $("#clients tr .new_tof[data-id=\""+my_user_id+"\"]").siblings(".checkbox").html('<img src="images/icones/icons/accept.png" />');
								  $("#clients tr .new_tof[data-id=\""+my_user_id+"\"]").siblings(".prec_tof").html('<img class="ico-client" src="images/photos/'+my_user_id+'.jpg" />');
								 
								 nb_uploaded++;
							}	 
								 upload_avancement+=upload_pas;
								 $('#progress_bar .ui-progress').animateProgress(upload_avancement, function() {});
								 
								 
								 $("#results").text(nb_uploaded+'/'+nb_to_upload+' éléments mis à jours');
					
						 }
					
			  });
			
		}
	});
}

function search_pict(my_id)
{
	$.ajax({
		url: "image_search.php",
        data   : {id:my_id},
          cache: false,
          dataType: "json",
        error : function(request, error) {          
                 $("#clients tr .new_tof[data-id=\""+my_id+"\"]").html('error');
				 avancement+=pas;
				 $('#progress_bar .ui-progress').animateProgress(avancement, function() {});
                },
          success: function(data) {    
			if (data.code_erreur == "0")
			{
                 $("#clients tr .new_tof[data-id=\""+my_id+"\"]").html('<img src="https://graph.facebook.com/'+data.reponse+'/picture" title="Nouvelle photo" />').siblings(".checkbox").html('<input type="checkbox" checked="checked" />');     
				 
				 nb_found++;
			}	 
				 avancement+=pas;
				 $('#progress_bar .ui-progress').animateProgress(avancement, function() {});
				 
				 
				 $("#results").text(nb_found+'/'+nb_tof+' éléments trouvés');
				 
				 if (avancement >= 100)
				 $('#progress_bar .ui-progress').animateProgress(avancement, function() 
				 {
					$('.ui-label').delay(1500).text('Clickez-ici pour télécharger les photos').css({'display':'block','cursor':'pointer'}).click(function(){
						upload_pict();
					});
				});
                 }
			
      });
}

$(function() {
  // Hide the label at start
  $('#progress_bar .ui-progress .ui-label').hide();
  // Set initial value
  $('#progress_bar .ui-progress').css('width', '0%');

  
  
});
</script>