<?php

try {
    $bdd = new PDO('mysql:host=2eurhost.com;dbname=eurh_groupe6;charset=utf8', 'groupe6', 'Cj1h8%m9');
}

catch (Exception $e)
{
    die('Erreur MySQL' . $e->getMessage());
}

session_start();
?>