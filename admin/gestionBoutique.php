<?php 
require_once('../connexiondb.php');

$erreur = false;
$erreurCategorie = false;

$requeteCategorie = $bdd->query("SELECT * FROM categorie");

//  si non déclarée ou Vide
if(!isset($_POST['nom_produit']) ||  empty($_POST['nom_produit'])){
    $erreur = true;
}
if(!isset($_POST['nom']) ||  empty($_POST['nom'])){
    $erreurCategorie = true;
}
if(!isset($_POST['description']) || empty($_POST['description'])){
    $erreur = true;
}
if(!isset($_POST['prix']) || empty($_POST['prix'])){
    $erreur = true;
}
if(!isset($_POST['stock']) || empty($_POST['stock'])){
    $erreur = true;
}
if(!isset($_POST['categorie']) || empty($_POST['categorie']) ){
    $erreur = true;
}
$photoBdd = '';
    // Si l'indice 'name' dans la superglobale $_FILES est différent de vide, cela veut dire que l'utilisateur a bien uploader une photo
if(!empty($_FILES['photo']['name']))
{
    // on concatène le nom saisie dans le formulaire avec le nom de la photo recupéré dans la superglobale $_FILES
    $nomPhoto = $_POST['nom_produit'] . '-' . $_FILES['photo']['name'];

    // On définit l'URL de la photo jusqu'au dossier 'photo' sur le serveur, c'est ce qu'on enregistrera dans la BDD
    $photoBdd = "http://localhost/mini-shop_grp6/image/$nomPhoto";

    // On définit le chemin physique de la photo du dossier 'photo' sur le serveur, c'est ce que l'on utilisera pour copier la photo dans le dossier 'photo'
    $photoDossier = $_SERVER['DOCUMENT_ROOT']. "/mini-shop_grp6/image/$nomPhoto";

    //------------ TRAITEMENT EXTENSION PHOTO 
    $listExt = [1 => '.jpg', 2 => '.png', 3 => '.jpeg', 4 => '.PNG'];
    $positionPhoto = strpos($_FILES['photo']['name'], '.'); // on trouve à quel position se trouve le '.' dans le nom de la photo
    $decoupeExt = substr($_FILES['photo']['name'], $positionPhoto); // on recupère l'extension
    // array_search() : fonction prédéfinie permettant de trouver à quel indice se trouve une donnée dans un tableau ARRAY
    $extension = array_search($decoupeExt, $listExt);
    // Si la position de l'extension (indice) dans le tableau ARRAY n'est pas trouvé, array_search() retourne FALSE, on affiche un message d'erreur
    if($extension == false)
    {
        $erreurPhoto = '<p class="font-italic text-danger">Format photo non pris en compte</p>';
        $erreur = true;
    }

    // copy() : fonction prédéfinie permettant de copier la photo dans le dossier 'photo' sur le serveur
    // arguments : copy('nom_temporaire', 'chemin de la photo vers le dossier photo')
    // Si la valeur de $extension est différente de FALSE, cela veut dire que l'extension a bien été trouvé dans le tableau ARRAY $listExt, alors on copie la photo
    if($extension !== false)
    {
        copy($_FILES['photo']['tmp_name'], $photoDossier); 
    }
    
}
// Si aucune erreur
if($erreur == false){
    // Si danss l'url il y a ajoutProduit
    if(isset($_GET['action']) && $_GET['action'] == 'ajoutProduit'){
        // Prepare requête
        $insertProduit = $bdd->prepare("INSERT INTO produit (nom_produit, description, image, prix, stock) VALUES (:nom_produit, :description, :image, :prix, :stock)") ;
        $insertProduit->bindValue(':nom_produit', $_POST['nom_produit'], PDO::PARAM_STR);
        $insertProduit->bindValue(':description', $_POST['description'], PDO::PARAM_STR);
        $insertProduit->bindValue(':image', $photoBdd, PDO::PARAM_STR);
        $insertProduit->bindValue(':prix', $_POST['prix'], PDO::PARAM_INT);
        $insertProduit->bindValue(':stock', $_POST['stock'], PDO::PARAM_INT);
        $insertProduit->execute();
        // Exécute
        // Récupère l'index
        $indexProduit = $bdd -> lastInsertId(); 
        // Pour lier les catégories selectionnées avec le produit
        foreach($_POST['categorie'] as $valueCategorie){
            $insertCategorieProduit = $bdd->prepare("INSERT INTO produit_categorie(id_produit, id_categorie) VALUES ($indexProduit, :id_categorie)");
            $insertCategorieProduit->bindValue(':id_categorie', $valueCategorie,PDO::PARAM_INT);
            $insertCategorieProduit->execute();
        }
        // Affiche la liste des produits
        $_GET['action'] = 'affichageProduit';
        $validInsert = "<p class='col-md-5 mx-auto alert alert-secondary text-center'>Le produit a bien été enregistré !</p>";         
    }



    // else{

    //     $insertProduit = $bdd->prepare("UPDATE produit SET nom = :nom ,description = :description, :prix,stock = :stock WHERE id_produit = :id_produit");
    //     $insertProduit->bindValue(':id_produit',  $_GET['id_produit'], PDO::PARAM_INT);
    //     $insertProduit->bindValue(':nom', $_POST['nom_produit'], PDO::PARAM_STR);
    //     $insertProduit->bindValue(':description', $_POST['description'], PDO::PARAM_STR);
    //     $insertProduit->bindValue(':prix', $_POST['prix'], PDO::PARAM_INT);
    //     $insertProduit->bindValue(':stock', $_POST['stock'], PDO::PARAM_INT);
    //     $insertProduit->execute();

    //     $_GET['action'] = 'affichageProduit';

    //     $validUpdate = "<p class='col-md-5 mx-auto alert alert-secondary text-center'>Le produit a bien été modifié !</p>"; 
    // }
}
 // Si aucune erreur
