/*--------------------------------------#1 PREREQUIS-----------------------------------*/

//Inclusion des librairies nécéssaires au bon fonctionnement de ces scripts
//ATTENTION, les fichiers suivants doivent avoir été préalablement chargés : jquery-1.9.1.js, jquery.jgrowl.js et scroll.js

var AJAX_OK = 0;
var AJAX_UNKNOW_FAIL = 1;
var AJAX_NOT_IMPLEMENTED = 2;

/*------------------------------------------FIN #1----------------------------------------*/



/*--------------------------------------#2 FONCTIONS TIERCES-----------------------------------*/

//Suppression des accents courants d'une chaine de caractère
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

/*------------------------------------------FIN #2----------------------------------------*/



/*--------------------------------------#3 INITIALISATION-----------------------------------*/

//Les notifications seront affichés à partir du coin inférieur droit de la fenêtre.
$.jGrowl.defaults.position = 'bottom-right';


/*------------------------------------------FIN #3----------------------------------------*/

 

/*--------------------------------------#4 GESTION TAILLE ECRAN-----------------------------------*/

//Fonction à appeler pour rafraichir les proportions
function resize_boxes()
{
	//Récupération de la taille de la fenêtre
 	var width = (document.body.clientWidth);
	var height = (document.body.clientHeight-20);
	
	//Définition des hauteurs respectives
	var section_title_height = 24;
	var search_bar_height = 35;
	var table_title_height = 35;

	//Calcul des hauteurs des listes d'élèves (colonne de gauche du gestionnaire)
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
	
	//Application des hauteurs aux div concernées - Liste des élèves (zone de recherche)
	$("#list_eleves").css("height", size_list);

	$("#list_eleves .article_content").css("height", size_list-section_title_height);
	$("#list_eleves .article_title").css({"height":section_title_height,"line-height":section_title_height+"px"});

	$("#list_eleves .article_content #list_eleves_table").css("height", size_list-section_title_height-search_bar_height);
	$("#list_eleves .article_content #search_bar").css({"height":search_bar_height,"line-height":search_bar_height+"px"});

	$("#list_eleves .article_content #scroll_bar_list_eleves").css("height",size_list-section_title_height-search_bar_height-table_title_height);
	$("#list_eleves .article_content .table_title").css({"height":table_title_height,"line-height":table_title_height+"px"});
	
	//Application des hauteurs aux div concernées - Elèves sélectionnés
	$("#selected_eleves").css("height", size_selected);

	$("#selected_eleves .article_content").css("height", size_selected-section_title_height);
	$("#selected_eleves .article_title").css({"height": section_title_height,"line-height":section_title_height+"px"});
	
	$("#selected_eleves .article_content #selected_eleves_table").css("height", size_selected-section_title_height);
	
	$("#selected_eleves .article_content #scroll_bar_selected_eleves").css("height",size_selected-section_title_height-table_title_height);
	$("#selected_eleves .article_content .table_title").css({"height":table_title_height,"line-height":table_title_height+"px"});
	
	//Application des hauteurs aux div concernées - Produits disponibles
	$("#produits_disponibles").css("height", (height)*0.66);
	$("#produits_disponibles .article_content").css("height", (height)*0.66-section_title_height);
	$("#produits_disponibles .article_title").css({"height":section_title_height,"line-height":section_title_height+"px"});
	$("#produits_disponibles .article_content #scroll_bar_selected_eleves").css("height",(height)*0.66-section_title_height);
	
	//Application des hauteurs aux div concernées - Actions utilisateurs
	$("#user_actions").css("height", (height)*0.1);
	$("#user_actions .article_content").css({"height": (height)*0.1-section_title_height,"line-height":(height)*0.1-section_title_height+"px"});
	$("#user_actions .article_title").css({"height":section_title_height,"line-height":section_title_height+"px"});
	
	//MAJ des barres de défilement
	$('.scroll_bar').tinyscrollbar();
	
	//Coloration de la liste des élèves sélectionnés
	var it = 0;
	
	//Redimensionnement des boutons produits
	if (width <= 1400)
	{
		var product_width = ($("#produits_disponibles").width())/4-20;
		$("#produits_disponibles .product_item").css({"width": product_width, "height": product_width});
		$("#produits_disponibles .product_img").css({"height": product_width*0.75, "line-height": product_width*0.75+"px"});
		$("#produits_disponibles .product_button").css({"height": product_width*0.3, "line-height": product_width*0.3+"px"});
		$("#produits_disponibles .product_order").css({"height": product_width*0.3, "line-height": product_width*0.3+"px"});

	}
	else
	{
		$("#produits_disponibles .product_item, .product_img, .product_button, .product_order").removeAttr('style');
	}
	
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

//Appel de la fonction précedente lorsque la fenêtre est redimensionnée
$(window).resize(function(e) 
{
	resize_boxes();
});


/*------------------------------------------FIN #4----------------------------------------*/



/*--------------------------------------#5 GESTION SELECTION ELEVE-----------------------------------*/

//Retourne true si l'élève est selectionné
jQuery.expr[':'].not_selected = function(a, i, m) 
{	
	return ($(a).children(".selected").text() == 0);
};   

//Fonction appelée lors de la pression de la touche "Entrée".
function enter_pressed()
{
	
	if ($('#prompt_box_cash').css('display') != 'none')
	{
		$('#prompt_box_cash .buttons input[value="Ajouter"]').click();
	}
	else if ($('#prompt_box_extern').css('display') != 'none')
	{
		$('#prompt_box_extern .buttons input[value="Ajouter"]').click();
	}
	else if ($('#prompt_box_pin').css('display') != 'none')
	{
		$('#prompt_box_pin .buttons input[value="Valider"]').click();
	}
	else
	{
		//Initialisations
		var count = 0;
		var pointor = 0;
		
		//Compte le nombre d'élève présents dans la liste des élèves (correspondant à la recherche).
		$("#list_eleves_table .table_row" ).each(function (i) 
		{
			if ( $(this).css('display') != "none" ) 
			{
				pointor = this; //Pointe vers l'élève courant
				count++;
			} 
		});
		
		//Si la liste ne compte qu'un seul élève
		if (count == 1)
		{
			add_selected_eleve(pointor)
		}
	}
}

//Ajoute un élève à la liste des élève sélectionnais
function add_selected_eleve(eleve)
{
	//Récupération des données relatives à cet élève (pointé par pointor)
	var firstname = $(eleve).children(".firstname").text();
	var surname = $(eleve).children(".surname").text();
	var solde = $(eleve).children(".solde").text();
	var id = $(eleve).attr("data-id");
	var not_shown = $(eleve).children(".not_shown").text();
	var selected = $(eleve).children(".selected").text();
	var distinctions = $(eleve).children(".distinctions").html();
	var comment = $(eleve).children(".comment").html();
	var active = $(eleve).children(".active").text();
	var url_photo = $(eleve).children(".url_photo").text();
	
	//Vérifie que l'élève n'est pas bloqué
	if (active == 'bloqué')
	{
		alert("Client bloqué");
	}
	else
	{
		//Sélection de l'élève à proprement parlé
		$(eleve).children(".selected").html(1); //Changement de l'état de l'élève afin de ne plus l'afficher dans la liste des élèves
		$("#selected_eleves .table_content").append("<li data-id=\""+id+"\" class=\"table_row\"><span class=\"cell_photo\"><img src=\"images/photos/"+url_photo+".jpg\"></span><span class=\"full_name\">"+firstname+" "+surname+"<br /><span class=\"stars\">"+calcul_star(eleve)+"</span><br />"+distinctions+"</span><span class=\"order\">Ancien solde : <span class=\"old_solde\">"+solde+" €</span><br />Nouveau solde : <span class=\"new_solde\">"+solde+" €</span><br /><span class=\"command\"></span><br /><div class=\"command_pop\">Détails de la commande</div><div class=\"command_script\">,</div><div class=\"command_details\"><span class=\"details_tot\">Total : 0 €</span></div></span><span class=\"actions\"><a href=\"javascript:return false;\" class=\"valid_user\"><img alt=\"Valider la commande\"src=\"images/valid.png\" /></a> <a class=\"cancel_user\" href=\"javascript:return false;\"><img alt=\"Annuler cette commande\" src=\"images/cancel.png\" /></a><a class=\"add_cash\" href=\"javascript:return false;\"><img alt=\"Ajout liquide\"  src=\"images/add.png\" /></a></span></li>");
		$("#selected_eleves li").last().css("border-left","5px solid rgb(18, 163, 50)");
		$(".cell_photo img").mouseover(function() {
			$("#pict_viewer").stop();
			$("#pict_viewer").clearQueue();
			$("#pict_viewer").attr("src",$(this).attr("src"));
			$("#pict_viewer").delay(10).fadeIn();
		    });
		$(".cell_photo img").mouseout(function() {
			$("#pict_viewer").fadeOut();
		    });
		search();//Rafraichissement de la liste des élèves et redimensionnement via resize_boxes()
	}
}

/*------------------------------------------FIN #5----------------------------------------*/



/*--------------------------------------#6 RECHERCHE ELEVE-----------------------------------*/

//Retourne les élèves correspondant à la recherche et non déjà sélectionnés
jQuery.expr[':'].search_name = function(a, i, m) 
{	
	return (($(a).children(".not_shown").text().sansAccent().toUpperCase().indexOf(m[3].toUpperCase()) >= 0) && ($(a).children(".selected").text() == 0))
}; 

//Fonction à appeler pour effectuer une recherche dans la liste des élèves. Mot clé : Contenu du champ de recherche au moment de l'appel de la fonction.
function search()
{
	//Initialisations
	nb_result = 0;
	$("#list_eleves_table .table_row").hide();
	
	//Récupération mot-clé de recherche
	var name = $("#to_search").val().sansAccent();
	
	//Affichage des élèves correspondants à la recherche
	if (name != "")
	{
		$("#list_eleves_table .table_row:search_name('"+name+"')").show();	
	}
	else
	{
		$("#list_eleves_table .table_row:not_selected()").show();
	}
	
	//Recoloriage des lignes
	$( "#list_eleves_table .table_row" ).each(function (i) 
	{
		if ( $(this).css('display') != "none" ) 
		{
			if ($(this).children(".active").text() == 'bloqué')
			{
				if (nb_result % 2 ==0)
					$(this).css("background-color", "rgba(137, 53, 53, 0.8)");
				else
					$(this).css("background-color", "rgba(137, 53, 53, 1)");
			}
			else
			{
				if (nb_result % 2 ==0)
					$(this).css("background-color", "rgba(0, 0, 0, 0.1)");
				else
					$(this).css("background-color", "rgba(0, 0, 0, 0.0)");
			}
			nb_result++;
		} 
	});
	
	//Affichage du nombre de résultats trouvés
	$('#nb_result').html(nb_result+' résultats trouvés');
	
	//Redimensionnement des divs
	resize_boxes();
}

//Gestion évènement
$("#to_search").bind("keyup", function()
{
	search();
});


//Initialisation au cas où (navigateur qui reaffiche le contenu des champs lors du clic sur 'Page précédente')
search();


/*------------------------------------------FIN #6----------------------------------------*/


/*------------------------------------------#7 EVENEMENTS----------------------------------------*/

//Clic sur un produit
$(document).on('click',".product_item .product_button input",function(e)
{
	add_product(this);
	e.stopPropagation();
});

$(document).on('click',".product_item", function()
{
	if ($('input',this).size() == 1)
	{	
		add_product($('input',this));
	}
});

function add_product(elem)
{
	//Récupération des données relatives au produit sélectionné
	var id = $(elem).attr("data-id");
	var name = $(elem).parent().siblings(".product_name").text();
	var img = $(elem).parent().siblings(".product_img").html();
	var prix = parseFloat($(elem).val());
	
	//Pour chaque élève de la liste des élèves sélectionnés
	$("#selected_eleves_table .table_row" ).each(function () 
	{
		//Dans le cas où l'élève de cette liste n'est pas déselectionné (afin de l'exclure momentanément de la sélection)
		if ( $(this).css("border-left-color") == "rgb(18, 163, 50)" ) 
		{
			//Récupération des données relatives à l'élève avant l'ajout du produit
			var com_script = $(".order .command_script",this).text();//com_script contient alors la chaine parsée et représentative de la commande, envoyée au serveur lors de la validation
			var temp_array = com_script.split(',');
			var com_length = temp_array.length;
			var old_solde = parseFloat($(".order .new_solde",this).text());
			var old_tot = parseFloat($(".order .details_tot",this).text().substring(7));
			
			//Calculs du nouveau solde et du total de la commande
			var new_solde = old_solde - prix;
			var new_tot = old_tot + prix;
			//Edition du détail de la commande et de la chaine représentant la commande envoyée au serveur lors de la validation
			
			//Via une expression régulière, on cherche à savoir si le produit à ajouter fait déjà partie de la commande
			var regex_script = new RegExp(","+id+":([0-9]+),", "i");
			var pos = com_script.search(regex_script);
			
			
			if (pos >= 0) //Si c'est le cas
			{
				regex_script.exec(com_script);//On récupère l'ancienne quantité
				var nb = parseInt(RegExp.$1);
				nb++;//On l'incrémente
				com_script = com_script.replace(regex_script, ","+id+":"+nb+",");
				$(".order .command_script",this).html(com_script);//On remplace par la nouvelle quantité dans la chaine de commande parsée
				
				$(".order .command span[data-id=\""+id+"\"] span:nth-child(1)", this).text(nb);//Modification des quantités dans le détail de la commande
				$(".order .details_item[data-id=\""+id+"\"] .left span", this).text(nb);//Ici aussi
				
				var new_prix = parseFloat($(".order .details_item[data-id=\""+id+"\"] .right").text());
				new_prix += prix;//Maj du prix
				
				$(".order .details_item[data-id=\""+id+"\"] .right").text(new_prix.toFixed(2)+" €");//Et on l'affiche
			}
			else //Sinon c'est pratiquement pareil
			{
				if (com_length >= 6)
				{
					$.jGrowl('Trop de produits sélectionnés', { group:'blue_popup', life: 10000 });
				}
				else
				{
					$(".order .command_script",this).append(id+":1,");
					
					$(".order .command", this).append("<span data-id=\""+id+"\"><span>1</span>x <span class=\"miniature\">"+ img+"</span></span> ");
					$(".order .command img", this).last().attr("data-id", id);
					
					$(".order .command_details .details_tot", this).before("<div class=\"details_item\" data-id=\""+id+"\"><span class=\"left\"><span>1</span> "+name+"</span><span class=\"right\">"+prix.toFixed(2)+" €</span></div>");
				}
			}
			
			if ($(this).attr("data-id") != 'extern')
			{
				$(".order .new_solde",this).text(new_solde.toFixed(2)+" €");
				$(".order .details_tot",this).text("Total : "+new_tot.toFixed(2)+" €");
			}
		} 
	});
}
	
//Variable globale pour mémoriser la couleur initiale de la ligne survolée.	
var hover_color;

//Survol d'une ligne de la liste d'élève ou de celle des élèves sélectionnés
$(document).on('mouseenter',".table_row",function(){
	hover_color=$(this).css("background-color");
	$(this).css("background-color","rgba(0,0,0,0.3)");
	});

//Sortie du curseur d'une telle ligne
$(document).on('mouseleave',".table_row",function(){
	$(this).css("background-color",hover_color)
});

//Clic sur un élève de la liste des élèves sélectionnés afin de le déselectionner momentanément
$(document).on('click',"#selected_eleves_table .table_row",function()
{
	if ($(this).css("border-left-color") == "rgb(18, 163, 50)")
		$(this).css("border-left","5px solid #333");
	else
		$(this).css("border-left","5px solid rgb(18, 163, 50)");
		
	
});

//Fermer boite de dialongue
$(document).on('click',".but_close",function()
{
	close_box($(this).parent().parent());
});

function close_box(elem)
{
	
	if (elem.attr('class') == 'prompt_box')
		elem.hide();
	else
		elem.animate({"right":"-500px"});
		
	$('#prompt_box_extern input[name="name"]').val('');
	$('.prompt_box input[name="pin"]').val('');
		
	$('#modal_screen').hide();
}

//Annuler ancienne commande
$(document).on('click',".cancel_command",function()
{
	var id=$(this).parent().siblings('.id_command').text();
	cancel_old_order(id);
	$('#popup_historique').css("right","-500px");
});

//Popup de détail de la commande
$(document).on('mouseenter',".command_pop",function(){
	$("#popup_commande .popup_content").html($(this).siblings(".command_details").html());
	$("#popup_commande").css({"display":"block", "top":$(this).offset().top-50, "left":$(this).offset().left+50});
});

//Idem
$(document).on('mouseleave',".command_pop",function(){
	$("#popup_commande").css("display", "none");
});
	
//Clic sur un élément de la liste des élèves afin de l'ajouter aux élèves sélectionnés
$(document).on('click',"#list_eleves_table .table_row",function(){
		add_selected_eleve(this);
});

$(document).on('click',".add_cash",function(e)
{
	add_cash(this);	
	e.stopPropagation();
});

$(document).on('click',".valid_user",function(e)
{
	if ($(this).parent().parent().attr('data-id') != 'extern')
	{
		valid_user(this);
	}
	else
	{
		get_pin(0, this)
	}
	e.stopPropagation();
});

$(document).on('click',".cancel_user",function(e)
{
	cancel_user(this);	
	e.stopPropagation();
});

/*------------------------------------------FIN #7----------------------------------------*/

/*------------------------------------------#8 VALIDATION----------------------------------------*/

function valid_all_users()
{
	var is_there_extern = 0;
	
	$("#selected_eleves_table .table_row" ).each(function (i) 
	{
		if ($(this).attr('data-id') == 'extern')
			is_there_extern = 1;
		else
			valid_user($(".actions > a",this));
	});
	
	if (is_there_extern == 1)
		get_pin(1);
}

function valid_user(elem)
{
	var conso = $(elem).parent().parent().children(".order").children(".command_script").text();
	var my_id = $(elem).parent().parent().attr("data-id");
	
	var this_eleve = $("#list_eleves .table_row[data-id=\""+my_id+"\"] .solde").text('Rafraichissement en cours');
	
	var GET_args =  {'action':'order', 'id' : my_id, 'consom':conso};//Arguments de la requète GET
	ajax_url("traitement.php", GET_args, ajax_callback, ajax_error);//Appel AJAX
		  
	cancel_user(elem);
}



/*------------------------------------------FIN #8----------------------------------------*/


/*------------------------------------------#9 ANNULATION----------------------------------------*/

function cancel_all_users()
{
	$("#selected_eleves_table .table_row" ).each(function (i) 
	{
		cancel_user($(".actions > a", this));
	});
}



function cancel_user(elem)
{
	$("#list_eleves .table_row[data-id=\""+$(elem).parent().parent().attr("data-id")+"\"] .selected").text("0");
	  $(elem).parent().parent().remove();	  
	  search();
}

/*------------------------------------------FIN #9----------------------------------------*/


/*------------------------------------------#10 FONCTIONS AUXILIAIRES----------------------------------------*/
function add_cash(elem)
{
	$('#prompt_box_cash .buttons').html('<td colspan="2"><input class="vert valid_users" type="submit" value="Ajouter" onClick="send_cash_request('+$(elem).parent().parent().attr("data-id")+')"> <input class="cancel" type="reset" value="Annuler" onClick="javascript:close_box($(\'#prompt_box_cash\'));"></td>');
	
	$("#prompt_box_cash").show();
	$("#modal_screen").show();
	$("#prompt_box_cash input[name=\"pin\"]").focus()
}

function send_cash_request(id)
{
	var pin = $('#prompt_box_cash input[name="pin"]').val();
	var cash = $('#prompt_box_cash input[name="added_cash"]').val();
	var GET_args =  {'action':'add_cash', 'id' : id, 'pin':pin, 'cash':cash};//Arguments de la requète GET
	ajax_url("traitement.php", GET_args, ajax_callback, ajax_error);//Appel AJAX
	
	close_box($('#prompt_box_cash'));
}

function get_pin(all, elem=0)
{
	if (all)
	{
		$('#prompt_box_pin .buttons').html('<td colspan="2"><input class="vert valid_users" type="submit" value="Valider" onClick="javascript:valid_all_extern_user();"> <input class="cancel" type="reset" value="Annuler" onClick="javascript:close_box($(\'#prompt_box_pin\'));"></td>');
	}
	else
	{
		$('#prompt_box_pin .buttons').html('<td colspan="2"><input class="vert valid_users" type="submit" value="Valider" onClick="javascript:valid_extern_user(elem);"> <input class="cancel" type="reset" value="Annuler" onClick="javascript:close_box($(\'#prompt_box_pin\'));"></td>');

	}
	$('#prompt_box_pin .buttons').html('<td colspan="2"><input class="vert valid_users" type="submit" value="Valider" onClick="javascript:valid_all_extern_user();"> <input class="cancel" type="reset" value="Annuler" onClick="javascript:close_box($(\'#prompt_box_pin\'));"></td>');

	$("#prompt_box_pin").show();
	$("#modal_screen").show();
	$("#prompt_box_pin input[name=\"pin\"]").focus();
}

function valid_extern_user(elem)
{
	var pin = $('#prompt_box_pin input[name="pin"]').val();
	
	var GET_args =  {'action':'extern_order', 'pin':pin, 'consom':$(elem).parent().parent().children('.command_script').text()};//Arguments de la requète GET
	ajax_url("traitement.php", GET_args, ajax_callback, ajax_error);//Appel AJAX

	cancel_user(elem);
	
	close_box($('#prompt_box_pin'));
}

function valid_all_extern_user()
{
	var pin = $('#prompt_box_pin input[name="pin"]').val();
	
	$("#selected_eleves_table .table_row[data-id=\"extern\"]" ).each(function (i) 
	{
		var GET_args =  {'action':'extern_order', 'pin':pin, 'consom':$('.command_script',this).text()};//Arguments de la requète GET
		ajax_url("traitement.php", GET_args, ajax_callback, ajax_error);//Appel AJAX

		cancel_user($(".actions > a", this));
	});
	
	close_box($('#prompt_box_pin'));
	

}


function calcul_star(elem)
{
	var litres_bus = parseFloat($('.litres_bus', elem).text());
	var string_stars = '';
	
	for(var i=0;i<=litres_bus/10;i++) 
		string_stars += '<img src="images/icones/icons/star.png" title="'+litres_bus+' litre(s) bu(s)"/>';

	return string_stars;
}

function add_extern_user()
{
	$('#prompt_box_extern .buttons').html('<td colspan="2"><input class="vert valid_users" type="submit" value="Ajouter" onClick="javascript:insert_extern_user();"> <input class="cancel" type="reset" value="Annuler" onClick="javascript:close_box($(\'#prompt_box_extern\'));"></td>');
	
	$("#prompt_box_extern").show();
	$("#modal_screen").show();
	$("#prompt_box_extern input[name=\"name\"]").focus()
}

function insert_extern_user()
{
	var name='Anonyme';
	
	if ($('#prompt_box_extern input[name="name"]').val() != '')
		name = $('#prompt_box_extern input[name="name"]').val();
		
	$("#selected_eleves .table_content").append("<li data-id=\"extern\" class=\"table_row\"><span class=\"cell_photo\"><img src=\"images/photos/sans_photo.jpg\"></span><span class=\"full_name\">"+name+"<br /></span><span class=\"order\"><span class=\"new_solde\">Les commandes externes doivent être immédiatement payées.</span><br /><span class=\"command\"></span><br /><div class=\"command_pop\">Détails de la commande</div><div class=\"command_script\">,</div><div class=\"command_details\"><span class=\"details_tot\">Total : 0 €</span></div></span><span class=\"actions\"><a href=\"javascript:return false;\" class=\"valid_user\"><img alt=\"Valider la commande\"src=\"images/valid.png\" /></a> <a class=\"cancel_user\" href=\"javascript:return false;\"><img alt=\"Annuler cette commande\" src=\"images/cancel.png\" /></a></span></li>");
	$("#selected_eleves li").last().css("border-left","5px solid rgb(18, 163, 50)");
	
	close_box($('#prompt_box_extern'));
	resize_boxes();
}

function show_stats()
{
	$('#popup_historique').animate({"right":"10px"});
}

function cancel_old_order(id_order)
{
	var GET_args =  {'action':'cancel', 'id' : id_order};//Arguments de la requète GET
	ajax_url("traitement.php", GET_args, ajax_callback, ajax_error);//Appel AJAX
}

//Fonction de callback ajax
function ajax_callback(data, GET_args)
{
	$.jGrowl(data.reponse, { group:'green_popup', life: 10000 });
	
	var this_eleve = $("#list_eleves .table_row[data-id=\""+data.id+"\"]");
	$('.solde', this_eleve).text(parseFloat(data.solde).toFixed(2));
	
	if (GET_args['action']=='add_cash')
	{
		if ($('.selected', this_eleve).text() == '1')
		{
			var this_selected_eleve = $("#selected_eleves .table_row[data-id=\""+data.id+"\"]");
			var old_old_solde = parseFloat($(".order .old_solde",this_selected_eleve).text());
			var old_new_solde = parseFloat($(".order .new_solde",this_selected_eleve).text());
			var diff_solde = old_new_solde - old_old_solde;
			
			var new_new_solde = parseFloat(data.solde)+parseFloat(diff_solde);
			
			$(".order .old_solde",this_selected_eleve).text(parseFloat(data.solde).toFixed(2)+' €')
			$(".order .new_solde",this_selected_eleve).text(new_new_solde.toFixed(2)+' €')
		}
	}
	
	if (GET_args['action']=='cancel')
	{
		if ($('.selected', this_eleve).text() == '1')
		{
			var this_selected_eleve = $("#selected_eleves .table_row[data-id=\""+data.id+"\"]");
			var old_old_solde = parseFloat($(".order .old_solde",this_selected_eleve).text());
			var old_new_solde = parseFloat($(".order .new_solde",this_selected_eleve).text());
			var diff_solde = old_new_solde - old_old_solde;
			
			var new_new_solde = parseFloat(data.solde)+parseFloat(diff_solde);
			
			$(".order .old_solde",this_selected_eleve).text(parseFloat(data.solde).toFixed(2)+' €')
			$(".order .new_solde",this_selected_eleve).text(new_new_solde.toFixed(2)+' €')
			$(".full_name .stars", this_selected_eleve).html(calcul_star(this_eleve));
			
			$('.litres_bus', this_eleve).text(parseFloat(data.litres_bus).toFixed(2));
		}
		
		$("#popup_historique tr[command-id=\""+GET_args['id']+"\"]").remove();
		
		if ($('#popup_historique tr').size() == 0)
			$('#popup_historique tbody').html('<tr><td>Aucune commande disponible pour l\'annulation</td></tr>');
	}
	
	if (GET_args['action']=='order')
	{
		var command_prod_qtte = data.command_prod_qtte.split(',');
		var command_prod_icone = data.command_prod_icone.split(',');
		var command_prod_id = data.command_prod_id.split(',');
		for (var i=0;i<command_prod_id.length-2;i++)
		{
			if ($("#popup_historique tr > td").text() == "Aucune commande disponible pour l'annulation")
				$("#popup_historique table").html('<tr command-id="'+(parseFloat(data.command_id)+i)+'"><td class="id_command">'+(parseFloat(data.command_id)+i)+'</td><td>'+data.command_timestamp+'</td><td>'+data.command_util+'</td><td>'+command_prod_qtte[i+1]+'x <span class="miniature"><img src="images/produits/'+command_prod_icone[i+1]+'.png" alt="'+command_prod_icone[i+1]+'" /></span></td><td><img title="Annuler" class="cancel_command" src="images/icones/icons/cancel.png" alt="Annuler" /></td></tr>');
			else
				$("#popup_historique tr:nth-child(1)").before('<tr command-id="'+(parseFloat(data.command_id)+i)+'"><td class="id_command">'+(parseFloat(data.command_id)+i)+'</td><td>'+data.command_timestamp+'</td><td>'+data.command_util+'</td><td>'+command_prod_qtte[i+1]+'x <span class="miniature"><img src="images/produits/'+command_prod_icone[i+1]+'.png" alt="'+command_prod_icone[i+1]+'" /></span></td><td><img title="Annuler" class="cancel_command" src="images/icones/icons/cancel.png" alt="Annuler" /></td></tr>');
		}
		
		$('.litres_bus', this_eleve).text(parseFloat(data.litres_bus).toFixed(2));
		
		for (var i=0;i<$('#popup_historique tr').length-9;i++)
		{
			$('#popup_historique tr:last-child').remove();
		}
	}
}

//Fonction de traitement d'erreur ajax
function ajax_error(data, GET_args)
{
	$.jGrowl(data.reponse, { group:'red_popup', life: 10000 });
	
	$("#list_eleves .table_row[data-id=\""+data.id+"\"] .solde").text(parseFloat(data.solde).toFixed(2));
}


/*------------------------------------------FIN #10----------------------------------------*/