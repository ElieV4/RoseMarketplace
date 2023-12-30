<?php
    @session_start();
    session_unset();
    session_destroy();
    //vider le panier
    include("./connect.php");
    $delete_query = "DELETE FROM panier";
    $result = mysqli_query($con, $delete_query);
    if ($result) {
        echo "Toutes les lignes de la table panier ont été supprimées avec succès.";
    } else {
        echo "Erreur dans la requête : " . mysqli_error($con);
    }

    echo "<script>alert('Vous avez été déconnecté.e')</script>";
    echo "<script>window.open('../index.php','_self')</script>"; 
?>