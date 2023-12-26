<?php
include("include/connect.php");

//fonction bouton ajouter quantité
function ajouterquantite($con, $id_produit){
    $update_query = "UPDATE panier SET quantité_produit = quantité_produit + 1 
        WHERE id_produit = $id_produit";

    // Exécuter la requête
    $result = mysqli_query($con, $update_query);

    // Vérifier si la requête s'est exécutée avec succès
    if ($result) {
        echo "+";
    } else {
        // En cas d'erreur lors de l'exécution de la requête
        echo "Err" . mysqli_error($con);
    }
}

//fonction bouton enlever quantité
function enleverquantite($con, $id_produit){
    $update_query = "UPDATE panier SET quantité_produit = quantité_produit - 1 
        WHERE id_produit = $id_produit";

    // Exécuter la requête
    $result = mysqli_query($con, $update_query);

    // Vérifier si la requête s'est exécutée avec succès
    if ($result) {
        echo "-";
    } else {
        // En cas d'erreur lors de l'exécution de la requête
        echo "Err" . mysqli_error($con);
    }
}

// Vérifier si l'action est définie
if (isset($_POST['action'])) {
    $id_produit = $_POST['id_produit'];

    switch ($_POST['action']) {
        case 'ajouterQuantite':
            // Logique pour ajouter la quantité du produit dans la base de données
            ajouterquantite($con, $id_produit);
            break;

        case 'enleverQuantite':
            // Logique pour enlever la quantité du produit dans la base de données
            enleverquantite($con, $id_produit);
            break;

        default:
            echo "Action non reconnue";
            break;
    }
} else {
    echo "Action non définie";
}


if (isset($_POST['action'])) {
    $action = $_POST['action'];

    switch ($action) {
        case 'updateQuantite':
            updateQuantite();
            break;
        // Ajoutez d'autres cas pour d'autres actions si nécessaire
        default:
            echo "Action non reconnue";
            break;
    }
}

function updateQuantite() {
    global $con;

    if (isset($_POST['id_produit']) && isset($_POST['nouvelleQuantite'])) {
        $id_produit = $_POST['id_produit'];
        $nouvelleQuantite = $_POST['nouvelleQuantite'];

        // Assurez-vous que la nouvelle quantité est un nombre entier positif
        $nouvelleQuantite = intval($nouvelleQuantite);

        if ($nouvelleQuantite >= 0) {
            // Mettez à jour la quantité dans la table panier
            $update_query = "UPDATE panier SET quantité_produit = $nouvelleQuantite WHERE id_produit = $id_produit";
            $result = mysqli_query($con, $update_query);

            if ($result) {
                echo "Quantité mise à jour avec succès";
            } else {
                echo "Erreur lors de la mise à jour de la quantité : " . mysqli_error($con);
            }
        } else {
            echo "La nouvelle quantité doit être un nombre entier positif";
        }
    } else {
        echo "Paramètres manquants pour mettre à jour la quantité";
    }
}

if (isset($_POST['action'])) {
    $action = $_POST['action'];

    switch ($action) {
        // ... d'autres cas existants ...

        case 'supprimerProduit':
            supprimerProduit();
            break;

        default:
            echo "Action non reconnue";
            break;
    }
}

function supprimerProduit() {
    global $con;

    if (isset($_POST['id_produit'])) {
        $id_produit = $_POST['id_produit'];

        // Supprimer le produit du panier
        $delete_query = "DELETE FROM panier WHERE id_produit = $id_produit";
        $result = mysqli_query($con, $delete_query);

        if ($result) {
            echo "Produit supprimé avec succès";
        } else {
            echo "Erreur lors de la suppression du produit : " . mysqli_error($con);
        }
    } else {
        echo "ID du produit manquant pour la suppression";
    }
}
?>