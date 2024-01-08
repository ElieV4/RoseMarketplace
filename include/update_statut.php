<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['user_id'])) {
        $response = 'Non autorisé';
    } else {
        $con = mysqli_connect('localhost', 'root', '', 'rosemarketplace');
        if (!$con) {
            $response = 'Erreur de connexion à la base de données : ' . mysqli_connect_error();
        } else {
            $id_commande = mysqli_real_escape_string($con, $_POST['commandeId']);
            $id_fournisseur = mysqli_real_escape_string($con, $_SESSION['user_id_id']);

            $etatCommandeQuery = "SELECT etat_commande, idclient_commande, id_client, id_gestionnaire
                FROM commande co
                LEFT JOIN client cl ON co.idclient_commande = cl.id_client 
                WHERE id_commande = '$id_commande'";
            $etatCommandeResult = mysqli_query($con, $etatCommandeQuery);

            if ($etatCommandeResult && $etatCommandeRow = mysqli_fetch_assoc($etatCommandeResult)) {
                $etatActuel = $etatCommandeRow['etat_commande'];
                $id_client = $etatCommandeRow['id_client'];
                $id_gestionnaire = $etatCommandeRow['id_gestionnaire'];

                switch ($etatActuel) {
                    case 'à valider':
                        $message = "Votre commande N°".$id_commande." est en cours de préparation.";
                        $updateQuery = "UPDATE commande SET etat_commande = 'en préparation', date_preparation = NOW() WHERE id_commande = '$id_commande'";
                        break;

                    case 'en préparation':
                        $message = "Votre commande N°".$id_commande." est en cours d\'envoi.";
                        $updateQuery = "UPDATE commande SET etat_commande = 'en cours d\'envoi', date_envoi = NOW() WHERE id_commande = '$id_commande'";
                        break;

                    case 'en cours d\'envoi':
                        $message = "Votre commande N°".$id_commande." est en cours de livraison.";
                        $updateQuery = "UPDATE commande SET etat_commande = 'en cours de livraison', date_livraison = NOW() WHERE id_commande = '$id_commande'";
                        break;

                    case 'en cours de livraison':
                        $message = "Votre commande N°".$id_commande." a été livrée.";
                        $updateQuery = "UPDATE commande SET etat_commande = 'livrée', date_livree = NOW() WHERE id_commande = '$id_commande'";
                        break;

                    case 'livrée':
                        break;
                    
                    default:
                        $response = 'Erreur : État non géré';
                        break;
                }

                $result = mysqli_query($con, $updateQuery);

                $msg_query = "INSERT INTO message (date_message, contenu_message, sens, idclient_message, idgestionnaire_message, type_message)
                VALUES (NOW(), '$message', 1, '$id_client', '$id_gestionnaire','notification')";

                $msg_execute = mysqli_query($con,$msg_query);

                if ($result) {
                    $response = 'OK';
                } else {
                    $response = 'Erreur lors de la mise à jour du statut : ' . mysqli_error($con);
                }
            } else {
                $response = 'Erreur lors de la récupération de l\'état actuel de la commande : ' . mysqli_error($con);
            }
        }

        mysqli_close($con);
    }
} else {
    $response = 'Méthode non autorisée';
}

echo $response;
?>
