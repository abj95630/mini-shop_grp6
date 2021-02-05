<?php

session_start();

// Base de données
// try {
//       $bdd = new PDO('mysql:host=https://2eurhost.com/db/;dbname=produits;charset=utf8', 'groupe6', 'Cj1h8%m9');
// } catch (Exception $e) {
//       die('Erreur'.$e->getMessage());
// }
require_once('include/header.php');
require_once('include/navBar.php');
if(isset($_GET['articles_list']) && !empty($_GET['articles_list'])) {

    // Traitement du formulaire
    $new_articles = $_GET['articles_list'];

    // Traitement de la session
    if(isset($_SESSION['articles'])) {
        $articles = $_SESSION['articles'];
    } else {
        $articles = array();
    }

    foreach ($new_articles as $article) {

        // Si il existe déjà
        if(isset($articles[$article])) {

            $articles[$article] = $articles[$article] + 1;

        } else {

            $articles[$article] = 1;

        }

    }

    // On le sauvegarde
    $_SESSION['articles'] = $articles;

    print_r($articles);

}

/**
 * AFFICHAGE
 */
?>
<!DOCTYPE html>
<html>

<head>
    <title>Minishop</title>
    <meta charset="utf-8" />

    <!-- CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link href="design.css" rel="stylesheet">
</head>

<body>

<div class="container">

    <!-- Afficher le contenu d'artciles -->
    <!-- <pre><?php print_r($_SESSION['articles']); ?></pre> -->

    <div class="col-md-6">

        <form action="" method="get" id="articles">

            <h1>Catalogue</h1>

            <hr>

            <div>
                <!-- Liste d'articles disponibles -->
                <label for="articles_list">Ajouter un article</label>
                <select name="articles_list[]" id="articles_list" class="form-control" multiple>
                    <option value="Doom Eternal">Doom Eternal</option>
                    <option value="Minecraft">Minecraft</option>
                    <option value="Monster Hunter World">Monster Hunter World</option>
                    <option value="Dragon Ball Xenoverse 2">Dragon Ball Xenoverse 2</option>
                    <option value="Dark Soul 3">Dark Soul 3</option>
                    <option value="Bloodborne">Bloodborne</option>
                    <option value="Starcraft 2">Starcraft 2</option>
                    <option value="Hollow Knight">Hollow Knight</option>
                </select>
            </div>

            <hr>

            <div class="text-right">
                <button type="submit" class="btn btn-primary">Ajouter!</button>
            </div>

        </form>

    </div>

    <div class="col-md-6">

        <h1>Panier</h1>

        <hr>

        <form action="" method="get">

            <?php if(isset($_SESSION['articles'])) { ?>

                <ul>
                    <?php foreach ($_SESSION['articles'] as $name=>$quantite) { ?>
                        <li>
                            <?php echo $name;  ?> - x<?php echo $quantite; ?>
                            <!-- Boutons pour changer la quantité d'un article -->
                            <!-- <input type="submit" class="btn btn-danger" name="<?php echo $name;  ?>" value="-">
                            <input type="submit" class="btn btn-success" name="<?php echo $name;  ?>" value="+"> -->
                        </li>
                    <?php } ?>
                </ul>

            <?php } else { ?>

                <h3>Aucun article</h3>

            <?php } ?>

        </form>

    </div>

</div>
    <div>
        <p class="valider"> 
        <a href="commande.php">Valider votre commande</a>
    </p>
    </div>   
</body>

</html>