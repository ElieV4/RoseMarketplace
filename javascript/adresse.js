// Ajouter une adresse
function ajouterAdresse(idClient, callback) {
    var nouvelleAdresse = {
        type: 'livraison', // ou 'facturation' selon le bouton
        // autres données de l'adresse...
    };

    var data = {
        action: 'ajouterAdresse',
        idClient: idClient,
        nouvelleAdresse: nouvelleAdresse
    };

    envoyerRequeteAjax(data, callback);
}

// Copier l'adresse de facturation comme adresse de livraison
function copierLivraison(idClient, callback) {
    var data = {
        action: 'copierLivraison',
        idClient: idClient
    };

    envoyerRequeteAjax(data, callback);
}

// Fonction générique pour envoyer une requête Ajax
function envoyerRequeteAjax(data, callback) {
    var xhr = new XMLHttpRequest();
    xhr.open('POST', './include/ajax_commande.php', true);
    xhr.setRequestHeader('Content-Type', 'application/json');

    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            // La requête s'est bien déroulée
            console.log(xhr.responseText);

            if (callback) {
                callback();
            }
        }
    };

    xhr.send(JSON.stringify(data));
}
