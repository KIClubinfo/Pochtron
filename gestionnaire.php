<?php
//Titre de la page
define('page_titre', "Gestion courante &bull; Caisse Foyer");

$head_HTML = '<link rel="stylesheet" href="style/gestionnaire.css"><link rel="stylesheet"  href="style/jquery.jgrowl.css" type="text/css"><link rel="stylesheet"  href="style/scroll.css" type="text/css">';

include_once 'inclus/tete.html.php';
?>
	
	<div id="prompt_box">
		<div class="title">
			Ajouter Cash<span class="but_close">x</span>
		</div>
		<div class="content cash">
			<table>
				<tbody>
					<tr>
						<td class="label">PIN :</td>
						<td class="input"><input type="password" name="pin" min="0" step="0.10"  size="4" value=""></td>
					</tr>
					<tr>
						<td class="label">Augmenter de :</td>
						<td class="input"><input type="number" name="added_cash" min="0" step="0.10" size="4" value="0.00"></td>
					</tr>
					<tr class="buttons">
						
					</tr>
				</tbody>
			</table>
		</div>
	</div>
	<div id="modal_screen">
	</div>
	<div id="popup_historique">
		<div class="title">
			Historiques des commandes<span class="but_close">x</span>
		</div>
		<div class="content">
			<table class="lignes">
				<tbody>
				<?php 
					$sql->rek( 'SELECT c.id, c.qtte_produit, c.id_produit, c.timestamp, e.prenom, e.nom, p.nom AS produit, p.icone FROM commandes c, clients e, produits p WHERE c.id_user = e.id AND c.id_produit=p.id ORDER BY c.id DESC LIMIT 10' );
					while($command = $sql->fetch())
					{
						echo '<tr command-id="'.$command['id'].'"><td class="id_command">'.$command['id'].'</td><td>'.$command['timestamp'].'</td><td>'.$command['prenom'].' '.$command['nom'].'</td><td>'.$command['qtte_produit'].'x <span class="miniature"><img src="images/produits/'.$command['icone'].'.png" alt="'.$command['produit'].'" /></span></td><td><img title="Annuler" class="cancel_command" src="images/icones/icons/cancel.png" alt="Annuler" /></td></tr>';
					}
				?>
				</tbody>
			</table>
		</div>
	</div>
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
									$sql->rek( 'SELECT * FROM clients ORDER BY nom ASC' );
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
											<span class="litres_bus"><?php echo $a['litres_bus']; ?></span>
											<span class="distinctions"><img src="images/icones/icons/medal_gold_1.png" /><img src="images/icones/icons/medal_gold_2.png" /><img src="images/icones/icons/medal_gold_3.png" /></span>
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
						&nbsp;&nbsp; <span id="nb_result"><?php echo $sql->nbrlignes(); ?> résultats trouvés</span>
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
										if ($i+1!=$it and $produits[$i]['nom'] == $produits[$i+1]['nom'])
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
 
<img src="" alt="" id="pict_viewer">
<script type="text/javascript" src="scripts/jquery-1.9.1.js"></script>
<script type="text/javascript" src="scripts/jquery.jgrowl.js"></script>
<script type="text/javascript" src="scripts/scroll.js"></script>
<script type="text/javascript" src="scripts/ajax.js"></script>
<script type="text/javascript" src="scripts/gestionnaire.js"></script>
 
<?php
include 'inclus/pied.html.php';
?><br />