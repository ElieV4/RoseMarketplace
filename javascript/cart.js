function updateQuantite(id_produit) {
    // Get the new quantity from the input field
    var nouvelleQuantite = document.getElementById('quantiteInput_' + id_produit).value;

    // Log the data to be sent in the console for debugging
    console.log("Updating quantity for product ID: " + id_produit);
    console.log("New quantity: " + nouvelleQuantite);

    // Perform AJAX request to update quantity
    fetch("./include/ajax_cart.php", {
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
    .then(responseText => {
        // Log the response from the server for debugging
        console.log("Server Response: " + responseText);

        // Reload the page after successful update
        location.reload();
    })
    .catch(error => {
        // Log any errors that occurred during the AJAX request
        console.error("Erreur AJAX: ", error);
    });
}

function supprimerProduit(id_produit, callback) {
    // Appel AJAX pour supprimer le produit du panier
    fetch("./include/ajax_cart.php", {
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

        // Appel de la fonction de rappel pour recharger la page
        if (typeof callback === 'function') {
            callback();
        }
    })
    .catch(error => {
        console.error("Erreur AJAX: ", error);
    });
}