<?php session_start();
	header("X-FRAME-OPTIONS: DENY");
	if (!(isset($_SESSION['id'])) or !(isset($_SESSION['pseudo']))) //vérification session en cours
	{
		$_SESSION = array();
		session_destroy();
		header("Location: menu.php");
	}

	function verif_selection($database) //fonction qui vérifie si toutes les séléctions de contact sont bonnes
	{
		$valide = true;
		foreach ($_POST as $key => $value) 
		{
			if (preg_match("#^contact([1-9]|[1-3][0-9])$#", htmlspecialchars($key)) and $valide == true)
			{
				$valuesafe = (int) $value;
				$req = $database->prepare('SELECT * FROM discussions WHERE (id1 = :id1 AND id2 = :id2) OR (id2 = :id1 AND id1 = :id2) AND groupe = 0');
				$req->execute(array(
									'id1' => $_SESSION['id'],
									'id2' => $valuesafe
									));
				$donnees = $req->fetch();	
				if (empty($donnees))
				{
					$valide = false;
				}
				$req->closeCursor();
			}
		}
		return $valide;
	}

	function correct($database) //fonction pour compter le nombre de membres séléctionnés puis la validité
	{
		$membres = 1;
		$tab_membres[0] = $_SESSION['id']; //tableau avec la liste des id pour éviter 2 fois le même id dans le groupe
		$correct = true;
		foreach ($_POST as $key => $value) {
			if (preg_match("#^contact([1-9]|[1-3][0-9])$#", htmlspecialchars($key)) and !in_array((int) $value, $tab_membres)) 
			{
				$tab_membres[$membres] = (int) $value;
				$membres++;
			}
			elseif (preg_match("#^contact([1-9]|[1-3][0-9])$#", htmlspecialchars($key)))
			{
				$correct = false;
			}
		}
		if ($membres >= 3 and $membres <= 30 and $correct)
		{
			return verif_selection($database);
		}
		else
		{
			return false;
		}
	}

	function ajouter($database, $group_name) //fonction qui ajoute la liste séléctionnée à un groupe
	{
		$membres = "2";
		$tabmembres["id1"] = $_SESSION['id'];
		foreach ($_POST as $key => $value) {
			if (preg_match("#^contact([1-9]|[1-3][0-9])$#", htmlspecialchars($key))) 
			{
				$tabmembres["id". $membres] = (int) $value;
				$membres++;
			}
		}
		if ($membres <= 30)
		{
			for ($i = $membres; $i <= 30; $i++)
			{
				$tabmembres["id". $i] = NULL;
			}
		}
		$clef = bin2hex(random_bytes(10));
		//création de la disc
		$req = $database->prepare('INSERT INTO discussions(id1, id2, pseudo1, pseudo2, clef, groupe) VALUES (:id1, :id2, :pseudo1, :pseudo2, :clef, 1)');
		$req->execute(array(
							'id1' => $_SESSION['id'],
							'id2' => $_SESSION['id'],
							'pseudo1' => $_SESSION['pseudo'],
							'pseudo2' => $group_name,
							'clef' => $clef
							));
		//récup de l'id de conv
		$req = $database->prepare('SELECT id FROM discussions WHERE id1 = :id1 and id2 = :id1 and pseudo1 = :pseudo1 and pseudo2 = :groupe and clef = :clef and groupe = 1');
		$req->execute(array(
							'id1' => $_SESSION['id'],
							'pseudo1' => $_SESSION['pseudo'],
							'groupe' => $group_name,
							'clef' => $clef,
							));
		$donnees = $req->fetch();
		$iddisc = $donnees['id'];
		$tabmembres['iddisc'] = $iddisc;
		$req-> closeCursor();
		//intertion des id de groupe
		$req = $database->prepare('INSERT INTO groupe(iddisc, id1, id2, id3, id4, id5, id6, id7, id8, id9, id10, id11, id12, id13, id14, id15, id16, id17, id18, id19, id20, id21, id22, id23, id24, id25, id26, id27, id28, id29, id30) VALUES (:iddisc, :id1, :id2, :id3, :id4, :id5, :id6, :id7, :id8, :id9, :id10, :id11, :id12, :id13, :id14, :id15, :id16, :id17, :id18, :id19, :id20, :id21, :id22, :id23, :id24, :id25, :id26, :id27, :id28, :id29, :id30)');
		$req->execute($tabmembres);
		//premier message pour éviter bug
		$message = "Bienvenue dans mon nouveau groupe !";
	    $encrypted = openssl_encrypt($message, "AES-128-ECB" , $clef);
		$req = $database->prepare('INSERT INTO msg(iddisc, idenv, contenu, latest) VALUES(:iddisc, :idenv, :contenu, :latest)');
		$req->execute(array(
							'iddisc' => $iddisc,
							'idenv' => $_SESSION['id'],
							'contenu' => $encrypted,
							'latest' => 1
							));
		return $iddisc;
	}

//========================================================== main ========================================================//

	if (isset($_POST['nom']) and $_POST['nom'] != '') //nom groupe défini
	{
		$nom_groupe = htmlspecialchars($_POST['nom']);
		$nom_groupe = preg_replace("#^ *([^ ].*[^ ]) *$#", "$1", $nom_groupe);
		$nom_groupe = preg_replace("# {2,}#", ' ', $nom_groupe);
		if ($nom_groupe != '' and $nom_groupe != ' ' and strlen($nom_groupe) <= 20) //nom du groupe conforme
		{
			include("bdd/bdd.php");
			if (correct($bdd)) //vérification du nombre et de la conformité des séléctions
			{
				$id = ajouter($bdd, $nom_groupe);
				header("Location: accueil.php?conv=$id");
			}
			else
			{
				header("Location: ajout_groupe.php");
			}
		}
		else
		{
			header("Location: ajout_groupe.php");
		}
	}
	else
	{
		header("Location: ajout_groupe.php");
	}
?>