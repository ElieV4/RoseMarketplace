document.getElementById('executer_commande').addEventListener('click', function() {
    executerCommande();
});

function executerCommande() {
    var messageErreurDiv = document.getElementById('message_erreur');
    var livraisonRadio = document.querySelector('input[name="livraison_radio"]:checked');
    var paiementRadio = document.querySelector('input[name="paiement_radio"]:checked');
    if (livraisonRadio && paiementRadio) {

        var idLivraison = livraisonRadio.value;
        var idPaiement = paiementRadio.value;

        fetch("./include/ajax_commande.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
            },
            body: "action=executerCommande&idLivraison=" + idLivraison + "&idPaiement=" + idPaiement,
        })
        .then(response => {
            if (!response.ok) {
                throw new Error("Network response was not ok");
            }
            return response.text();
        })
        .then(responseText => {
            console.log("Server Response: " + responseText);

            var currentPath = window.location.pathname;
            var profilPageURL = currentPath + "?profil";

            window.location.href = profilPageURL; // Rediriger vers la page actuelle avec le paramètre ?profil
        })
        .catch(error => {
            console.error("Erreur AJAX: ", error);
            alert("Erreur lors de l'exécution de la commande.");
        });
    } else {
        messageErreurDiv.textContent = "Veuillez sélectionner une adresse de livraison et un moyen de paiement avant de passer commande.";
    }
}

function supprimerAdresse(id_adresse, callback) {
    fetch("./include/ajax_commande.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded",
        },
        body: "action=supprimerAdresse&id_adresse=" + id_adresse,
    })
    .then(response => {
        if (!response.ok) {
            throw new Error("La réponse du réseau n'était pas correcte");
        }
        return response.text();
    })
    .then(response => {
        console.log(response);

        if (typeof callback === 'function') {
            callback();
        }

        var currentPath = window.location.pathname;
        var profilPageURL = currentPath + "?profil";

        window.location.href = profilPageURL; 
    })
    .catch(error => {
        console.error("Erreur AJAX : ", error);
    });
}

function supprimerPaiement(id_paiement, callback) {
    fetch("./include/ajax_commande.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded",
        },
        body: "action=supprimerPaiement&id_paiement=" + id_paiement,
    })
    .then(response => {
        if (!response.ok) {
            throw new Error("La réponse du réseau n'était pas correcte");
        }
        return response.text();
    })
    .then(response => {
        console.log(response);

        if (typeof callback === 'function') {
            callback();
        }

        var currentPath = window.location.pathname;
        var profilPageURL = currentPath + "?profil";

        window.location.href = profilPageURL; 
    })
    .catch(error => {
        console.error("Erreur AJAX : ", error);
    });
}
