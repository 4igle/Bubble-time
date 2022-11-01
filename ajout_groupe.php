<?php session_start(); 
	header("X-FRAME-OPTIONS: DENY");
	if (!(isset($_SESSION['id'])) or !(isset($_SESSION['pseudo'])))
	{
		$_SESSION = array();
		session_destroy();
		header("Location: menu.php");
	}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>Nouveau groupe</title>
        <link rel="icon" type="image/png" sizes="16x16" href="images/logo.png">
        <link rel="stylesheet" type="text/css" href="css/box.css">
        <link rel="stylesheet" type="text/css" href="css/groupe.css">
        <link rel="stylesheet" type="text/css" href="css/groupe_tel.css" />
    </head>
    <body>
		<form class="form" method="POST" action="creation_groupe.php">
			<p class="retour"><a href="accueil.php" style="color: black;">Retour</a></p>

			<h2>Nouveau groupe</h2>
			<label for="nom_groupe" class="nom_groupe">Nom du groupe :</label>

			<input type="text" name="nom" class="search" id="nom_groupe" pattern=".{0,}[^ ]{1,}.{0,}" required autofocus maxlength="20">
			<fieldset>
				<div class="contacts">
					<?php 
						include("bdd/bdd.php");
						$req = $bdd->prepare('SELECT * FROM discussions WHERE (pseudo1 = :pseudo OR pseudo2 = :pseudo) AND groupe = 0 ORDER BY latest DESC');
						$req->execute(array('pseudo' => $_SESSION['pseudo']));
						$i = 0;
						while($donnees = $req->fetch())
						{
							if ($_SESSION['pseudo'] == $donnees['pseudo1'])
							{
								$pseudo_contact = $donnees['pseudo2'];
								$id_contact = $donnees['id2'];
							}
							else
							{
								$pseudo_contact = $donnees['pseudo1'];
								$id_contact = $donnees['id1'];
							}
							$i++;
							?>
							<div class="inputGroup">
				    			<input id="contact<?php echo $i; ?>" name="contact<?php echo $i; ?>" type="checkbox" value="<?php echo $id_contact; ?>" class="contact"/>
				    			<label for="contact<?php echo $i; ?>" class="contact"><?php echo $pseudo_contact; ?></label>
							</div><?php
						}
					?>
				</div>
			</fieldset>
			<input type="submit" class="valider">
		</form>

    </body>
</html>