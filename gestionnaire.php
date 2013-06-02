<?php
define('page_titre', "Gestion courante &bull; Caisse Foyer");

$head_HTML = '<link rel="stylesheet" href="style/gestionnaire.css"><link rel="stylesheet"  href="style/jquery.jgrowl.css" type="text/css"><link rel="stylesheet"  href="style/scroll.css" type="text/css"><script type="text/javascript" src="scripts/jquery.jgrowl.js"></script><script src="/scripts/scroll.js"></script>';

include_once 'inclus/tete.html.php';
?>
	<div id="popup_commande" class="blue_popup">
		<div class="popup_title">
			Détail de la commande
		</div>
		<div class="popup_content">
			Commande
		</div>
		</div>
	</div>
  <div class="central_large bloc gestion">
   <a href="./" class="maison">Retour</a>
    <h1>Gestion courante</h1>
    <div id="eleves">
		<article class="article" id="list_eleves">
			<div class="article_title">
				Liste des élèves
			</div>
			<div class="article_content">
				<div id="list_eleves_table">
					<div class="table_title">
						<span class="cell_photo"></span>
						<span>Prénom</span>
						<span>Nom</span>
						<span>Solde</span>
					</div>
					<div class="scroll_bar" id="scroll_bar_list_eleves">
						<div class="scrollbar"><div class="track"><div class="thumb"><div class="end"></div></div></div></div>
						<div class="viewport">
							<div class="overview">	
								<ul class="table_content">
								<?php
									$sql->rek( 'SELECT `id` ,prenom,nom,solde,active,nb_consos FROM clients ORDER BY nom ASC' );

									while($a = $sql->fetch())
									{
									?>
										<li data-id="<?php echo $a['id']; ?>" class="table_row">
											<span class="cell_photo">
											<?php
												if(file_exists("images/photos/".$a['id'].".jpg")) 
													$photo = $a['id'];
												else
													$photo = "sans_photo";
												
											?>
												<img src="images/photos/<?php echo $photo; ?>.jpg">
											</span>
											<span class="firstname"><?php echo $a['prenom'];?></span>
											<span class="surname"><?php echo $a['nom'];?></span>
											<span class="solde"><?php echo $a['solde'];?></span>
											<span class="not_shown"><?php echo $a['prenom'];?> <?php echo $a['nom'];?> <?php echo $a['prenom'];?> <?php echo substr($a['prenom'],0,1).substr($a['nom'],0,1);?></span>
											<span class="selected">0</span>
											<span class="stars"><img src="images/icones/icons/star" /><img src="images/icones/icons/star" /><img src="images/icones/icons/star" /><img src="images/icones/icons/star" /><img src="images/icones/icons/star" /></span>
											<span class="distinctions"><img src="images/icones/icons/medal_gold_1" /><img src="images/icones/icons/medal_gold_2" /><img src="images/icones/icons/medal_gold_3" /></span>
											<span class="comment"></span>
											<span class="active"><?php echo $a['active'];?></span>
											<span class="url_photo"><?php echo $photo; ?></span>
										</li>
									<?php
									} ?>
								</ul>
							</div>
						</div>
					</div>
				</div>
				<div id="search_bar">
						Chercher un élève : &nbsp;&nbsp;
						<input type="text" name="to_search" id="to_search">
						&nbsp;&nbsp; <span id="nb_result"><? echo $sql->nbrlignes(); ?> résultats trouvés</span>
				</div>
			</div>
		</article>
		<article class="article" id="selected_eleves">
			<div class="article_title">
				Eleves sélectionnés
			</div>
			<div class="article_content">
				<div id="selected_eleves_table">
					<div class="table_title">
						<span class="cell_photo"></span><span class="full_name">Nom complet</span><span class="order">Commande</span><span class="actions">Actions</span>
					</div>
					<div class="scroll_bar" id="scroll_bar_selected_eleves">
						<div class="scrollbar"><div class="track"><div class="thumb"><div class="end"></div></div></div></div>
						<div class="viewport">
							<div class="overview">	
								<ul class="table_content">
									
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>
		</article>
	</div>
	
	<div id="produits">
		<article class="article" id="produits_disponibles">
			<div class="article_title">
				Produits disponibles
			</div>
			<div class="article_content">
				<div class="scroll_bar" id="scroll_bar_product">
					<div class="scrollbar"><div class="track"><div class="thumb"><div class="end"></div></div></div></div>
					<div class="viewport">
						<div class="overview">
							<?php
							$sql->rek( 'SELECT `id` ,`nom`, `vol` ,`prix` ,`icone` ,`qtt_reserve`,`ventes` FROM `produits` ORDER BY `nom` ASC' );
							
							$it = 0;
							
							while($a = $sql->fetch())
							{
								$produits[$it++] = $a;
							}
								
							for($i=0;$i<$it;$i++)
							{
							?>
								<div class="product_item">
									<div class="product_name">
										<?php echo $produits[$i]['nom']; ?>
									</div>
									<div class="product_img">
										<img src="images/produits/<?php echo $produits[$i]['icone']; ?>.png" />
									</div>
									<div class="product_button">
										<?php 
										if ($produits[$i]['nom'] == $produits[$i+1]['nom'])
										{
											?>
											<input type="submit" data-id="<?php echo $produits[$i]['id']; ?>" class="product_order vert half" value="<?php echo $produits[$i]['prix']; ?> €"/><input type="submit" data-id="<?php echo $produits[$i+1]['id']; ?>" class="product_order vert half" value="<?php echo $produits[$i+1]['prix']; ?> €"/>
										<?php
											$i++;
										}
										else
										{ ?>
											<input type="submit" data-id="<?php echo $produits[$i]['id']; ?>" class="product_order vert whole" value="<?php echo $produits[$i]['prix']; ?> €"/>
										<?php } ?>
									</div>
								</div>
							
							<?php
							}
							
							?>
						</div>
					</div>
				</div>
			</div>
		</article>
		<article class="article" id="user_actions">
			<div class="article_title">
				Validation & Options
			</div>
			<div class="article_content">
				<input class="vert valid_users" type="submit" value="Valider" onClick="valid_all_users()">
				<input class="cancel" type="reset" value="Annuler" onClick="cancel_all_users()">
				<input class="vert add_extern_user" type="submit" value="Client externe" onClick="add_extern_user()">
				<input class="cancel" type="reset" value="Historique" onClick="show_stats()">
			</div>
		</article>
	</div>
  </div>
  
 <script type="text/javascript">

