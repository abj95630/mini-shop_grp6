<?php
require_once('connexiondb.php');



if(isset($_POST['deconnexion'])) {
    session_unset();
    session_destroy();
    header("Location:connexion.php");
    exit;
}
require_once('include/header.php');
require_once('include/navBar.php');
echo "Bonjour, bienvenue dans la boutique e-commerce X-GAMING";
?>

    <body>
        <form method="post" actionb="">
            <button type="submit" name="deconnexion">Déconnexion</button>
        </form>
        <?php 
    require_once('include/footer.php');
    ?>