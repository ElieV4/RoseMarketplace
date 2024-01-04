// ventes / historique commandes > bouton réinitialiser 
function resetFilters() {
	// Récupérer l'URL actuelle
	var currentUrl = window.location.href;

	// Vérifier s'il y a des paramètres GET dans l'URL
	if (currentUrl.indexOf('?') !== -1) {
		// Extraire la partie de l'URL avant le premier paramètre GET
		var baseUrl = currentUrl.split('?')[0];

		// Construire la nouvelle URL avec uniquement le paramètre 'ventes'
		if(baseUrl.includes('ventes')){
			var newUrl = baseUrl + '?ventes';
		} else if (baseUrl.includes('historique')) {
			var newUrl = baseUrl + '?historique_commandes';
		} else {
			var newUrl = baseUrl + '?commandes';
		}
		// Rediriger vers la nouvelle URL
		window.location.href = newUrl;
	}
}


// commandes > bouton update état de commande 
document.addEventListener('DOMContentLoaded', function () {
    var buttons = document.querySelectorAll('.statut-btn');

    buttons.forEach(function (button) {
        button.addEventListener('click', function () {
            var commandeId = this.getAttribute('data-commande-id');
            var etatCommande = this.getAttribute('data-etat-commande');

            // Ajoutez une vérification pour désactiver le bouton si l'état est 'livrée', 'validée' ou 'refusée'
            if (etatCommande === 'livrée' || etatCommande === 'validée' || etatCommande === 'refusée') {
                console.log('Le bouton est désactivé, action à faire côté client ');
                return;
            }

            // Mettez à jour le statut en appelant le script PHP
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
                // Mettez à jour le texte du bouton et désactivez-le
                etatCommande = result.trim(); // Mettez à jour l'état du bouton avec le nouvel état
                button.innerText = etatCommande;
                button.classList.remove('livree', 'validee', 'refusee');
                
                // Mettez à jour la classe du bouton en fonction de l'état
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
                        // Ne rien faire si l'état n'est pas géré
                }
            
                button.disabled = true;
            
                // Maintenant, après la mise à jour réussie, rechargez la page
                location.reload();
            })
            .catch(function (error) {
                console.error('Il y a eu un problème avec votre opération de fetch :', error);
            });
        });
    });
});
