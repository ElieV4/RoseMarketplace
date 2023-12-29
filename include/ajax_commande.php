<?php
include("connect.php");

// Check if the action is set
if (isset($_POST['action'])) {
    $action = $_POST['action'];

    // Check the specific action
    switch ($action) {
        case 'updateAdresse':
            updateQuantite();
            break;
        case 'supprimerAdresse':
            supprimerAdresse();
            break;
        case 'supprimerPaiement':
            supprimerPaiement();
            break;
        case 'executerCommande':
            executerCommande();
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
function supprimerAdresse() {
    if (isset($_POST['id_adresse'])) {
        $id_adresse = $_POST['id_adresse'];
        include("connect.php");

        $delete_query = "DELETE FROM adresse WHERE id_adresse = $id_adresse";
        $result = mysqli_query($con, $delete_query);
        if ($result) {
            echo "Adresse deleted successfully";
        } else {
            echo "Error deleting product: " . mysqli_error($con);
        }
    } else {
        echo "Invalid parameters for deleting product";
    }
}

// Function to delete the product from the cart
function supprimerPaiement() {
    if (isset($_POST['id_paiement'])) {
        $id_paiement = $_POST['id_paiement'];
        include("connect.php");

        $delete_query = "DELETE FROM paiement WHERE id_paiement = $id_paiement";
        $result = mysqli_query($con, $delete_query);
        if ($result) {
            echo "Paiement deleted successfully";
        } else {
            echo "Error deleting product: " . mysqli_error($con);
        }
    } else {
        echo "Invalid parameters for deleting product";
    }
}

// executer la commande 
function executerCommande() {

    include("connect.php");

// Vérifie si l'utilisateur est déjà connecté
session_start();
if (isset($_SESSION['user_id'])) {
    //echo $_SESSION['user_id']." est connecté";
} else {
    echo "déconnecté";
}
$user = $_SESSION['user_id_id'];

// Récupérer les valeurs des radios sélectionnés pour livraison et paiement
$idLivraison = mysqli_real_escape_string($con, $_POST['idLivraison']);
$idPaiement = mysqli_real_escape_string($con, $_POST['idPaiement']);
$id_commande = rand();
// Insérer une ligne dans la table commande pour chaque produit du panier
$insert_commande_query = "INSERT INTO commande (id_commande, id_commande_produit, date_commande, idclient_commande, etat_commande, id_produit, quantité_produit, montant_total, id_fournisseur, id_adresse, id_paiement)
    SELECT '$id_commande', CONCAT('$id_commande', '-',p.id_produit), NOW(), '$user', 'à valider', p.id_produit, p.quantité_produit, p.quantité_produit * prixht_produit * 1.2, pr.id_fournisseur, '$idLivraison', '$idPaiement'
    FROM panier p
    INNER JOIN produit pr ON p.id_produit = pr.id_produit";

$result = $con->query($insert_commande_query);

if ($result) {
    $id_commande = $con->insert_id;

    // Mettre à jour le stock dans la table produit
    $update_produit_query = "UPDATE produit pr
        INNER JOIN panier p ON pr.id_produit = p.id_produit
        SET pr.quantitestock_produit = pr.quantitestock_produit - p.quantité_produit";

    if ($con->query($update_produit_query) !== TRUE) {
        echo "Erreur lors de la mise à jour du stock dans la table produit : " . $con->error;
        return;
    } else {
        // Supprimer le contenu du panier
        $delete_panier_query = "DELETE FROM panier";
        if ($con->query($delete_panier_query) !== TRUE) {
            echo "Erreur lors de la suppression du panier : " . $con->error;
            return;
        } else {
            echo '<script>alert("Commande prise en compte, merci pour votre achat !");</script>';
        }
    }
} else {
    echo "Erreur lors de l'insertion dans la table commande : " . $con->error;
    return;
}
}

?>