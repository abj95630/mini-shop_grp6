<?php

require_once "connexion_db.php";


echo "Bonjour, bienvenue dans la boutique e-commerce X-GAMING";
if(isset($_POST['deconnexion'])) {
    session_unset();
    session_destroy();
    header("Location:connexion.php");
    exit;
}

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Acceuil</title>
    </head>
    <body>
        <form method="post" actionb="">
            <button type="submit" name="deconnexion">Déconnexion</button>
        </form>
    </body>
</html>