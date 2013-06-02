<?php
// Classe pour l'utilisation de MySQL.

include_once 'inclus/config.inc.php';
include_once 'inclus/fonctions.inc.php';

class rekSQL {
      var $sql_bdd;
      var $result;
      var $derek;
      var $nbrek;

      // Constructeur de classe
      function rekSQL($init = false){
            if(!($this->connecte($init))) /* !$init && */
                  return false;
      }

      // Connexion à la base de données
      function connecte($init){

            if(empty($this->sql_bdd)) // Si aucune base de données n'a été spécifiée
                  $this->sql_bdd = _oOoMySQLBDD;

            // Connexion au serveur
            if(!(@mysql_connect(_oOoMySQL,_oOoMySQLUSER,_oOoMySQLPASS))){
                  // En cas d'erreur
                  $this->erreur('Impossible de se connecter au serveur MySQL.');
                  return false;
            }

            if(!$init){
                  // Connexion à la base de données
                  if(!(mysql_select_db($this->sql_bdd))){
                        $this->erreur('Impossible de se connecter à la base de données.');
                        return false;
                  }
            }
      }

      // Fermeture de la connexion
      function deconnecte(){
            mysql_close();
      }

      // Requète SQL
      function rek($sql){
            $this->nbrek++; // On incrémente le compteur de requêtes
            $this->derek = $sql;
            if($this->result = mysql_query($sql))
                  return $this->result;
            else{
                  $this->erreur('Impossible d\'exécuter une requête.');
                  return false;
            }
      }

      // Recherche des résultats
      function fetch($s = false){
            if($s === false) $s = $this->result;
            return mysql_fetch_assoc($s);
      }
      // Recherche des résultats
      function resul($s = false){
            if($s === false) $s = $this->result;
            return mysql_fetch_row($s);
      }

      // Affichage de la ligne
      function resultat_ligne($num){
            return mysql_result($this->result, $num);
      }

      // Sécurisation de données
      function secur($chaine){
            if(get_magic_quotes_gpc()) // Vérifie si les guillemets magiques ont été activés et enlève la protection pour éviter la surprotection
                  $chaine  = stripslashes($chaine);
            return mysql_real_escape_string($chaine);
      }

      // Nombre de résultats
      function nbrlignes(){
            return mysql_num_rows($this->result);
      }
      // Nombre de modifications
      function nbrchangements(){
            return mysql_affected_rows();
      }
      // ID inséré
      function insertid(){
            return mysql_insert_id();
      }

       // Affichage d'une erreur
      function erreur($txt){
            $err = (_AFF_ERR_SQL) ? "Une erreur s'est produite sur la base de données. Désolé !<br>Cette erreur a été enregistrée : <br>".mysql_error() : "Une erreur s'est produite sur la base de données. Désolé !<br>Cette erreur a été enregistrée, nous essaierons de résoudre ce problème au plus vite.";
            
            logue('Erreur SQL : '.mysql_error().' sur '.$this->derek,'sql','critique',$this);

            echo '<div class="notif argh"><strong>Une erreur s\'est produite sur la base de donnée...</strong><br>'.$err.'</div>';
      }
      
      // Exporter dans un tableur
      function exporte_xls($rek,$fichier='export.xls'){
	header("Content-type: application/vnd.ms-excel; Charset:UTF-8");
	header("Content-Disposition: attachment; filename=$fichier");

	$this->rek($rek);

	echo "<table border='0' cellpadding='0' cellspacing='0'>
	<tr bgcolor='#CCCCCC' height='40px'>";
	$a = $this->fetch();
	foreach($a as $i=>$v){
	  echo '<th>'.utf8_decode($i).'</th>';
	}
	echo '</tr>';
	foreach($a as $i=>$v){
	  echo '<td>'.utf8_decode($v).'</td>';
	}
	while ($a = $this->fetch()) {
	  echo '<tr>';
	  foreach($a as $v)
	    echo '<td>'.utf8_decode($v).'</td>';
	  echo '</tr>';
	}
	echo '</table>';
	exit();
      }
}


/* ====== Exemple d'utilisation ======

<?php
$sql = new rekSQL;
$sql->rek( 'SELECT `id`,`nom` FROM `test`' );
while($a = $sql->fetch()){
      echo '<br/>'.$a['nom'];
}
$sql->deconnecte();
?>

*/