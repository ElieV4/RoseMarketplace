//page_fournisseur > désactiver / réactiver les annonces
// s'execute quand le gestionnaire bloque un contrat pro
function desactiverAnnonce(idProduit) {
    fetch('include/desactiver_annonce.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ idProduit: idProduit }),
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            location.reload();
        } else {
            alert('Erreur lors de la désactivation de l\'annonce : ' + data.message);
        }
    })
    .catch(error => {
        console.error('Erreur lors de la désactivation de l\'annonce :', error);
    });
}

//FONCTION ADMIN > page contrats.php
function validerCompte(idClient, nouveauStatut) {
    fetch('include/valider_contrat.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ idClient: idClient, nouveauStatut: nouveauStatut }),
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        console.log('voila:', data);
        location.reload();
    })
    .catch(error => {
        console.error('Erreur lors de la mise à jour du statut :', error);
    });
}