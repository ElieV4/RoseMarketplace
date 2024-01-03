<?php 

 echo "'à valider', 'en préparation', 'en cours d'envoi', 'en cours de livraion', 'livrée', 'refusée','validée')";
    if (isset($_SESSION['user_id'])) {
        //echo $_SESSION['user_id']." est connecté";
    } else {
        //echo "déconnecté";
    }
    $user = $_SESSION['user_id_id'];

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rose. | Commandes</title>
    <link rel="stylesheet" type="text/css" href="./css/main_style.css">
    <style>
        .outer-container{
            margin-top:50px;
            margin-left:10%;
            margin-right:10%;
            background-color: white;
            align-items: center;
            text-align: center;
        }
        .imgcontainer sideimg {
                display:flex;
                align-items: center;
                margin:5%;
                padding:10px;
                width:20%;
                height:auto;
        }
        .main {
            background-color: white;
        }
    </style>
</head>
<body>  
    <div class="outer-container">
        <div class="content">
            <?php
                $select_query = "SELECT *
                                FROM commande c
                                LEFT JOIN produit p ON c.id_produit = p.id_produit
                                LEFT JOIN client cl ON c.idclient_commande = cl.id_client
                                LEFT JOIN adresse a ON c.id_adresse = a.id_adresse
                                WHERE c.id_fournisseur = '$user'
                                ORDER BY date_commande DESC";
                $result = mysqli_query($con, $select_query);
                
                if ($result) {
                    // Afficher le tableau HTML
                    echo "<table border='1'>
                        <tr>
                            <th>ID Commande</th>
                            <th>Informations client</th>
                            <th>Nom du produit</th>
                            <th>Quantité commandée</th>
                            <th>Date commande</th>
                            <th>Statut commande</th>
                        </tr>";
                    $montant_commande = 0;
                    // Parcourir les résultats et afficher chaque ligne dans le tableau
                    while ($rowdata = mysqli_fetch_assoc($result)) {
                        $id_commande = $rowdata['id_commande'];
                        $nom_produit = $rowdata['nom_produit'];
                        $marque_produit = $rowdata['marque_produit'];
                        $quantité_produit = $rowdata['quantité_produit'];
                        $date_commande = $rowdata['date_commande'];
                        $type_client = $rowdata['type_client'];
                        if($type_client==0){
                            $client = $rowdata['prenom_client']. ' ' . $rowdata['nom_client'];
                        } else {
                            $client = $rowdata['raisonsociale_client'];
                        }
                        $adresse = $rowdata['numetrue_adresse'];
                        $codepostal = $rowdata['codepostal_adresse'];
                        $ville = $rowdata['villeadresse_adresse'];
                        $statut = $rowdata['etat_commande'];

                        echo '<tr>
                                <td>'.$id_commande.'</td>
                                <td>'.$client.'<br>'.$adresse.'<br>'.$codepostal.' '.$ville.'</td>
                                <td>'.$nom_produit.'<br>'.$marque_produit.'</td>
                                <td>'.$quantité_produit.'</td>
                                <td>'.$date_commande.'</td>
                                <td><button class="statut-btn" data-etat-commande="'.$statut.'" data-commande-id="'.$id_commande.'">'.$statut.'</button></td>
                            </tr>';
                    }
                    echo "</table>";
                } else {
                    // En cas d'erreur lors de l'exécution de la requête
                    echo "Erreur dans la requête : " . mysqli_error($con);
                }
            ?>
        </div>
    </div>
    <script>
document.addEventListener('DOMContentLoaded', function () {
    var buttons = document.querySelectorAll('.statut-btn');

    buttons.forEach(function (button) {
        button.addEventListener('click', function () {
            var commandeId = this.getAttribute('data-commande-id');
            var etatCommande = this.getAttribute('data-etat-commande');

            // Ajoutez une vérification pour désactiver le bouton si l'état est 'livrée', 'validée' ou 'refusée'
            if (etatCommande === 'livrée' || etatCommande === 'validée' || etatCommande === 'refusée') {
                console.log('Le bouton est désactivé car l\'état est ' + etatCommande);
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
                button.classList.remove('en-cours', 'livree', 'validee', 'refusee');
                
                // Mettez à jour la classe du bouton en fonction de l'état
                switch (etatCommande) {
                    case 'à valider':
                    case 'en préparation':
                    case "en cours d'envoi":
                    case 'en cours de livraison':
                        button.classList.add('en-cours');
                        break;

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

                // Rechargez la page après la mise à jour réussie
                location.reload();
            })
            .catch(function (error) {
                console.error('Il y a eu un problème avec votre opération de fetch :', error);
            });
        });
    });
});
</script>

</body>
</html>