$.jGrowl.defaults.position = 'bottom-right';
 
String.prototype.sansAccent = function(){
    var accent = [
        /[\300-\306]/g, /[\340-\346]/g, // A, a
        /[\310-\313]/g, /[\350-\353]/g, // E, e
        /[\314-\317]/g, /[\354-\357]/g, // I, i
        /[\322-\330]/g, /[\362-\370]/g, // O, o
        /[\331-\334]/g, /[\371-\374]/g, // U, u
        /[\321]/g, /[\361]/g, // N, n
        /[\307]/g, /[\347]/g, // C, c
    ];
    var noaccent = ['A','a','E','e','I','i','O','o','U','u','N','n','C','c'];
     
    var str = this;
    for(var i = 0; i < accent.length; i++){
        str = str.replace(accent[i], noaccent[i]);
    }
     
    return str;
}
  
function resize_boxes()
{
 	var width = (document.body.clientWidth);
	var height = (document.body.clientHeight-20);

	var section_title_height = 24;
	var search_bar_height = 35;
	var table_title_height = 35;

	var size_list=0;
	var size_selected=0;
	var nb_selected = $("#selected_eleves .table_row").size();
	
	if (nb_selected > 0)
	{
		size_selected=(nb_selected*90+section_title_height+table_title_height <= 0.46*height) ? nb_selected*90+section_title_height+table_title_height : 0.46*height;
		size_list=height*0.76-size_selected;
	}
	else
	{
		size_selected=0;
		size_list=height*0.76+10;
	}
	
	
	$("#list_eleves").css("height", size_list);

	$("#list_eleves .article_content").css("height", size_list-section_title_height);
	$("#list_eleves .article_title").css({"height":section_title_height,"line-height":section_title_height+"px"});

	$("#list_eleves .article_content #list_eleves_table").css("height", size_list-section_title_height-search_bar_height);
	$("#list_eleves .article_content #search_bar").css({"height":search_bar_height,"line-height":search_bar_height+"px"});

	$("#list_eleves .article_content #scroll_bar_list_eleves").css("height",size_list-section_title_height-search_bar_height-table_title_height);
	$("#list_eleves .article_content .table_title").css({"height":table_title_height,"line-height":table_title_height+"px"});
	
	$("#selected_eleves").css("height", size_selected);

	$("#selected_eleves .article_content").css("height", size_selected-section_title_height);
	$("#selected_eleves .article_title").css({"height": section_title_height,"line-height":section_title_height+"px"});
	
	$("#selected_eleves .article_content #selected_eleves_table").css("height", size_selected-section_title_height);
	
	$("#selected_eleves .article_content #scroll_bar_selected_eleves").css("height",size_selected-section_title_height-table_title_height);
	$("#selected_eleves .article_content .table_title").css({"height":table_title_height,"line-height":table_title_height+"px"});
	
	
	$("#produits_disponibles").css("height", (height)*0.66);
	$("#produits_disponibles .article_content").css("height", (height)*0.66-section_title_height);
	$("#produits_disponibles .article_title").css({"height":section_title_height,"line-height":section_title_height+"px"});
	$("#produits_disponibles .article_content #scroll_bar_selected_eleves").css("height",(height)*0.66-section_title_height);
	
	
	$("#user_actions").css("height", (height)*0.1);
	$("#user_actions .article_content").css({"height": (height)*0.1-section_title_height,"line-height":(height)*0.1-section_title_height+"px"});
	$("#user_actions .article_title").css({"height":section_title_height,"line-height":section_title_height+"px"});
	
	$('.scroll_bar').tinyscrollbar();
	
	var it = 0;
	
	$("#selected_eleves_table .table_row" ).each(function (i) 
	{
		if ( $(this).css('display') != "none" ) 
		{
			if (it % 2 ==0)
			{
				$(this).css("background-color", "rgba(0, 0, 0, 0.1)");
			}
			else
			{
				$(this).css("background-color", "rgba(0, 0, 0, 0.0)");
			}
			it++;
		} 
	});
}

