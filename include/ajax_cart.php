<?php
include("connect.php");

if (isset($_POST['action'])) {
    $action = $_POST['action'];

    switch ($action) {
        case 'updateQuantite':
            updateQuantite();
            break;
        case 'supprimerProduit':
            supprimerProduit();
            break;
        default:
            echo "Unknown action";
            break;
    }
} else {
    echo "Action not set";
}

function updateQuantite() {
    global $con; 

    if (isset($_POST['id_produit']) && isset($_POST['nouvelleQuantite'])) {
        $id_produit = mysqli_real_escape_string($con, $_POST['id_produit']);
        $nouvelleQuantite = mysqli_real_escape_string($con, $_POST['nouvelleQuantite']);

        $update_query = "UPDATE panier SET quantité_produit = $nouvelleQuantite WHERE id_produit = $id_produit";
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

function supprimerProduit() {
    if (isset($_POST['id_produit'])) {
        $id_produit = $_POST['id_produit'];
        include("connect.php");

        $delete_query = "DELETE FROM panier WHERE id_produit = $id_produit";
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
?>