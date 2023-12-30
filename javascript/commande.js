// Fonction pour exécuter la commande avec les éléments sélectionnés
function executerCommande() {
    // Récupérer les radios sélectionnés pour la livraison et le paiement
    var livraisonRadio = document.querySelector('input[name="livraison_radio"]:checked');
    var paiementRadio = document.querySelector('input[name="paiement_radio"]:checked');

    // Vérifier si une ligne est sélectionnée dans chaque tableau
    if (livraisonRadio && paiementRadio) {
        // Récupérer les valeurs des radios sélectionnés
        var idLivraison = livraisonRadio.value;
        var idPaiement = paiementRadio.value;

        // Effectuer une requête AJAX pour exécuter la commande côté serveur
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
            // Log the response from the server for debugging
            console.log("Server Response: " + responseText);

            // Construire l'URL de redirection en utilisant le chemin de la page actuelle
            var currentPath = window.location.pathname;
            var profilPageURL = currentPath + "?profil";

            window.location.href = profilPageURL; // Rediriger vers la page actuelle avec le paramètre ?profil
        })
        .catch(error => {
            // Log any errors that occurred during the AJAX request
            console.error("Erreur AJAX: ", error);
            alert("Erreur lors de l'exécution de la commande.");
        });
    }
}

// Fonction pour supprimer l'adresse
function supprimerAdresse(id_adresse, callback) {
    // Appel AJAX pour supprimer l'adresse du compte
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
        // Mettre à jour l'affichage ou effectuer d'autres actions nécessaires
        console.log(response);

        // Appel de la fonction de rappel pour recharger la page
        if (typeof callback === 'function') {
            callback();
        }

        // Construire l'URL de redirection en utilisant le chemin de la page actuelle
        var currentPath = window.location.pathname;
        var profilPageURL = currentPath + "?profil";

        window.location.href = profilPageURL; // Rediriger vers la page actuelle avec le paramètre ?profil
    })
    .catch(error => {
        console.error("Erreur AJAX : ", error);
    });
}

// Fonction pour supprimer le paiement
function supprimerPaiement(id_paiement, callback) {
    // Appel AJAX pour supprimer le moyen de paiement du compte
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
        // Mettre à jour l'affichage ou effectuer d'autres actions nécessaires
        console.log(response);

        // Appel de la fonction de rappel pour recharger la page
        if (typeof callback === 'function') {
            callback();
        }

        // Construire l'URL de redirection en utilisant le chemin de la page actuelle
        var currentPath = window.location.pathname;
        var profilPageURL = currentPath + "?profil";

        window.location.href = profilPageURL; // Rediriger vers la page actuelle avec le paramètre ?profil
    })
    .catch(error => {
        console.error("Erreur AJAX : ", error);
    });
}

// Valider la commande avec les éléments sélectionnés
function checkSelection() {
    var livraisonRadio = document.querySelector('input[name="livraison_radio"]:checked');
    var paiementRadio = document.querySelector('input[name="paiement_radio"]:checked');

    // Si une ligne est sélectionnée dans chaque tableau, activer le bouton "Exécuter commande"
    if (livraisonRadio && paiementRadio) {
        document.getElementById('executer_commande').disabled = false;
    } else {
        document.getElementById('executer_commande').disabled = true;
    }
}

// Ajouter des écouteurs d'événements pour les boutons radio
var livraisonRadios = document.querySelectorAll('input[name="livraison_radio"]');
var paiementRadios = document.querySelectorAll('input[name="paiement_radio"]');

livraisonRadios.forEach(function (radio) {
    radio.addEventListener('change', checkSelection);
});

paiementRadios.forEach(function (radio) {
    radio.addEventListener('change', checkSelection);
});
