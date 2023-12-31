function toggleTable() {
    var produitssupprimés = document.getElementById("produitssupprimés");
    var toggleTableButton = document.getElementById("toggleTableButton");

    if (produitssupprimés.style.display === "none") {
        produitssupprimés.style.display = "block";
        toggleTableButton.innerHTML = "Réduire";
    } else {
        produitssupprimés.style.display = "none";
        toggleTableButton.innerHTML = "Afficher vos anciens produits";
    }
}

function updateStock(id_produit) {

    // Get the new quantity from the input field
    var nouveauStock = document.getElementById('quantiteInput_' + id_produit).value;

    // Log the data to be sent in the console for debugging
    console.log("Updating quantity for product ID: " + id_produit);
    console.log("New quantity: " + nouveauStock);

    // Perform AJAX request to update quantity
    fetch("./include/ajax_produits.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded",
        },
        body: "action=updateStock&id_produit=" + id_produit + "&nouveauStock=" + nouveauStock,
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

function retirerProduit(id_produit) {

    // Appel AJAX pour supprimer le produit du panier
    fetch("./include/ajax_produits.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded",
        },
        body: "action=retirerProduit&id_produit=" + id_produit,
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

        // Reload the page after successful update
        location.reload();
    })
    .catch(error => {
        console.error("Erreur AJAX: ", error);
    });
}

function ajouterProduit(id_produit) {

    // Appel AJAX pour supprimer le produit du panier
    fetch("./include/ajax_produits.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded",
        },
        body: "action=ajouterProduit&id_produit=" + id_produit,
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

        // Reload the page after successful update
        location.reload();
    })
    .catch(error => {
        console.error("Erreur AJAX: ", error);
    });
}