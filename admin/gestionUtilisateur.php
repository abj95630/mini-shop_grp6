<?php 
require_once('../connexiondb.php');
require_once('../include/header.php');
require_once('../include/navBar.php');

$requeteUtilisateur = $bdd->query("SELECT id, nom, prenom, mail, date_creation
FROM Utilisateur");
echo '<table class="table table-bordered text-center mb-3"><tr>';
    for($i = 0; $i < $requeteUtilisateur->columnCount(); $i++){
        $colonne = $requeteUtilisateur->getColumnMeta($i);
        echo "<th>$colonne[name]</th>";
    }
    echo '<th>Supp</th>';
    echo '</tr>';
    while($utilisateur = $requeteUtilisateur->fetch(PDO::FETCH_ASSOC)){
        echo '<tr>';
        foreach($utilisateur as $key => $value){
            echo "<td>$value</td>";
        }
        echo "</td>";
    
        echo "<td><a href='?action=suppressionUtilisateur&id=$utilisateur[id]' class='text-danger'><i class='fas fa-trash-alt' onclick='return(confirm(\" Ëtes-vous certain de vouloir le supprimer? \"));'></i></a></td>";
        echo '</tr>';
    }
    echo '</table>';

if(isset($_GET['action']) && $_GET['action'] == 'suppressionUtilisateur')
{
    $deleteUtilisateur = $bdd->prepare("DELETE FROM Utilisateur WHERE id = :id");
    $deleteUtilisateur->bindValue(':id', $_GET['id'], PDO::PARAM_INT);
    $deleteUtilisateur->execute();
    header("Location: http://localhost/mini-shop_grp6/admin/gestionUtilisateur.php");
}

?>