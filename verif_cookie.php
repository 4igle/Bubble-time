<?php
	header("X-FRAME-OPTIONS: DENY");
    $id = (int) $_COOKIE['id'];
    $mdp = htmlspecialchars($_COOKIE['mdp']);
    include("bdd/bdd.php");

	$req = $bdd->prepare('SELECT * FROM utilisateurs WHERE id = :id');
    $req->execute(array(
    					'id' => $id
    					));
    $donnees = $req->fetch();
    if (!is_null($donnees['cookie']))
    {
	    $clef = $donnees['cookie'];
	    $hash_cookie = openssl_decrypt($mdp, "AES-128-ECB" , $clef);
	    if ($hash_cookie == $donnees['mdp'])
	    {
	    	$pseudo = $donnees['pseudo'];
	    	$req->closeCursor();
	    	session_start();
			$_SESSION['id'] = $id;
			$_SESSION['pseudo'] = $pseudo;
			header("Location: accueil.php");
	    } 
	    else
	    {
	    	$req->closeCursor();
	    }
	}
	else
	{
		$req->closeCursor();
	}
?>