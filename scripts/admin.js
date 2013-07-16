function modif_solde(retrait){
  document.getElementById("solderestant").innerHTML = Math.round(( document.getElementById("soldeactuel").innerHTML - retrait)*100)/100;
} 

// Estomper les éléments qui le demandent
$(document).ready(function(){
//   $(".estompe").css("opacity","0");
  setTimeout( "jQuery('.estompe').fadeOut();",4000 );
  $(".notif").click(function(){
    $(this).fadeOut();
  })
  
  $(".prix").after(" €");
});

function edit(id){
  alert("La modification des produits n'est pas encore implémentée... Peut-être qu'en glissant quelques billets dans la poche de MB015 ça ira mieux ?")
}

function supp(id){
  if(confirm("Voulez-vous vraiment supprimer ce produit ? Toutes ses données seront effacées (mis à part l'icône, qu'il faut toujours gérer manuellement)")){
      location.href="stock.php?efface="+id
  }
};

function editc(id){
  
//   $("tr#c"+id+" span").replaceWith("<input type='text' name='"+ $(this).attr("name") +"' value='"+ $(this).contents() +"'>");
  ;
//   alert("La modification des clients n'est pas encore implémentée... Peut-être qu'en glissant quelques billets dans la poche de MB015 ça ira mieux ?")
}

function suppc(id){
  if(confirm("Voulez-vous vraiment tuer ce client ? Toutes ses données seront effacées (mis à part l'icône, qu'il faut toujours gérer manuellement)")){
      location.href="clients.php?efface="+id
  }
}
function suppp(id){
  if(confirm("Voulez-vous vraiment effacer ce produit ?")){
      location.href="stock.php?efface="+id
  }
}