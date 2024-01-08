// ventes / historique / commandes / page fournisseur / ajouter une adresse > bouton réinitialiser la page
function resetFilters() {
    var currentUrl = window.location.href;
    var baseUrl = currentUrl.split('?')[0];
    var idFournisseur = window.idFournisseur;

    if (currentUrl.includes('ventes')) {
        var newUrl = baseUrl + '?ventes';
    } else if (currentUrl.includes('historique')) {
        var newUrl = baseUrl + '?historique_commandes';
    } else if (currentUrl.includes('produits.php')) {
        var newUrl = baseUrl;
    } else if (currentUrl.includes('?messagerie')) {
        var newUrl = baseUrl + '?messagerie';
    } else if (currentUrl.includes('page_fournisseur.php')) {
        var newUrl = baseUrl + '?id=' + idFournisseur;     
    } else if (currentUrl.includes('profil')) {
            var newUrl = baseUrl + '?profil';     
    } else if (currentUrl.includes('commande.php')) {
            var newUrl = baseUrl ;    
    } else {
        var newUrl
    }
    console.log(newUrl);
    window.location.href = newUrl;
}


// commandes.php > bouton update état de commande 
document.addEventListener('DOMContentLoaded', function () {
    var buttons = document.querySelectorAll('.statut-btn');

    buttons.forEach(function (button) {
        button.addEventListener('click', function () {
            var commandeId = this.getAttribute('data-commande-id');
            var etatCommande = this.getAttribute('data-etat-commande');

            if (etatCommande === 'livrée' || etatCommande === 'validée' || etatCommande === 'refusée') {
                console.log('Le bouton est désactivé, action à faire côté client ');
                return;
            }

            fetch('include/update_statut.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'commandeId=' + encodeURIComponent(commandeId),
            })
            .then(function (response) {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.text();
            })
            .then(function (result) {
                etatCommande = result.trim(); 
                button.innerText = etatCommande;
                button.classList.remove('livree', 'validee', 'refusee');
                
                switch (etatCommande) {
                    case 'à valider':
                    case 'en préparation':
                    case "en cours d'envoi":
                    case 'en cours de livraison':
                    case 'livrée':
                        button.classList.add('livree');
                        break;
            
                    case 'validée':
                        button.classList.add('validee');
                        break;
            
                    case 'refusée':
                        button.classList.add('refusee');
                        break;
            
                    default:
                }
            
                button.disabled = true;
            
                location.reload();
            })
            .catch(function (error) {
                console.error('Il y a eu un problème avec votre opération de fetch :', error);
            });
        });
    });
});



// messagerie > Fonction pour faire défiler la boîte de messages vers le bas
function scrollToBottomOfMessages() {
    var messageContainer = document.querySelector('.message-container');
    messageContainer.scrollTop = messageContainer.scrollHeight;
}



// suivi_commande > voir les détails 
function toggleDetails(commandeId) {
    var detailsRow = document.getElementById('details-' + commandeId);

    if (detailsRow) {
        if (detailsRow.style.display === 'none' || detailsRow.style.display === '') {
            detailsRow.style.display = 'table-row';
        } else {
            detailsRow.style.display = 'none';
        }
    } else {
        console.error('Element not found: details-' + commandeId);
    }
}

//suivi_commande > bouton accepter / refuser la commmande
function updateStatut(commandeId, nouveauStatut) {
    
    fetch('include/accepter_commande.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ commandeId: commandeId, nouveauStatut: nouveauStatut }),
    })
    .then(data => {
        location.reload();
    })
    .catch(error => {
        console.error('Erreur lors de la mise à jour du statut :', error);
    });
}