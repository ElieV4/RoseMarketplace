<?php
session_start();

$response = ['success' => false, 'error' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Assurez-vous que l'utilisateur est connecté
    if (!isset($_SESSION['user_id'])) {
        $response['error'] = 'Non autorisé';
    } else {
        $con = mysqli_connect('localhost', 'root', '', 'rosemarketplace');
        if (!$con) {
            $response['error'] = 'Erreur de connexion à la base de données : ' . mysqli_connect_error();
        } else {
            $id_commande = mysqli_real_escape_string($con, $_POST['commandeId']);
            $id_fournisseur = mysqli_real_escape_string($con, $_SESSION['user_id']);

            $etatCommandeQuery = "SELECT etat_commande, idclient_commande, id_client, id_gestionnaire
                FROM commande co
                LEFT JOIN client cl ON co.idclient_commande = cl.id_client 
                WHERE id_commande = '$id_commande'";
            $etatCommandeResult = mysqli_query($con, $etatCommandeQuery);

            if ($etatCommandeResult && $etatCommandeRow = mysqli_fetch_assoc($etatCommandeResult)) {
                $etatActuel = $etatCommandeRow['etat_commande'];
                $id_client = $etatCommandeRow['id_client'];
                $id_gestionnaire = $etatCommandeRow['id_gestionnaire'];

                // Mettez à jour l'état de la commande en fonction de l'état actuel
                switch ($etatActuel) {
                    case 'à valider':
                        // Mettez à jour l'état à 'en préparation'
                        $message = "Votre commande est en cours de préparation.";
                        $updateQuery = "UPDATE commande SET etat_commande = 'en préparation' WHERE id_commande = '$id_commande'";
                        break;

                    case 'en préparation':
                        // Mettez à jour l'état à 'en cours d'envoi' et ajoutez un message
                        $message = "Votre commande est en cours d'envoi.";
                        $updateQuery = "UPDATE commande SET etat_commande = 'en cours d\'envoi' WHERE id_commande = '$id_commande'";
                        break;

                    case 'en cours d\'envoi':
                        // Mettez à jour l'état à 'en cours de livraison' et ajoutez un message
                        $message = "Votre commande est en cours de livraison.";
                        $updateQuery = "UPDATE commande SET etat_commande = 'en cours de livraison' WHERE id_commande = '$id_commande'";
                        break;

                    case 'en cours de livraison':
                        // Mettez à jour l'état à 'livrée' et ajoutez un message
                        $message = "Votre commande a été livrée.";
                        $updateQuery = "UPDATE commande SET etat_commande = 'livrée' WHERE id_commande = '$id_commande'";
                        break;

                    case 'livrée':
                        // Ne rien faire, car la commande est déjà livrée
                        break;

                    default:
                        // Si l'état n'est pas géré, renvoyez une erreur
                        $response['error'] = 'Erreur : État non géré';
                        break;
                }

                // Exécutez la requête de mise à jour
                $result = mysqli_query($con, $updateQuery);

                if ($result) {
                    $response['success'] = true;
                    $response['message'] = 'Statut mis à jour avec succès';
                } else {
                    $response['error'] = 'Erreur lors de la mise à jour du statut : ' . mysqli_error($con);
                }
            } else {
                $response['error'] = 'Erreur lors de la récupération de l\'état actuel de la commande : ' . mysqli_error($con);
            }
        }

        // Fermez la connexion à la base de données
        mysqli_close($con);
    }
} else {
    $response['error'] = 'Méthode non autorisée';
}

// Retournez la réponse en format JSON
echo json_encode($response);
?>
