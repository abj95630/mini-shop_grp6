<?php
require_once('connexiondb.php');

if(isset($_SESSION['mail'])) {
    header('Location:index.php');
    exit;
}

if(!empty($_POST)) {
    $valid = true;

   if(isset($_POST['connexion'])) {
        $mail = strtolower(trim($_POST['mail']));
        $mdp = trim($_POST['mdp']);

        if(empty($mail)) {
            $valid = false;
            $er_mail = "Il faut mettre un mail";
        }

        if(empty($mdp)) {
            $valid = false;
            $er_mail = "Il faut mettre un mot de passe";
        }
        try {
            $req_mail = $bdd->prepare("SELECT * FROM Utilisateur WHERE mail=?");
            $req_mail->execute([$mail]);
            $verif_mail = $req_mail->fetch();
            $req_mdp = $bdd->prepare("SELECT * FROM Utilisateur WHERE mdp=?");
            $req_mdp->execute([hash("md5",$mdp)]);
            $verif_mdp = $req_mdp->fetch();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
        
        if($verif_mail && $verif_mdp) {
            $valid = true;
        } else {
            $valid = false;
            echo "Mail ou mot de passe incorrecte";
        }

    } 
    if($valid) {
        $_SESSION['mail'] = $mail;
        header('Location: index.php');
        exit;
    }

}
require_once('include/header.php');
require_once('include/navBar.php');
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" 
    integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous"> 
    <link href="connexion.css" rel="stylesheet">  
    <title>Connexion</title>
</head>
<body>
    <div class="nom-connexion">Connexion</div>
    <form method="post">
        <?php
            if(isset($er_mail)) {
        ?>  
                <div><?= $er_mdp ?></div>    
        <?php    
            }
        ?>

        <input type="email" placeholder="Adresse mail" name="mail" value="<?php if(isset($mail)){echo $mail; }?>" required>
    
        <?php 
            if(isset($er_mdp)) {
        ?>
                <div><?=$er_mdp?></div>
        <?php
            }
        ?>

        <input type="password" placeholder="Mot de passe" name="mdp" value="<?php if(isset($mdp)){echo $mdp;}?>" required>
        <button type="submit" name="connexion">Se connecter</button>
        <p class="s_inscrire">Pas inscrit ? Cliquez<a href="inscription.php"> Ici </a></p>
    </form>
<?php 
require_once('include/footer.php');
?>