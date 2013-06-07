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
function add_one_user()
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
	var stars = $(eleve).children(".stars").html();
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
		$("#selected_eleves .table_content").append("<li data-id=\""+id+"\" class=\"table_row\"><span class=\"cell_photo\"><img src=\"images/photos/"+url_photo+".jpg\"></span><span class=\"full_name\">"+firstname+" "+surname+"<br />"+stars+"<br />Distinctions : "+distinctions+"</span><span class=\"order\"><span class=\"old_solde\">Ancien solde : "+solde+" €</span><br /><span class=\"command\">Commande : </span><br /><div class=\"command_pop\">Détails</div><div class=\"command_script\">,</div><div class=\"command_details\"><span class=\"details_tot\">Total : 0 €</span></div><span class=\"new_solde\">Nouveau solde :  "+solde+" €</span></span><span class=\"actions\"><a href=\"javascript:return false;\" onClick=\"valid_user(this)\"><img alt=\"Valider la commande\"src=\"images/valid.png\" /></a> <a onClick=\"cancel_user(this)\" href=\"javascript:return false;\"><img alt=\"Annuler cette commande\" src=\"images/cancel.png\" /></a><a onClick=\"add_cash(this)\" href=\"javascript:return false;\"><img alt=\"Ajout liquide\"  src=\"images/add.png\" /></a></span></li>");
		$("#selected_eleves li").last().css("border-left","3px solid #12A332");
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
$(document).on('click',".product_item .product_button input",function()
{
		//Récupération des données relatives au produit sélectionné
		var id = $(this).attr("data-id");
		var name = $(this).parent().siblings(".product_name").text();
		var img = $(this).parent().siblings(".product_img").html();
		var prix = parseFloat($(this).val());
		
		//Pour chaque élève de la liste des élèves sélectionnés
		$("#selected_eleves_table .table_row" ).each(function () 
		{
			//Dans le cas où l'élève de cette liste n'est pas déselectionné (afin de l'exclure momentanément de la sélection)
			if ( $(this).css("border-left-width") == "3px" ) 
			{
				//Récupération des données relatives à l'élève avant l'ajout du produit
				var com_script = $(".order .command_script",this).text();//com_script contient alors la chaine parsée et représentative de la commande, envoyée au serveur lors de la validation
				var old_solde = parseFloat($(".order .new_solde",this).text().substring(15));
				var old_tot = parseFloat($(".order .details_tot",this).text().substring(8));
				
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
					
					$(".order .command span[data-id=\""+id+"\"] span").text(nb);//Modification des quantités dans le détail de la commande
					$(".order .details_item[data-id=\""+id+"\"] .left span").text(nb);//Ici aussi
					
					var new_prix = parseFloat($(".order .details_item[data-id=\""+id+"\"] .right").text());
					new_prix += prix;//Maj du prix
					
					$(".order .details_item[data-id=\""+id+"\"] .right").text(new_prix.toFixed(2)+" €");//Et on l'affiche
				}
				else //Sinon c'est pratiquement pareil
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
	if ($(this).css("border-left-width") == "3px")
		$(this).css("border-left","0px none #12A332");
	else
		$(this).css("border-left","3px solid #12A332");
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


/*------------------------------------------FIN #7----------------------------------------*/

/*------------------------------------------#8 VALIDATION----------------------------------------*/

function valid_all_users()
{
	$("#selected_eleves_table .table_row" ).each(function (i) 
	{
		valid_user($(".actions > a",this));
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
                 $.jGrowl("Erreur : " + error, { group:'red_popup', life: 10000 });
                },
          success: function(data) {   
		    if(data.code_erreur==AJAX_OK)
			$.jGrowl(data.reponse, { group:'green_popup', life: 10000 });
                    else
			$.jGrowl(data.reponse, { group:'red_popup', life: 10000 });         
                  }
      });
	  
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

/*------------------------------------------FIN #10----------------------------------------*/