if($erreurCategorie == false){
    // Si dans l'url il y a ajoutCategorie
    if(isset($_GET['action']) && $_GET['action'] == 'ajoutCategorie'){
        // Prépare la requête
        $insertCategorie = $bdd->prepare("INSERT INTO categorie (nom_categorie) VALUES (:nom)") ;
        $insertCategorie->bindValue(':nom', $_POST['nom'], PDO::PARAM_STR);
        $insertCategorie->execute(); // Execute
        // Affiche la liste des catégories
        $_GET['action'] = 'affichageCategorie';
    }
}
// Si dans l'url il y a suppression
if(isset($_GET['action']) && $_GET['action'] == 'suppression')
{
    $deleteProduitCategorie = $bdd->prepare("DELETE FROM produit_categorie WHERE id_produit = :id_produit");
    $deleteProduitCategorie->bindValue(':id_produit', $_GET['id_produit'], PDO::PARAM_INT);
    $deleteProduitCategorie->execute();
    $deleteProduit = $bdd->prepare("DELETE FROM produit WHERE id_produit = :id_produit");
    $deleteProduit->bindValue(':id_produit', $_GET['id_produit'], PDO::PARAM_INT);
    $deleteProduit->execute();

    $_GET['action'] = 'affichageProduit';

    $validDelete = "<p class='col-md-5 mx-auto alert alert-success text-center'>Le produit ID <strong>$_GET[id_produit]</strong> a bien été supprimé !</p>";
}
require_once('../include/header.php');
?>

<ul class="col-md-4 list-group text-center mt-3 mx-auto">
    <li class="list-group-item bg-secondary">BACK OFFICE</li>
    <li class="list-group-item"><a href="?action=affichageProduit" class="text-dark">AFFICHAGE PRODUITS</a></li>
    <li class="list-group-item"><a href="?action=affichageCategorie" class="text-dark">AFFICHAGE CATEGORIE</a></li>
    <li class="list-group-item"><a href="?action=ajoutProduit" class="text-dark">AJOUT PRODUIT</a></li>
    <li class="list-group-item"><a href="?action=ajoutCategorie" class="text-dark">AJOUT CATEGORIE</a></li>