$(window).resize(function(e) 
{
	resize_boxes();
});

var hover_color;


function jquery_actions()
{
	$(".table_row").unbind("mouseenter");
	$(".table_row").unbind("mouseleave");
	
	$(".table_row").mouseenter(function(){
	hover_color=$(this).css("background-color");
	$(this).css("background-color","rgba(0,0,0,0.3)");
	}).mouseleave(function(){
	$(this).css("background-color",hover_color)
	});
	
	
	$("#selected_eleves_table .table_row").unbind("click");
	$("#selected_eleves_table .table_row").click(function()
	{
		if ($(this).css("border-left-width") == "3px")
		{
			$(this).css("border-left","0px none #12A332");
		}
		else
		{
			$(this).css("border-left","3px solid #12A332");
		}
	});
	
	
	$(".table_row .order").unbind("mouseenter");
	$(".table_row .order").unbind("mouseleave");
	
	$(".command_pop").mouseenter(function(){
	$("#popup_commande .popup_content").html($(this).siblings(".command_details").html());
	$("#popup_commande").css({"display":"block", "top":$(this).offset().top-50, "left":$(this).offset().left+50});
	}).mouseleave(function(){
	$("#popup_commande").css("display", "none");
	});
}

$(".product_item .product_button input").click(function()
	{
		var it=0;
		var id = $(this).attr("data-id");
		var name = $(this).parent().siblings(".product_name").text();
		var img = $(this).parent().siblings(".product_img").html();
		var prix = parseFloat($(this).val());
		
		$("#selected_eleves_table .table_row" ).each(function () 
		{
			
			if ( $(this).css("border-left-width") == "3px" ) 
			{
				var com_script = $(".order .command_script",this).text();
				var old_solde = parseFloat($(".order .new_solde",this).text().substring(15));
				var old_tot = parseFloat($(".order .details_tot",this).text().substring(8));

				var new_solde = old_solde - prix;
				var new_tot = old_tot + prix;
					
				var regex_script = new RegExp(","+id+":([0-9]+),", "i");
				var pos = com_script.search(regex_script);
				
				
				if (pos >= 0)
				{
					regex_script.exec(com_script);
					var nb = parseInt(RegExp.$1);
					nb++;
					com_script = com_script.replace(regex_script, ","+id+":"+nb+",");
					$(".order .command_script",this).html(com_script);
					
					$(".order .command span[data-id=\""+id+"\"] span").text(nb)
					$(".order .details_item[data-id=\""+id+"\"] .left span").text(nb)
					
					var new_prix = parseFloat($(".order .details_item[data-id=\""+id+"\"] .right").text());
					new_prix += prix;
					
					$(".order .details_item[data-id=\""+id+"\"] .left span").text(nb);
					$(".order .details_item[data-id=\""+id+"\"] .right").text(new_prix.toFixed(2)+" €");
				}
				else
				{
					$(".order .command_script",this).append(id+":1,");
					
					$(".order .command", this).append("<span data-id=\""+id+"\"><span>1</span>x "+ img+"</span> ");
					$(".order .command img", this).addClass("order_img").last().attr("data-id", id);
					
					$(".order .command_details .details_tot", this).before("<div class=\"details_item\" data-id=\""+id+"\"><span class=\"left\"><span>1</span> "+name+"</span><span class=\"right\">"+prix.toFixed(2)+" €</span></div>");
				}
				
				$(".order .new_solde",this).text("Nouveau solde : "+new_solde.toFixed(2)+" €");
				$(".order .details_tot",this).text("Total : "+new_tot.toFixed(2)+" €");
				
				
			} 

		});
	});
	


