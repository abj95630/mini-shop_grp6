<?php
require_once('connexiondb.php');

/*if(isset($_SESSION['mail'])) {
    header('Location:index.php');
    exit;
}*/
print_r($_POST);
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

        $query = $bdd->prepare("SELECT * FROM Utilisateur WHERE :mail AND :mdp");
        $query->bindValue(':mail', $mail);
        $query->bindValue(':mdp', hash("md5", $mdp));
        $query->execute();

        if($mail == NULL || $mdp == NULL) {
            $valid = false;
            $er_mail = "Le mail ou le mot de passe est incorrecte";
        }

    } 
    if($valid) {
       // $_SESSION['mail'] = $mail;
        header('Location: index.php');
        exit;
    }

}
require_once('include/header.php');
require_once('include/navBar.php');
?>

<body>
    <div>Se connecter</div>
    <form method="post">
        <?php
            if(isset($er_mail)) {
        ?>  
                <div><?= $er_mdp ?></div>    
        <?php    
            }
        ?>

        <input type="email" placeholder="Addresse mail" name="mail" value="<?php if(isset($mail)){echo $mail; }?>" required>
    
        <?php 
            if(isset($er_mdp)) {
        ?>
                <div><?=$er_mdp?></div>
        <?php
            }
        ?>

        <input type="password" placeholder="Mot de passe" name="mdp" value="<?php if(isset($mdp)){echo $mdp;}?>" required>
         <button type="submit" name="connexion">Se connecter</button>
    </form>
<?php 
require_once('include/footer.php');
?>