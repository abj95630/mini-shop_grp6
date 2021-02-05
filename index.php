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


<div class="col-lg-9">
    <div class="row">

        <?php 

        $data = $bdd->prepare("SELECT * FROM produit");

        $data->execute();
        ?>

        <?php while($produit = $data->fetch(PDO::FETCH_ASSOC)): ?>
        <div class="col-lg-6 col-md-5 mb-5 mt-5">
            <div class="card h-100">
                <img class="card-img-top" src="<?= $produit['image']?>" alt=""style="width:200px;margin:auto;">
                <div class="card-body">
                    <h4 class="card-title  text-center">
                        <p class="text-dark"><?= $produit['nom_produit'] ?></p>
                    </h4>
                    <h5 class="text-center"><?= $produit['prix'] ?>€</h5>
                    <p class="card-text text-center"><?= $produit['description'] ?></p>
                </div>
                <div class="card-footer text-center">
                    <a href="" class="btn btn-info">Ajouter au panier</a>
                </div>
            </div>
        </div>
    
    <?php endwhile; ?>
    </div>
    </div>
    <?php 
    require_once('include/footer.php');
    ?>