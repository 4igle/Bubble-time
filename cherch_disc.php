<?php session_start();
	header("X-FRAME-OPTIONS: DENY");
	if (!(isset($_SESSION['id'])) or !(isset($_SESSION['pseudo'])))
	{
		$_SESSION = array();
		session_destroy();
		header("Location: menu.php");
	}
	$_POST['recherche'] = htmlspecialchars($_POST['recherche']);
	include("bdd/bdd.php");
	$req = $bdd->prepare('SELECT id FROM discussions WHERE ((pseudo1 = :pseudo1 and pseudo2 = :pseudo2) OR (pseudo1 = :pseudo2 and pseudo2 = :pseudo1)) AND groupe = 0');
	$req->execute(array(
						'pseudo1' => $_SESSION['pseudo'],
						'pseudo2' => $_POST['recherche']
						));
	$donnees = $req->fetch();
	if (empty($donnees)) //conv inexistante
	{
		$req-> closeCursor();
		$req = $bdd->prepare('SELECT * FROM utilisateurs WHERE pseudo = :pseudo');
		$req->execute(array(
					'pseudo' => $_POST['recherche']
					));
		$donnees = $req->fetch();
		if (empty($donnees)) //utilisateur inexistant
		{
			$req-> closeCursor();
			header("Location: accueil.php?conv=erreur");
		}
		else //création de la conv
		{
			if ($donnees['id'] != $_SESSION['id']) //pas de conv avec soi meme
			{
				$id_nouv = $donnees['id'];
				$pseudo_nouv = $donnees['pseudo'];
				$clef = bin2hex(random_bytes(10));
				$req-> closeCursor();
				$req = $bdd->prepare('INSERT INTO discussions(id1, id2, pseudo1, pseudo2, clef) VALUES (:id1, :id2, :pseudo1, :pseudo2, :clef)');
				$req->execute(array(
									'id1' => $_SESSION['id'],
									'id2' => $id_nouv,
									'pseudo1' => $_SESSION['pseudo'],
									'pseudo2' => $pseudo_nouv,
									'clef' => $clef
									));
				$req = $bdd->prepare('SELECT id FROM discussions WHERE (pseudo1 = :pseudo1 and pseudo2 = :pseudo2) OR (pseudo1 = :pseudo2 and pseudo2 = :pseudo1)');
				$req->execute(array(
									'pseudo1' => $_SESSION['pseudo'],
									'pseudo2' => $pseudo_nouv
									));
				$donnees = $req->fetch();
				if (empty($donnees)) //cas théoriquement impossible mais on sait jamais
				{
					$req-> closeCursor();
					header("Location: accueil.php");
				}
				else //renvoi vers la nouvelle conv
				{
					$id = $donnees['id'];
					$req-> closeCursor();				
					header("Location: accueil.php?conv=$id");
				}
			}
			else
			{
				$req-> closeCursor();
				header("Location: accueil.php");
			}
		}
	}
	else //conv existante, envoi vers cette conv
	{
		$id = $donnees['id'];
		$req-> closeCursor();
		header("Location: accueil.php?conv=$id");
	}
?>