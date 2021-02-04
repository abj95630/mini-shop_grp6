<?php
session_start();
?>

<!DOCTYPE html">
<html lang="fr">
  <head>
    <meta charset="utf-8" />
    <link href="design.css" rel="stylesheet">
    <title>Commande validée!</title>
  </head>
  
  <body>
  <div id="conteneur">    
    <h1 id="header">Commande validée!</h1>

    <nav>
      <ul id="menu">
      <p>Merci beaucoup!</p>
      </ul>
    </nav>
    
    <div id="contenu">
      <h2>Récapitulatif :</h2>
      <form action="" method="get">

<?php if(isset($_SESSION['articles'])) { ?>

    <ul>
        <?php foreach ($_SESSION['articles'] as $name=>$quantite) { ?>
            <li>
                <?php echo $name;  ?> - x<?php echo $quantite; ?>
            </li>
        <?php } ?>
    </ul>

<?php } else { ?>

    <h3>Aucun article</h3>

<?php } ?>

</form>
    </div>
    
  </div>
  </body>
</html>