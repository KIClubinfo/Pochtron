<?php

// Enregistre un évènement dans la base de données
function logue($texte, $categorie="sans",$importance="info",$sql=false){
  // $importance est dans [info,warn,critique]
  // $categorie est dans [sans,erreur,retrait,stocks,client,...]
  if(!$sql)
    global $sql;
  
  $texte = htmlentities($texte,ENT_QUOTES,'utf-8');
  
  $sql->rek("INSERT INTO `evenements` (`texte`, `categorie`, `importance`) VALUES ('$texte', '$categorie', '$importance');");
}