<?php
    // Appelation de la base de données
    require_once('connexiondb.php');
    // Fonctions pour afficher les erreurs
    error_reporting(E_ALL);
    ini_set('display_errors', TRUE);
    ini_set('display_startup_errors', TRUE);
    // Si une session est en cours on se redirige vers la page d'acceuil
    if(isset($_SESSION["mail"])) {
        header('Location: acceuil.php');
        exit;
    }

    if(!empty($_POST)) {
        $valid = true;
        // On se place sur le bon formulaire grâce au "name" de la balise "input"
        if(isset($_POST['inscription'])) {
            $prenom = $_POST['prenom']; // on récupère le prénom
            $mail = strtolower(trim($_POST['mail'])); // On récupère le mail
            $mdp = trim($_POST['mdp']); // On récupère le mot de passe 
            $confmdp = trim($_POST['confmdp']); //  On récupère la confirmation du mot de passe
 
            //  Vérification du nom
            if (empty($_POST['nom'])) {
                $valid = false;
                $er_nom = "Le nom d' utilisateur ne peut pas être vide";
            }     
 
            //  Vérification du prénom
            if(empty($prenom)){
                $valid = false;
                $er_prenom = "Le prenom d' utilisateur ne peut pas être vide";
            }     
            
            // Vérification du mail
            if(empty($mail)) {
                // On vérifit que le mail est dans le bon format
                $valid = false;
                $er_mail = "mail vide";
            } else if(!preg_match("/^[a-z0-9\-_.]+@[a-z]+\.[a-z]{2,3}$/i", $mail)) {
                $valid = true;
                $er_mail = "Le mail invalide";
            } else {
                try {
                    $req = $bdd->prepare("SELECT * FROM Utilisateur WHERE mail=?");
                    $req->execute([$mail]);
                    $utilsateur = $req->fetch();
                } catch (Exception $e) {
                    echo "Erreur";
                } 
                if($utilsateur) {
                    echo "Le mail existe déja";
                    $valid = false;
                }
            }

            // Vérification du mot de passe
            if(empty($mdp)) {
                $valid = false;
                $er_mdp = "Le mot de passe ne peut pas être vide";
            } else if($mdp != $confmdp) {
                $valid = false;
                $er_mdp = "La confirmation du mot de passe ne correspond pas";
            } 
 
            // Si toutes les conditions sont remplies alors on fait le traitement
            if($valid) {
                $mdp = hash("md5", $mdp);
                $date_creation = date('Y-m-d');
                // On insert nos données dans la table utilisateur
                try {
                    $query = $bdd->prepare("INSERT INTO Utilisateur (nom, prenom, mail, mdp, date_creation) VALUES (:nom, :prenom, :mail, :mdp, :date_creation)");
                    $query->bindValue(':nom', $_POST['nom']);
                    $query->bindValue(':prenom', $prenom);
                    $query->bindValue(':mail', $mail);
                    $query->bindValue(':mdp', $mdp);
                    $query->bindValue(':date_creation', $date_creation);
                    $query->execute();
                    $_SESSION['mail'] = $mail;
                    header('Location: acceuil.php');
                    exit;
                } catch (Exception $e) {
                    echo $e->getMessage();
                }
            } 
        } 
    }
    // On appel les fichiers pour l'en-tête et le bas de page
    require_once('include/header.php');
    require_once('include/navBar.php');
?>
    <body>      
    <div>Inscription</div>
    <form actionb="" method="post">


<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" 
        integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous"> 
        <link href="inscription.css" rel="stylesheet">
        <title>Inscription</title>
    </head>
    <body>      
        <div class="nom-inscription">Inscription</div>
        <form actionb="" method="post">
           <?php
            if (isset($er_nom)) {
            ?>
               <div><?php $er_nom ?></div>
            <?php    
            }
            ?>
           <input type="text" placeholder="Votre nom" name="nom">    
           <?php
            if (isset($er_prenom)){
            ?>
               <div><?= $er_prenom ?></div>
            <?php    
            }
           ?>
           <input type="text" placeholder="Votre prénom" name="prenom">    
           <?php
            if (isset($er_mail)){
            ?>
               <div><?= $er_mail ?></div>
            <?php    
            }
           ?>
           <input type="email" placeholder="Adresse mail" name="mail">
           <?php
            if (isset($er_mdp)){
            ?>
               <div><?= $er_mdp ?></div>
            <?php    
            }
           ?>
           <input type="password" placeholder="Mot de passe" name="mdp">
           <input type="password" placeholder="Confirmer le mot de passe" name="confmdp">
           <button type="submit" name="inscription">Envoyer</button>
    </form>
    <?php 
    require_once('include/footer.php');
    ?>
        </form>
    </body>
</html>
