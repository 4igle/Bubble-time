<?php header("X-FRAME-OPTIONS: DENY"); ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <title>Inscription</title>
        <link rel="stylesheet" href="css/map.css">
        <link rel="stylesheet" href="css/phone_inscription.css">
    </head>
    <body>

        <?php       
        if (isset($_GET['erreur']) and $_GET['erreur'] != '')
        { ?>
        <div class="message_rouge_url"><?php echo "<p class='msg_rouge'>" . htmlspecialchars($_GET['erreur']) . '</p>' ?></div>
        <?php } ?>


        <div class="creation">          
            <h1>Bubble time</h1>
            <h2>Inscription</h2>
            <form method="POST" action="creation_compte.php" class="creation">
                <input type="text" name="pseudo" placeholder="Pseudo" class="champ" required maxlength="15">
                <input type="email" name="mail" placeholder="E-mail" class="champ" required maxlength="250">
                <input type="password" name="mdp" placeholder="Mot de passe" class="champ" required maxlength="250">
                <input type="password" name="mdp_conf" placeholder="Confirmation du mot de passe" class="champ" required maxlength="250">
                <input type="submit" name="connexion" value="Inscription" class="bouton">
            </form>
        </div>
    </body>
</html>