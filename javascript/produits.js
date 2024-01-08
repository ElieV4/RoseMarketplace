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

    var nouveauStock = document.getElementById('quantiteInput_' + id_produit).value;

    console.log("Updating quantity for product ID: " + id_produit);
    console.log("New quantity: " + nouveauStock);

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
        console.log("Server Response: " + responseText);

        location.reload();
    })
    .catch(error => {
        console.error("Erreur AJAX: ", error);
    });
}

function retirerProduit(id_produit) {

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
        console.log(response);

        location.reload();
    })
    .catch(error => {
        console.error("Erreur AJAX: ", error);
    });
}

function ajouterProduit(id_produit) {
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
        console.log(response);
        location.reload();
    })
    .catch(error => {
        console.error("Erreur AJAX: ", error);
    });
}