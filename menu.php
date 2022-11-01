<?php session_start(); 
    if ((isset($_SESSION['id'])) and (isset($_SESSION['pseudo'])))
    {
        header("Location: accueil.php");
    }
    if (isset($_COOKIE['id']) and isset($_COOKIE['mdp']))
    {
        if ($_COOKIE['id'] != "" and $_COOKIE['mdp'] != "")
        {
            include('verif_cookie.php');
        }
    }
?>
<?php header("X-FRAME-OPTIONS: DENY"); ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>Bubble time : connexion</title>
        <link rel="icon" type="image/png" sizes="16x16" href="images/logo.png">
        <link rel="stylesheet" href="css/map.css">
        <link rel="stylesheet" href="css/phone_entree.css">
        <script type="text/javascript" src="script/phone_entree.js" defer></script>
    </head>
    <body>


        <?php       
        if (isset($_GET['message']) and $_GET['message'] != '' and (!isset($_GET['erreur']) or $_GET['erreur'] == ''))
        { ?>
        <div class="message_vert_url"><?php echo "<p class='msg_vert'>" . htmlspecialchars($_GET['message']) . '</p>' ?></div>
        <?php } 

        if (isset($_GET['erreur']) and $_GET['erreur'] != '' and (!isset($_GET['message']) or $_GET['message'] == ''))
        { ?>
        <div class="message_rouge_url"><?php echo "<p class='msg_rouge'>" . htmlspecialchars($_GET['erreur']) . '</p>' ?></div>
        <?php }
        ?>



        <div class="main">
            <div class="logo">
                <img src="images/logo.png" class="logo">
            </div>
            <div class="formulaire">
                <div class="connexion">
                    <h1>Bubble time</h1>
                    <form method="POST" action="verif.php" class="connexion">
                            <input type="text" name="pseudo_mail" placeholder="Pseudo ou e-mail" required class="champ">
                            <input type="password" name="mdp" placeholder="Mot de passe" required class="champ">
                            <input type="submit" name="connexion" value="Connexion" class="bouton">
                            <div id="box">
                                <label style="font-family: arial, sans-serif;" for="connexion_auto"> Connexion auto : </label>
                                <input type="checkbox" checked name="connexion_auto" id="connexion_auto" style="margin-left: 176px;">
                            </div>
                    </form>
                </div>
                <div class="inscription">
                    <p class="inscription1">Vous n'avez pas de compte ?</p>
                    <p class="inscription2"><a href="inscription.php">Inscrivez-vous</a></p>
                </div>
            </div>
        </div>
    </body>
</html>