jquery_actions();



$("#list_eleves_table .table_row").click(function(){
		var firstname = $(this).children(".firstname").text();
		var surname = $(this).children(".surname").text();
		var solde = $(this).children(".solde").text();
		var id = $(this).attr("data-id");
		var not_shown = $(this).children(".not_shown").text();
		var selected = $(this).children(".selected").text();
		var stars = $(this).children(".stars").html();
		var distinctions = $(this).children(".distinctions").html();
		var comment = $(this).children(".comment").html();
		var active = $(this).children(".active").text();
		var url_photo = $(this).children(".url_photo").text();
		
		if (active == 'bloqué')
		{
			alert("Client bloqué");
		}
		else
		{
			$(this).children(".selected").html(1);
			search();
			add_selected_eleve(id, firstname, surname, solde, stars, distinctions, comment, url_photo);
			jquery_actions();
		}
});



jQuery.expr[':'].search_name = function(a, i, m) 
{	
	return (($(a).children(".not_shown").text().sansAccent().toUpperCase().indexOf(m[3].toUpperCase()) >= 0) && ($(a).children(".selected").text() == 0))
}; 

jQuery.expr[':'].not_selected = function(a, i, m) 
{	
	return ($(a).children(".selected").text() == 0);
}; 

function add_one_user()
{
	var count = 0;
	var pointor = 0;
	$("#list_eleves_table .table_row" ).each(function (i) 
	{
		if ( $(this).css('display') != "none" ) 
		{
			pointor = this;
			count++;
		} 
		
	});
	
	if (count == 1)
	{
		var firstname = $(pointor).children(".firstname").text();
		var surname = $(pointor).children(".surname").text();
		var solde = $(pointor).children(".solde").text();
		var id = $(pointor).attr("data-id");
		var not_shown = $(pointor).children(".not_shown").text();
		var selected = $(pointor).children(".selected").text();
		var stars = $(pointor).children(".stars").html();
		var distinctions = $(pointor).children(".distinctions").html();
		var comment = $(pointor).children(".comment").html();
		var active = $(pointor).children(".active").text();
		var url_photo = $(pointor).children(".url_photo").text();
		
		if (active == 'bloqué')
		{
			alert("Client bloqué");
		}
		else
		{
			$(pointor).children(".selected").html(1);
			search();
			add_selected_eleve(id, firstname, surname, solde, stars, distinctions, comment, url_photo);
			jquery_actions();
		}
	}
}


