function ajouterQuantite(id_produit) {
    // Appel AJAX pour ajouter la quantité du produit
    $.ajax({
        type: "POST",
        url: "../ajax_cart.php", // Créez un fichier séparé pour gérer les actions AJAX
        data: { action: "ajouterQuantite", id_produit: id_produit },
        success: function(response) {
            // Mettez à jour l'affichage ou effectuez d'autres actions nécessaires
            console.log(response);
        },
        error: function(error) {
            console.log("Erreur AJAX: " + error);
        }
    });
}

function enleverQuantite(id_produit) {
    // Appel AJAX pour enlever la quantité du produit
    $.ajax({
        type: "POST",
        url: "../ajax_cart.php", // Créez un fichier séparé pour gérer les actions AJAX
        data: { action: "enleverQuantite", id_produit: id_produit },
        success: function(response) {
            // Mettez à jour l'affichage ou effectuez d'autres actions nécessaires
            console.log(response);
        },
        error: function(error) {
            console.log("Erreur AJAX: " + error);
        }
    });
}

function supprimerProduit(id_produit) {
    // Appel AJAX pour supprimer le produit du panier
    $.ajax({
        type: "POST",
        url: "ajax_cart.php",
        data: { action: "supprimerProduit", id_produit: id_produit },
        success: function(response) {
            // Mettez à jour l'affichage ou effectuez d'autres actions nécessaires
            console.log(response);
        },
        error: function(error) {
            console.log("Erreur AJAX: " + error);
        }
    });
}