<?php
header("X-FRAME-OPTIONS: DENY");
if (isset($_POST['pseudo_mail']) and $_POST['pseudo_mail'] != '' and isset($_POST['mdp']) and $_POST['mdp'] != '')
{
	$_POST['pseudo_mail'] = htmlspecialchars($_POST['pseudo_mail']);
	$_POST['mdp'] = htmlspecialchars($_POST['mdp']); //penser à tester avec par exemple un pseudo de 40 caractères
	include("bdd/bdd.php");
	
	$req = $bdd->prepare('SELECT id, pseudo, mdp, mail, cookie FROM utilisateurs WHERE pseudo = :pseudo OR mail = :mail');
	$req->execute(array(
						'pseudo' => $_POST['pseudo_mail'],
						'mail' => $_POST['pseudo_mail']
						));
	$donnees = $req->fetch();	
	if (empty($donnees))
	{
		$req->closeCursor();
		$s = 'Compte inexistant';
		header("Location: menu.php?erreur=$s");
	}
	else
	{
		if (password_verify($_POST['mdp'], $donnees['mdp']))
		{			
			session_start();
			$_SESSION['id'] = $donnees['id'];
			$id = $donnees['id'];
			$_SESSION['pseudo'] = $donnees['pseudo'];
			$hash = $donnees['mdp'];
			if (isset($_POST['connexion_auto']))
			{
				if (!is_null($donnees['cookie']))
				{
					$bytes = $donnees['cookie']; //recup ancienne clef
					$req->closeCursor();
				}
				else 
				{
					$req->closeCursor();
					$bytes = bin2hex(random_bytes(20)); //pas de clef avant, premiere connection auto
					$req = $bdd->prepare('UPDATE utilisateurs SET cookie = :cookie WHERE id = :id');
					$req->execute(array(
										'cookie' => $bytes,
										'id' => $id
										));
				}
				$pass = openssl_encrypt($hash, "AES-128-ECB" , $bytes);
				setcookie('id', $id, time() + 365*24*3600, null, null, false, true);
				setcookie('mdp', $pass, time() + 365*24*3600, null, null, false, true);			
			}
			header("Location: accueil.php");
		}
		else
		{
			$req->closeCursor();
			$s = 'Mot de passe incorrect';
			header("Location: menu.php?erreur=$s");
		}

	}
}
else
{
	$s = 'Veuillez entrer toutes les données';
	header("Location: inscription.php?erreur=$s");
}
?>