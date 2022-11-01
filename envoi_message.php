<?php
	session_start();
	header("X-FRAME-OPTIONS: DENY");
	if (!(isset($_SESSION['id'])) or !(isset($_SESSION['pseudo'])))
	{
		$_SESSION = array();
		session_destroy();
		header("Location: menu.php");
	}

	if (isset($_GET['type']))
	{
		$_GET['type'] = htmlspecialchars($_GET['type']);
	}

	if (isset($_GET['conv']))
	{
		$_GET['conv'] = (int) $_GET['conv'];
		include("bdd/bdd.php");

		$req = $bdd->query('SELECT COUNT(*) AS nbdisc FROM discussions');
		$donnees = $req->fetch();
		$nbdisc = $donnees['nbdisc'];
		$req-> closeCursor();
		if ($_GET['conv'] >= 1 and $_GET['conv'] <= $nbdisc) //id de conv valide
		{
			include('query/query2.php');
    		$req = $bdd->prepare($query2);
    		$req->execute(array(
    							'iddisc' => $_GET['conv'],
			    				'id' => $_SESSION['id']
    							));
    		$donnees = $req->fetch();
    		if (empty($donnees)) //utilisateur pas dans le groupe
    		{
    			$req->closeCursor();
    			$_SESSION = array();
				session_destroy();
				setcookie('id', '');
				setcookie('mdp', '');
				header("Location: menu.php");
    		}
    		elseif ($donnees['id1'] != $_SESSION['id'] and $donnees['id2'] != $_SESSION['id'] and !$donnees['groupe']) 
    		//conv n'appartenant pas à l'utilisateur
    		{
    			$req->closeCursor();
    			$_SESSION = array();
				session_destroy();
				setcookie('id', '');
				setcookie('mdp', '');
				header("Location: menu.php");
    		}
    		else //conforme, envoi du message
    		{
				if (htmlspecialchars($_POST['message']) == '' or !isset($_POST['message']) or preg_match('#^ +$#', $_POST['message'])) 
    			{
    				$req->closeCursor();
    				$conv = $_GET['conv'];
    				if (isset($_GET['type']))
    				{
    					$conv = $conv . '&type=' . $_GET['type'];
    				}
    				header("Location: accueil.php?conv=$conv");
    			}
    			else
    			{
    				$groupe = $donnees['groupe'];
    				$clef = $donnees['clef'];
	    			$req->closeCursor();
	    			$_POST['message'] = htmlspecialchars($_POST['message']);
	    			$_POST['message'] = preg_replace("#(https?://)?([a-z0-9._-]+\.[a-z]{2,4}(/([a-zA-Z0-9._?=-]|&amp;)*)*)#", '<a href="http://$2">$1$2</a>', $_POST['message']);

	    			$message = $_POST['message'];
	    			$encrypted = openssl_encrypt($message, "AES-128-ECB" , $clef);
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	    			//à faire même pour un groupe
		    		$req = $bdd->prepare('UPDATE msg SET latest = 0 WHERE latest = 1 AND iddisc = :iddisc');
		    		$req->execute(array(
		    							'iddisc' => $_GET['conv']
		    							));
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		    		$req = $bdd->prepare('INSERT INTO msg(iddisc, idenv, contenu, latest) VALUES(:iddisc, :idenv, :contenu, :latest)');
		    		$req->execute(array(
		    							'iddisc' => $_GET['conv'],
		    							'idenv' => $_SESSION['id'],
		    							'contenu' => $encrypted,
		    							'latest' => 1
		    							));
		    		//latest et latest env à garder, pour les nons lus de groupe, faire un compteur des id du dernier message non lu dans la table des groupes
		    		$req = $bdd->prepare('UPDATE discussions SET latest = NOW(), non_lus = non_lus + 1, latest_env = :latest_env WHERE id = :id');
		    		$req->execute(array(
		    							'latest_env' => $_SESSION['id'],
		    							'id' => $_GET['conv']
		    							));
		    		if ($groupe == 1) //mettre +1 aux non lus
		    		{
		    			$req = $bdd->prepare('UPDATE groupe SET non_lu1 = non_lu1 + 1, non_lu2 = non_lu2 + 1, non_lu3 = non_lu3 + 1, non_lu4 = non_lu4 + 1, non_lu5 = non_lu5 + 1, non_lu6 = non_lu6 + 1, non_lu7 = non_lu7 + 1, non_lu8 = non_lu8 + 1, non_lu9 = non_lu9 + 1, non_lu10 = non_lu10 + 1, non_lu11 = non_lu11 + 1, non_lu12 = non_lu12 + 1, non_lu13 = non_lu13 + 1, non_lu14 = non_lu14 + 1, non_lu15 = non_lu15 + 1, non_lu16 = non_lu16 + 1, non_lu17 = non_lu17 + 1, non_lu18 = non_lu18 + 1, non_lu19 = non_lu19 + 1, non_lu20 = non_lu20 + 1, non_lu21 = non_lu21 + 1, non_lu22 = non_lu22 + 1, non_lu23 = non_lu23 + 1, non_lu24 = non_lu24 + 1, non_lu25 = non_lu25 + 1, non_lu26 = non_lu26 + 1, non_lu27 = non_lu27 + 1, non_lu28 = non_lu28 + 1, non_lu29 = non_lu29 + 1, non_lu30 = non_lu30 + 1 WHERE iddisc = :iddisc');
		    			$req->execute(array(
			    							'iddisc' => $_GET['conv']
			    							));
		    		}

		    		$conv = $_GET['conv'];
		    		if (isset($_GET['type']))
    				{
    					$conv = $conv . '&type=' . $_GET['type'];
    				}
		    		header("Location: accueil.php?conv=$conv");
	    		}
    		}
    	}
    	else //id de conv non valide
    	{
    		$_SESSION = array();
			session_destroy();
			setcookie('id', '');
			setcookie('mdp', '');
			header("Location: menu.php");
    	}
    }
    else //pas de conv séléctionnée
    {
    	$_SESSION = array();
		session_destroy();
		setcookie('id', '');
		setcookie('mdp', '');
		header("Location: menu.php");
    }
?>