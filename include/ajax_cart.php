<?php
include("include/connect.php");

// Check if the action is set
if (isset($_POST['action'])) {
    $action = $_POST['action'];

    // Check the specific action
    switch ($action) {
        case 'updateQuantite':
            updateQuantite();
            break;
        case 'ajouterQuantite':
            ajouterQuantite();
            break;
        case 'enleverQuantite':
            enleverQuantite();
            break;
        case 'supprimerProduit':
            supprimerProduit();
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
    if (isset($_POST['id_produit']) && isset($_POST['nouvelleQuantite'])) {
        $id_produit = $_POST['id_produit'];
        $nouvelleQuantite = $_POST['nouvelleQuantite'];

        // Perform the update in the database
        // Add your database update logic here

        echo "Quantity updated successfully";
    } else {
        echo "Invalid parameters for updating quantity";
    }
}

// Function to add quantity to the product
function ajouterQuantite() {
    if (isset($_POST['id_produit'])) {
        $id_produit = $_POST['id_produit'];

        // Perform the update in the database
        // Add your database update logic here

        echo "Quantity added successfully";
    } else {
        echo "Invalid parameters for adding quantity";
    }
}

// Function to remove quantity from the product
function enleverQuantite() {
    if (isset($_POST['id_produit'])) {
        $id_produit = $_POST['id_produit'];

        // Perform the update in the database
        // Add your database update logic here

        echo "Quantity removed successfully";
    } else {
        echo "Invalid parameters for removing quantity";
    }
}

// Function to delete the product from the cart
function supprimerProduit() {
    if (isset($_POST['id_produit'])) {
        $id_produit = $_POST['id_produit'];

        // Perform the delete in the database
        // Add your database delete logic here

        echo "Product deleted successfully";
    } else {
        echo "Invalid parameters for deleting product";
    }
}
?>