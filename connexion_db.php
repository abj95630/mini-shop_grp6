<?php

try {
      $bdd = new PDO('mysql:host=localhost;dbname=db_gaming', 'root', 'root');
      $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $bdd->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (Exception $e) {
      die('Erreur'.$e->getMessage());
}

?>