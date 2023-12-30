<?php
include("connect.php");

// Check if the action is set
if (isset($_POST['action'])) {
    $action = $_POST['action'];

    // Check the specific action
    switch ($action) {
        case 'updateQuantite':
            updateQuantite();
            break;
        case 'retirerProduit':
            retirerProduit();
            break;
        default:
            // Handle unknown action
            echo "Unknown action";
            break;
    }
} else {
    // Handle action not set
    echo "Action not set";
}

// Function to update the quantity of the product
function updateQuantite() {
    global $con; // Make sure $con is accessible inside the function

    if (isset($_POST['id_produit']) && isset($_POST['nouvelleQuantite'])) {
        $id_produit = mysqli_real_escape_string($con, $_POST['id_produit']);
        $nouvelleQuantite = mysqli_real_escape_string($con, $_POST['nouvelleQuantite']);

        // Perform the update in the database without prepared statements
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

// Function to delete the product from the cart
function retirerProduit() {
    if (isset($_POST['id_produit'])) {
        $id_produit = $_POST['id_produit'];
        include("connect.php");

        $delete_query = "UPDATE produit
            SET statut_produit = 'supprimé'
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
?>