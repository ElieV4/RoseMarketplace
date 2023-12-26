function updateQuantite(id_produit, nouvelleQuantite) {
    // Appel AJAX pour mettre à jour la quantité du produit
    fetch("ajax_cart.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded",
        },
        body: "action=updateQuantite&id_produit=" + id_produit + "&nouvelleQuantite=" + nouvelleQuantite,
    })
    .then(response => {
        if (!response.ok) {
            throw new Error("Network response was not ok");
        }
        return response.text();
    })
    .then(response => {
        // Mettez à jour l'affichage ou effectuez d'autres actions nécessaires
        console.log(response);
    })
    .catch(error => {
        console.error("Erreur AJAX: ", error);
    });
}

function ajouterQuantite(id_produit) {
    // Appel AJAX pour ajouter la quantité du produit
    fetch("ajax_cart.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded",
        },
        body: "action=ajouterQuantite&id_produit=" + id_produit,
    })
    .then(response => {
        if (!response.ok) {
            throw new Error("Network response was not ok");
        }
        return response.text();
    })
    .then(response => {
        // Mettez à jour l'affichage ou effectuez d'autres actions nécessaires
        console.log(response);
    })
    .catch(error => {
        console.error("Erreur AJAX: ", error);
    });
}

function enleverQuantite(id_produit) {
    // Appel AJAX pour enlever la quantité du produit
    fetch("ajax_cart.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded",
        },
        body: "action=enleverQuantite&id_produit=" + id_produit,
    })
    .then(response => {
        if (!response.ok) {
            throw new Error("Network response was not ok");
        }
        return response.text();
    })
    .then(response => {
        // Mettez à jour l'affichage ou effectuez d'autres actions nécessaires
        console.log(response);
    })
    .catch(error => {
        console.error("Erreur AJAX: ", error);
    });
}

function supprimerProduit(id_produit) {
    // Appel AJAX pour supprimer le produit du panier
    fetch("ajax_cart.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded",
        },
        body: "action=supprimerProduit&id_produit=" + id_produit,
    })
    .then(response => {
        if (!response.ok) {
            throw new Error("Network response was not ok");
        }
        return response.text();
    })
    .then(response => {
        // Mettez à jour l'affichage ou effectuez d'autres actions nécessaires
        console.log(response);
    })
    .catch(error => {
        console.error("Erreur AJAX: ", error);
    });
}