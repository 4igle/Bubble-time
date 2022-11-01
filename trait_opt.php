<?php
	session_start();
	header("X-FRAME-OPTIONS: DENY");
	$_POST['options'] = (int) $_POST['options'];
	if (!(isset($_SESSION['id'])) or !(isset($_SESSION['pseudo'])))
	{
		$_SESSION = array();
		session_destroy();
		header("Location: menu.php");
	}
	elseif ($_POST['options'] == 1)
	{
		$_SESSION = array();
		session_destroy();
		setcookie('id', '');
		setcookie('mdp', '');
		header("Location: menu.php");
	}
	elseif ($_POST['options'] == 2)
	{
		header("Location: ajout_groupe.php");
	}
	else
	{
		header("Location: accueil.php");
	}
?>