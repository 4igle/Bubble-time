<?php session_start(); 
	if (!(isset($_SESSION['id'])) or !(isset($_SESSION['pseudo'])))
	{
		$_SESSION = array();
		session_destroy();
		header("Location: menu.php");
	}
?>
<?php header("X-FRAME-OPTIONS: DENY"); ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>Accueil</title>
        <link rel="icon" type="image/png" sizes="16x16" href="images/logo.png">
        <link rel="stylesheet" type="text/css" href="css/in.css">
        <link rel="stylesheet" type="text/css" href="css/phone_bulles.css">
        <link rel="stylesheet" type="text/css" href="css/phone_onglets.css">
        <script type="text/javascript" src="script/points.js" defer></script>
        <script type="text/javascript" src="script/false.js" defer></script>
        <script type="text/javascript" src="script/retour.js" defer></script>
    	<?php
    	if (isset($_GET['conv']) and $_GET['conv'] != "")
    	{ 
    		?> <script type="text/javascript" src="script/focus.js" defer></script>
    		<script type="text/javascript" src="script/phone_disc.js" defer></script>
    		<link rel="stylesheet" type="text/css" href="css/phone_disc.css"> <?php 
    	}
    	else
    	{
    		?> <script type="text/javascript" src="script/phone.js" defer></script>
    		<link rel="stylesheet" type="text/css" href="css/phone.css"> <?php
    	}
    	?>
    	<script src="script/scroll.js"></script>
    </head>
    <body>
    	<?php
    	function rdate($date)
    	{
    		$date2 = strtotime($date); //date2 = date conv
    		if (date('d/m/Y', $date2) == date('d/m/Y'))
    		{
    			echo "Aujourd'hui " . date('H:i', $date2);
    		}
    		else
    		{
    			$date3 = strtotime(date('Y/m/d')); //date3 = date du jour - 1j
    			$date3 = strtotime("-1 day", $date3);
    			if (date('d/m/Y', $date2) == date('d/m/Y', $date3))
    			{
    				echo 'Hier ' . date('H:i', $date2);
    			}
    			else
    			{
    				echo date('d/m/Y H:i', $date2);
    			}
    		}
    	}
    	?>
    	<div class="tout">
	    	<div class="discussions">
	    		<div class="formulaires">
	    			<div class="opt_haut">
			    		<form method="POST" action="trait_opt.php" class="options">
				    		<select name="options" class="options">
				    			<option value="" selected disabled>-- Choisissez une option --</option>
				    			<option value="2">Nouveau groupe</option>
			    				<option value="1">Déconnexion (supprime aussi la connexion auto)</option>
			    			</select>
			    			<input type="submit" value="Valider" class="bouton_options">
			    		</form>
			    		<form method="POST" class="recherche" action="cherch_disc.php" class="recherche">
			    			<input type="text" name="recherche" class="recherche" placeholder="Chercher ou créer une discussion" autocomplete="off">
			    			<input type="submit" value="Rechercher" class="bouton_recherche">
			    		</form>
			    	</div>
		    		<?php
		    			if (isset($_GET['conv']))
						{
							$conv = (int) $_GET['conv'];
						}
						if (isset($_GET['type']))
						{
							$_GET['type'] = htmlspecialchars($_GET['type']);
						}
					?>
		    		<div class="type_conv">
		    			<a href="accueil.php?<?php if (isset($conv)) echo 'conv=' . $conv . '&amp;' ?>type=tout" class="type_select" style="<?php if ((isset($_GET['type']) and $_GET['type'] != 'groupe' and $_GET['type'] != 'mp') or !isset($_GET['type'])) echo "background-color: rgb(130, 130, 130);"; ?>">
			    			<div class="type_select">
			    				<p class="type_select">Tout</p>
			    			</div>
			    		</a>	
		    			<a href="accueil.php?<?php if (isset($conv)) echo 'conv=' . $conv . '&amp;' ?>type=mp" class="type_select" style="<?php if (isset($_GET['type']) and $_GET['type'] == 'mp') echo "background-color: rgb(130, 130, 130);"; ?>">
			    			<div class="type_select">
			    				<p class="type_select">Messages privés</p>
			    			</div>
			    		</a>	
		    			<a href="accueil.php?<?php if (isset($conv)) echo 'conv=' . $conv . '&amp;' ?>type=groupe" class="type_select" style="<?php if (isset($_GET['type']) and $_GET['type'] == 'groupe') echo "background-color: rgb(130, 130, 130);"; ?>">
			    			<div class="type_select">
			    				<p class="type_select">Groupes</p>
			    			</div>
			    		</a>	
		    		</div>
		    	</div>
		    	<div class="convs">
		    		<?php
		    			if (isset($_GET['conv']))
		    			{
		    				$disc_gauche = (int) $_GET['conv'];
		    			}
		    			else
		    			{
		    				$disc_gauche = 0;
		    			}
		    			include("bdd/bdd.php");
		    			if (isset($_GET['type']) and $_GET['type'] == 'mp')
		    			{
		    				include("query/query_mp.php");
		    			}
		    			elseif (isset($_GET['type']) and $_GET['type'] == 'groupe')
		    			{
		    				include("query/query_groupe.php");
		    			}
		    			else
		    			{
		    				include("query/query.php");
		    			}

						$req = $bdd->prepare($query);
						$req->execute(array(
											'id' => $_SESSION['id']
											));
						while($donnees = $req->fetch())
						{
							if ($donnees['grp']) //non lus en cas de groupe
							{
								$non_lu = "non_lu1";
								foreach ($donnees as $key => $value) {
									if (preg_match("#^gid([1-9]|[1-3][0-9])$#", $key)) 
									{
										if ($value == $_SESSION['id'])
										{
											$num = preg_replace("#^gid([1-9]|[1-3][0-9])$#", "$1" , $key);
											$non_lu = "non_lu" . $num;
										}		
									}
								}
								$nombre_messages = $donnees[$non_lu];
							}
							else //non lus en cas de disc
							{
								$nombre_messages = $donnees['non_lus'];
							}
							if ($donnees['pseudo1'] != $_SESSION['pseudo'] and !$donnees['grp'])
							{
								$interlocuteur = $donnees['pseudo1'];
							}
							else
							{
								$interlocuteur = $donnees['pseudo2'];
							} ?>
							<a href="accueil.php?conv=<?php echo $donnees['id'] ?><?php if (isset($_GET['type'])) echo '&amp;type=' . $_GET['type'] ?><?php if ($donnees['non_lus'] != 0 and !$donnees['grp']) echo '#true'?>" class="contact">
								<div class="contact" <?php if ($disc_gauche == $donnees['id']) { ?> style="background-color: rgb(20, 50, 255);" <?php } ?> >
									<div class="contact_haut" style="height: 50%;">
										<div class="pseudo">
											<?php
												echo htmlspecialchars($interlocuteur);;
											?>
										</div>
										<div class="heure">
											<?php rdate($donnees['vdate']) ?>
										</div>
									</div>
									<div class="contact_bas" style="height: 50%;">
										<div class="conv_msg">
											<div class="conv_msg_msg">
												<div class="js" style="min-height: 18px; line-height: 1.2em;">
													<?php 
														$x = openssl_decrypt($donnees['message'], "AES-128-ECB" , $donnees['clef']); 
														$x = preg_replace('#<a href=".*">(.*)</a>#', "$1", $x);
														echo $x;
													?>
												</div>
											</div>
											<div class="points" style="display: none;">
												...
											</div>
										</div>

										<?php
											if ($nombre_messages != 0 and $donnees['latest_env'] != $_SESSION['id'] and $disc_gauche != $donnees['id'])
											{
												$x = $nombre_messages;
												if ($x > 99) {$x = "99+";}
												echo '<div class="num"><div class="numero">' . $x . '</div></div>';
											}											
										?>
									</div>
								</div>
							</a>
						<?php }
						$req->closeCursor();	
		    		?>
		    	</div>
	    	</div>
	    	<div class="messages">
	    		<?php
	    		if (isset($_GET['conv'])) //vérif conv bonne
	    		{
		    		$_GET['conv'] = (int) $_GET['conv'];
		    		$req = $bdd->query('SELECT COUNT(*) AS nbdisc FROM discussions');
		    		$donnees = $req->fetch();
		    		$nbdisc = $donnees['nbdisc'];
		    		$req-> closeCursor();
		    		if ($_GET['conv'] >= 1 and $_GET['conv'] <= $nbdisc)
		    		{
		    			include("query/query2.php");
			    		$req = $bdd->prepare($query2);
			    		$req->execute(array(
			    							'iddisc' => $_GET['conv'],
			    							'id' => $_SESSION['id']
			    							));
			    		$donnees = $req->fetch();
			    		if (empty($donnees))
			    		{
			    			$req->closeCursor();
			    			include('inclusions/mauvais_proprio.php');
			    		}
			    		elseif ($donnees['id1'] != $_SESSION['id'] and $donnees['id2'] != $_SESSION['id'] and !$donnees['groupe'])
			    		{
			    			$req->closeCursor();
			    			include('inclusions/mauvais_proprio.php');
			    		}
			    		else //conforme, donne les messages
			    		{
			    			?> <div class="bandeau">
			    					<h1 class="bandeau">
			    						<?php
			    							if ($donnees['pseudo1'] != $_SESSION['pseudo'] and !$donnees['groupe'])
			    							{
			    								echo $donnees['pseudo1'];
			    							}
			    							else
			    							{
			    								echo $donnees['pseudo2'];
			    							}
			    						?>			    							
			    					</h1>
			    				</div> 
			    			<div class="bulles" id="bulles">
			    			<div class="bulles_contenu" id="bulles_contenu">
			    			<?php

			    			include('inclusions/message_debut_conv.php');
			    			$clef = $donnees['clef'];
			    			$disc_groupe = $donnees['groupe'];
			    			$req->closeCursor();
				    		$req = $bdd->prepare('SELECT msg.*, utilisateurs.pseudo, DATE_FORMAT(msg.heure, "%H:%i") AS heure_msg FROM msg, utilisateurs WHERE iddisc = :iddisc AND idenv = utilisateurs.id ORDER BY msg.heure');
				    		$req->execute(array(
				    							'iddisc' => $_GET['conv']
				    							));
				    		$conv_pas_vide = 0;
				    		$premier_message_non_lu = 0;
				    		$last_pseudo = NULL; //variable pour pas afficher 2 fois le pseudo d'affilée
				    		while($donnees = $req->fetch())
			    			{
			    				$display = 'false';
			    				$conv_pas_vide = 1;
			    				if ($donnees['idenv'] == $_SESSION['id']) //qui envoie le message
			    				{
			    					$emplacement = 'droite';
			    				}
			    				else
			    				{
			    					$emplacement = 'gauche';
			    					if (!$donnees['lu'] and $premier_message_non_lu == 0)
			    					{
			    						$premier_message_non_lu = 1;
			    						$display = 'true';
			    					}
			    				} ?>
			    				<?php if (!$disc_groupe) { ?>
			    				<div class="<?php echo $display ?>" id="<?php echo $display ?>" >
			    					<div class="fond">
				    					<p class="<?php echo $display ?>">
				    						Messages non lus
				    					</p>
				    				</div>
			    				</div>
			    				<?php } ?>
			    				<div class="<?php echo $emplacement ?>" <?php if ($donnees['pseudo'] == $last_pseudo) {echo 'style="margin-top: 0.2em;"';} else {echo 'style="margin-top: 1.3em;"';} ?> >
			    					<?php 
			    					if ($disc_groupe and $donnees['pseudo'] != $last_pseudo)
			    					{
			    						echo '<p class="pseudo_groupe">' . $donnees['pseudo'] . '</p>';
			    					}
			    					$last_pseudo = $donnees['pseudo'];
			    					 ?>
			    					<p class="message"><?php echo openssl_decrypt($donnees['contenu'], "AES-128-ECB" , $clef) ?> </p>
			    					<p class="heure_msg"><?php echo $donnees['heure_msg'] ?></p>
			    				</div>
			    				<?php

			    			}

		    				//change le statut des messages non lus
		    				$req = $bdd->prepare('UPDATE msg SET lu = true WHERE iddisc = :iddisc AND idenv != :idenv AND lu = false');
			    			$req->execute(array(
			    								'iddisc' => $_GET['conv'],
			    								'idenv' => $_SESSION['id']
			    								));

			    			//change le statut de la conv non lue
		    				$req = $bdd->prepare('UPDATE discussions SET non_lus = 0 WHERE id = :id AND latest_env != :latest_env');
			    			$req->execute(array(
			    								'id' => $_GET['conv'],
			    								'latest_env' => $_SESSION['id']
			    								));	
			    			if ($disc_groupe) //mise à 0 de son compteur non lus personnel
			    			{
			    				$req = $bdd->prepare('SELECT * FROM groupe WHERE iddisc = :iddisc');
				    			$req->execute(array(
				    								'iddisc' => $_GET['conv']
				    								));
				    			$donnees = $req->fetch();

			    				$non_lu = "non_lu1";
								foreach ($donnees as $key => $value) {
									if (preg_match("#^id([1-9]|[1-3][0-9])$#", $key)) 
									{
										if ($value == $_SESSION['id'])
										{
											$num = preg_replace("#^id([1-9]|[1-3][0-9])$#", "$1" , $key);
											$non_lu = "non_lu" . $num;
										}		
									}
								}
								$req->closeCursor();

			    				$req = $bdd->prepare('UPDATE groupe SET ' . $non_lu . ' = 0 WHERE iddisc = :iddisc');
				    			$req->execute(array(
				    								'iddisc' => $_GET['conv'],
				    								));
			    			}	    			

			    			if ($conv_pas_vide == 0)
		    				{
		    					include('inclusions/pas_de_msg.php');
		    				}
			    			$req->closeCursor(); ?>
			    			</div></div>
			    			<form method="POST" action="envoi_message.php?conv=<?php echo $_GET['conv']; ?><?php if (isset($_GET['type'])) echo '&amp;type=' . $_GET['type'] ?> " class="nouv_msg">
			    				<input type="text" name="message" placeholder="Tapez un message" autofocus class="envoi_msg" autocomplete="off">
			    				<input type="submit" name="bouton" class="envoyer">
			    			</form>
			    			<script language="javascript">
							  var $scroll_clipper = $('#bulles'); //Le conteneur, qui a une hauteur fixe (200px)
							  var $scroll_text = $('#bulles_contenu'); //Le contenu, qui a une hauteur variable, en fonction de la longueur du texte
							   
							  function move_down() { //Aller en bas
							    $scroll_clipper[0].scrollTop = $scroll_text.height();
							  }


							  move_down();
							</script>

			    			<?php
			    		}
			    	}
			    	else
			    	{
			    		include('inclusions/mauvais_proprio.php');
			    	}
			    }
			    else
			    {
			    	include('inclusions/pas_de_conv_selec.php');
			    }
	    		?>

	    	</div>
    	</div>
    </body>
</html>