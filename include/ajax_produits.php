<?php
include("connect.php");

if (isset($_POST['action'])) {
    $action = $_POST['action'];
  
    switch ($action) {
        case 'updateStock':
            updateStock();
            break;
        case 'retirerProduit':
            retirerProduit();
            break;
        case 'ajouterProduit':
            ajouterProduit();
            break;
        default:
            echo "Unknown action";
            break;
    }
} else {
    echo "Action not set";
}

function updateStock() {
    global $con;
    if (isset($_POST['id_produit']) && isset($_POST['nouveauStock'])) {
        $id_produit = mysqli_real_escape_string($con, $_POST['id_produit']);
        $nouveauStock = mysqli_real_escape_string($con, $_POST['nouveauStock']);

        $update_query = "UPDATE produit SET quantitestock_produit = $nouveauStock WHERE id_produit = $id_produit";
        $result = mysqli_query($con, $update_query);

        if ($result) {
            echo "Quantity updated successfully";
        } else {
            echo "Error updating quantity: " . mysqli_error($con);
        }
    } else {
        echo "Invalid parameters for updating quantity";
    }
}

function retirerProduit() {
    global $con;
    if (isset($_POST['id_produit'])) {
        $id_produit = $_POST['id_produit'];

        $delete_query = "UPDATE produit
            SET statut_produit = 'supprimé', quantitestock_produit = 0
            WHERE id_produit = $id_produit;";
        $result = mysqli_query($con, $delete_query);
        if ($result) {
            echo "Product deleted successfully";
        } else {
            echo "Error deleting product: " . mysqli_error($con);
        }
    } else {
        echo "Invalid parameters for deleting product";
    }
}

function ajouterProduit() {
    global $con; 
    if (isset($_POST['id_produit'])) {
        $id_produit = $_POST['id_produit'];

        $add_query = "UPDATE produit
            SET statut_produit = 'disponible', quantitestock_produit = 1, date_ajout_produit = NOW()
            WHERE id_produit = $id_produit;";
        $result = mysqli_query($con, $add_query);
        if ($result) {
            echo "Product added successfully";
        } else {
            echo "Error adding product: " . mysqli_error($con);
        }
    } else {
        echo "Invalid parameters for adding product";
    }
}
?>