</ul>

<?php 
// Affichage Produit

if(isset($_GET['action']) && $_GET['action'] == 'affichageProduit'){
    $resultat = $bdd->query("SELECT *
                            FROM produit");

    if(isset($validDelete)) echo $validDelete;
    if(isset($validInsert)) echo $validInsert; 
    if(isset($validUpdate)) echo $validUpdate;

    echo '<h1 class="display-4 text-center mt-3">Affichage des produits</h1><hr>';   
    echo '<p class="text-center">Nombre de produit(s) dans la boutique : <span class="badge badge-info">' . $resultat->rowCount() . '</span></p>';

    echo '<table class="table table-bordered text-center mb-3"><tr>';
    // On ecrit les noms des colonnes présente dans la table produit
    for($i = 0; $i < $resultat->columnCount(); $i++){
        $colonne = $resultat->getColumnMeta($i);
        echo "<th>$colonne[name]</th>";
    }
        // On rajoute une colonne catégorie, modif, supp
        echo '<th>Catégories</th>';
        echo '<th>Modif</th>';
        echo '<th>Supp</th>';
    echo '</tr>';
    while($produit = $resultat->fetch(PDO::FETCH_ASSOC)){
        echo '<tr>';
        foreach($produit as $key => $value){
            if($key == 'image'){ // Si la clé est sur 'image' on met le lien dans une balise image
                echo "<td><img src='$value' alt='' style='width: 150px;' class='rounded border border-secondary img-thumbnail'></td>";
            }               
            else{
                echo "<td>$value</td>"; 
            }
                
            if($key == "id_produit"){ // Récupère l'id pour afficher les catégories lié a cette id par la suite lors de la requête
                $id_produit = $value;
            }
        }
        $resultatCategorie = $bdd->query("SELECT C.nom_categorie 
                                            FROM produit as P, categorie as C, produit_categorie as PC
                                            WHERE P.id_produit = PC.id_produit AND PC.id_categorie = C.id_categorie
                                            AND P.id_produit = $id_produit");
        $tab = []; // Création d'un tableau
        while($produitCat = $resultatCategorie->fetch(PDO::FETCH_ASSOC))
        {
            foreach($produitCat as $key => $value)
                $tab [] = $value; // on y stock les catégories
        }
        echo "<td>";
        for($i=0; $i < count($tab); $i++){
            echo "$tab[$i] <br>"; // Affichage des éléments du tableau avec une boucle
        }
        echo "</td>";

        echo "<td><a href='?action=modification&id_produit=$produit[id_produit]' class='text-info'><i class='fas fa-edit'></i></a></td>";
        // Bouton pour modifier et on passe dans l'URL action=modification et l'id du produit selectionné
        echo "<td><a href='?action=suppression&id_produit=$produit[id_produit]' class='text-danger'><i class='fas fa-trash-alt' onclick='return(confirm(\" En êtes vous certain ? \"));'></i></a></td>";
        // Bouton pour supprimer et on passe dans l'URL action=modification et l'id du produit selectionné
        echo '</tr>';
    }
}
echo '</table>';

// Affichage Catégorie
if(isset($_GET['action']) && $_GET['action'] == 'affichageCategorie'){
    $resultatCategorie = $bdd->query("SELECT *
                            FROM categorie");
    echo '<h1 class="display-4 text-center mt-3">Affichage des categories</h1><hr>';   
    echo '<p class="text-center">Nombre de categorie(s) dans la boutique : <span class="badge badge-info">' . $resultatCategorie->rowCount() . '</span></p>';

echo '<table class="table table-bordered text-center mb-3"><tr>';
    for($i = 0; $i < $resultatCategorie->columnCount(); $i++){
        $colonneCategorie = $resultatCategorie->getColumnMeta($i);
        echo "<th>$colonneCategorie[name]</th>";
    }
        echo '<th>Modif</th>';
        echo '<th>Supp</th>';
    echo '</tr>';
    while($categorie = $resultatCategorie->fetch(PDO::FETCH_ASSOC)){
        echo '<tr>';
        foreach($categorie as $key => $value){          
            echo "<td>$value</td>";                
        }
        echo "<td><a href='?action=modification&id_produit=$categorie[id_categorie]' class='text-info'><i class='fas fa-edit'></i></a></td>"; 
        echo "<td><a href='?action=suppression&id_produit=$categorie[id_categorie]' class='text-danger'><i class='fas fa-trash-alt' onclick='return(confirm(\" En êtes vous certain ? \"));'></i></a></td>";
        echo '</tr>';
    }
}
echo '</table>';
?>

<body>
    <!-- Formulaire pour l'ajout d'un produit -->
    <?php if(isset($_GET['action']) && ($_GET['action'] == 'ajoutProduit' || $_GET['action'] == 'modification')): 

        if(isset($_GET['id_produit'])) {
            $resultat = $bdd->prepare("SELECT * FROM produit WHERE id_produit = :id_produit");
            $resultat->bindValue(':id_produit', $_GET['id_produit'], PDO::PARAM_INT);
            $resultat->execute();

            $produitActuel = $resultat->fetch(PDO::FETCH_ASSOC);
        }
        // On stock les valeurs pour pouvoir les rafficher lors de la modification par la suite
        $nom = (isset($produitActuel['nom_produit'])) ? $produitActuel['nom_produit'] : '';
        $description = (isset($produitActuel['description'])) ? $produitActuel['description'] : '';
        $prix = (isset($produitActuel['prix'])) ? $produitActuel['prix'] : '';
        $stock = (isset($produitActuel['stock'])) ? $produitActuel['stock'] : '';
    ?>
    <h3 class="display-4 text-center mt-3">Ajouter un produit</h3>
    <form method="post" enctype="multipart/form-data" class="col-md-6 mx-auto">
        <div class="form-group">
            <label for="nom">Nom</label>
            <input type="text" class="form-control" id="nom" name="nom_produit" placeholder="Saisir le nom du produit" value="<?=$nom?>">
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <input type="text" class="form-control" id="description" name="description" placeholder="Saisir la description du produit" value="<?=$description?>">
        </div>
        <div class="custom-file">
            <input type="file" class="custom-file-input" name="photo" id="photo" value="<?= $photo ?>">
            <label class="custom-file-label" for="photo" aria-describedby="photo">Choisir le fichier</label>
        </div>
        <div class="form-group">
            <label for="prix">Prix</label>
            <input type="text" class="form-control" id="prix" name="prix" placeholder="Saisir le prix du produit"value="<?=$prix?>">
        </div>
        <div class="form-group">
            <label for="stock">Stock</label>
            <input type="text" class="form-control" id="stock" name="stock" placeholder="Saisir le stock du produit" value="<?=$stock?>">
        </div>
        <div>

            <?php
            // On affiche toutes les catégories 
            while ($categorie = $requeteCategorie -> fetch()) {?>
                <input type="checkbox" name="categorie[]" value="<?= $categorie['id_categorie']?>"> 
                <label> <?= $categorie['nom_categorie'] ?></label><br>
            <?php } ?>
                
        </div>
        <button type="submit" class="col-md-12 btn btn-info">Ajouter produit</button>
    </form>
    <?php endif; ?>

    <!-- Formulaire pour l'ajout d'une catégorie -->
    <?php if(isset($_GET['action']) && ($_GET['action'] == 'ajoutCategorie' || $_GET['action'] == 'modificationCategorie')): ?>
    <h3 class="display-4 text-center mt-3">Ajouter une catégorie</h3>
    <form method="post" enctype="multipart/form-data" class="col-md-6 mx-auto">
    <div class="form-group">
        <label for="nom">Nom</label>
        <input type="text" class="form-control" id="nom" name="nom" placeholder="Saisir le nom de la catégorie">
    </div>
    <button type="submit" class="col-md-12 btn btn-info">Ajouter catégorie</button>
    </form>
    <?php endif; ?>
</body>
</html>