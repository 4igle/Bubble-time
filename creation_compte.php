<?php
header("X-FRAME-OPTIONS: DENY"); 
if (isset($_POST['pseudo']) and $_POST['pseudo'] != '' and !(preg_match("# #", $_POST['pseudo'])) and isset($_POST['mdp']) and $_POST['mdp'] != '' and !(preg_match("# #", $_POST['mdp'])) and isset($_POST['mdp_conf']) and $_POST['mdp_conf'] != '' and isset($_POST['mail']) and $_POST['mail'] != '' and !(preg_match("# #", $_POST['mdp_conf'])) and !(preg_match("# #", $_POST['mail'])))
{
	$_POST['pseudo'] = htmlspecialchars($_POST['pseudo']);
	$_POST['mdp'] = htmlspecialchars($_POST['mdp']);
	$_POST['mdp_conf'] = htmlspecialchars($_POST['mdp_conf']);
	$_POST['mail'] = htmlspecialchars($_POST['mail']);
	if ($_POST['mdp_conf'] == $_POST['mdp'])
	{
		if (strlen($_POST['pseudo']) <= 15 and strlen($_POST['mdp']) <= 250 and strlen($_POST['mdp']) > 5 and strlen($_POST['mail']) <= 250)
		{
			if (preg_match("#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#", $_POST['mail']) and !preg_match("#xn[-]{2}#", $_POST['mail']) and !preg_match("#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#", $_POST['pseudo']) and preg_match("#^[a-zA-Z0-9_éàèùâêîôûäëïöü-]+$#", $_POST['pseudo']))
			{
				include("bdd/bdd.php");
				
				$req = $bdd->prepare('SELECT pseudo, mail FROM utilisateurs WHERE pseudo = :pseudo OR mail = :mail');
				$req->execute(array(
									'pseudo' => $_POST['pseudo'],
									'mail' => $_POST['mail']
									));
				$donnees = $req->fetch();	
				if (empty($donnees))
				{
					$mdp = $_POST['mdp'];
					$_POST['mdp'] = password_hash("$mdp", PASSWORD_DEFAULT);
					$req->closeCursor();
					$req = $bdd->prepare('INSERT INTO utilisateurs(pseudo, mdp, mail) VALUES(:pseudo, :mdp, :mail)');
					$req->execute(array(
											'pseudo'=>$_POST['pseudo'],
											'mdp'=>$_POST['mdp'],
											'mail'=>$_POST['mail']
											));
					$s = 'Compte créé avec succès, vous pouvez vous connecter';
					header("Location: menu.php?message=$s");
				}
				else
				{
					$req->closeCursor();
					$s = 'Pseudo ou e-mail est déjà pris';
					header("Location: inscription.php?erreur=$s");
				}
			}
			else
			{
				$s = 'E-mail ou pseudo non conforme';
				header("Location: inscription.php?erreur=$s");
			}
		}
		else
		{
		$s = 'Taille d\'un des champs non valide';
		header("Location: inscription.php?erreur=$s");
		}
	}
	else
	{
		$s = 'Confirmation de mot de passe non valide';
		header("Location: inscription.php?erreur=$s");
	}
}
else
{
	$s = 'Veuillez saisir toutes les données et ne pas utiliser d\'espaces';
	header("Location: inscription.php?erreur=$s");
}
?>