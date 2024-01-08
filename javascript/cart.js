function updateQuantite(id_produit) {
    var nouvelleQuantite = document.getElementById('quantiteInput_' + id_produit).value;

    console.log("Updating quantity for product ID: " + id_produit);
    console.log("New quantity: " + nouvelleQuantite);

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
        console.log("Server Response: " + responseText);

        location.reload();
    })
    .catch(error => {
        console.error("Erreur AJAX: ", error);
    });
}

function supprimerProduit(id_produit, callback) {
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
        console.log(response);

        if (typeof callback === 'function') {
            callback();
        }
    })
    .catch(error => {
        console.error("Erreur AJAX: ", error);
    });
}