<?php
	include('mdp.php');
	try
	{
	    $bdd = new PDO('mysql:host=' . $hostbdd . ';dbname=' . $namebdd . ';charset=utf8', $idbdd, $mdpbdd, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
	}
	catch (Exception $e)
	{
	    die('Erreur : ' . $e->getMessage());
	}
?>
