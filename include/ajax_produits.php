<?php
include("connect.php");

// Check if the action is set
if (isset($_POST['action'])) {
    $action = $_POST['action'];
    // Check the specific action
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
            // Handle unknown action
            echo "Unknown action";
            break;
    }
} else {
    // Handle action not set
    echo "Action not set";
}

// Function to update the quantity of the product
function updateStock() {
    global $con;
    if (isset($_POST['id_produit']) && isset($_POST['nouveauStock'])) {
        $id_produit = mysqli_real_escape_string($con, $_POST['id_produit']);
        $nouveauStock = mysqli_real_escape_string($con, $_POST['nouveauStock']);

        // Perform the update in the database without prepared statements
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

// Function to delete the product
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

// Function to add the product
function ajouterProduit() {
    global $con; // Make sure $con is accessible inside the function
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