function search()
{
	nb_result = 0;
	var name = $("#to_search").val().sansAccent();
	
	$("#list_eleves_table .table_row").hide();
	
	if (name != "")
	{
		$("#list_eleves_table .table_row:search_name('"+name+"')").show();	
	}
	else
	{
		$("#list_eleves_table .table_row:not_selected()").show();
	}
	
	$( "#list_eleves_table .table_row" ).each(function (i) 
	{
		if ( $(this).css('display') != "none" ) 
		{
			if ($(this).children(".active").text() == 'bloqué')
			{
				if (nb_result % 2 ==0)
				{
					$(this).css("background-color", "rgba(137, 53, 53, 0.8)");
				}
				else
				{
					$(this).css("background-color", "rgba(137, 53, 53, 1)");
				}
			}
			else
			{
				if (nb_result % 2 ==0)
				{
					$(this).css("background-color", "rgba(0, 0, 0, 0.1)");
				}
				else
				{
					$(this).css("background-color", "rgba(0, 0, 0, 0.0)");
				}
			}
			nb_result++;
		} 
	});
	
	$('#nb_result').html(nb_result+' résultats trouvés');
	resize_boxes();
	$('.scroll_bar').tinyscrollbar();
}

$("#to_search").bind("keyup", function()
{
	search();
});

search();

function add_selected_eleve(id, firstname, surname, solde, stars, distinctions, comment, url_photo)
{
	$("#selected_eleves .table_content").append("<li data-id=\""+id+"\" class=\"table_row\"><span class=\"cell_photo\"><img src=\"images/photos/"+url_photo+".jpg\"></span><span class=\"full_name\">"+firstname+" "+surname+"<br />"+stars+"<br />Distinctions : "+distinctions+"</span><span class=\"order\"><span class=\"old_solde\">Ancien solde : "+solde+" €</span><br /><span class=\"command\">Commande : </span><br /><div class=\"command_pop\">Détails</div><div class=\"command_script\">,</div><div class=\"command_details\"><span class=\"details_tot\">Total : 0 €</span></div><span class=\"new_solde\">Nouveau solde :  "+solde+" €</span></span><span class=\"actions\"><a href=\"javascript:return false;\" onClick=\"valid_user(this)\"><img alt=\"Valider la commande\"src=\"images/valid.png\" /></a> <a onClick=\"cancel_user(this)\" href=\"javascript:return false;\"><img alt=\"Annuler cette commande\" src=\"images/cancel.png\" /></a><a onClick=\"add_cash(this)\" href=\"javascript:return false;\"><img alt=\"Ajout liquide\" src=\"images/add.png\" /></a></span></li>");
	$("#selected_eleves li").last().css("border-left","3px solid #12A332");
	resize_boxes();

}

function valid_all_users()
{
	$("#selected_eleves_table .table_row" ).each(function (i) 
	{
		valid_user($(".actions > a",this));
	});
}

function cancel_all_users()
{
	$("#selected_eleves_table .table_row" ).each(function (i) 
	{
		cancel_user($(".actions > a", this));
	});
}

function valid_user(elem)
{
	var conso = $(elem).parent().parent().children(".order").children(".command_script").text();
	var my_id = $(elem).parent().parent().attr("data-id");
	
	$.ajax({
          url: "traitement.php",
        data   : {id:my_id,consom:conso},
          cache: false,
          dataType: "json",
        error : function(request, error) {          
                 $.jGrowl("Erreur", { group:'red_popup', life: 10000 });
                },
          success: function(data) {                
                    $.jGrowl(data.reponse, { group:'green_popup', life: 10000 });
                      
                                        
                  }
      });
	  
	  cancel_user(elem);
}

function cancel_user(elem)
{
	$("#list_eleves .table_row[data-id=\""+$(elem).parent().parent().attr("data-id")+"\"] .selected").text("0");
	  $(elem).parent().parent().remove();	  
	  search();
}

function add_cash(elem)
{
	alert('Non encore disponible');
}

function add_extern_user()
{
	alert('Non encore disponible');
}

function show_stats()
{
	alert('Non encore disponible');
}

//]]>
</script>
 
<?php
include 'inclus/pied.html.php';
?><br />