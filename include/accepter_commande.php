<?php
    include 'connect.php';
    session_start();

    $data = json_decode(file_get_contents("php://input"));
    $id_commande = $data->commandeId;
    $nouveauStatut = $data->nouveauStatut;
    //

    $etatCommandeQuery = "SELECT etat_commande, idclient_commande, id_client, id_gestionnaire
        FROM commande co
        LEFT JOIN client cl ON co.id_fournisseur = cl.id_client 
        WHERE id_commande = '$id_commande'";
    $etatCommandeResult = mysqli_query($con, $etatCommandeQuery);

    if ($etatCommandeResult = mysqli_fetch_assoc($etatCommandeResult)) {
        $id_fournisseur = $etatCommandeRow['id_client'];
        $id_client = $etatCommandeRow['idclient_commande'];
        $id_gestionnaire = $etatCommandeRow['id_gestionnaire'];

    switch ($nouveauStatut) {
        case 'validée':
            $message = "Votre commande N°".$id_commande." a été validée par le client.";
            $updateQuery = "UPDATE commande SET etat_commande = '$nouveauStatut' WHERE id_commande = '$id_commande'";
            break;
        case 'refusée':
            $message = "Votre commande N°".$id_commande." a été refusée par le client. Contactez votre gestionnaire.";
            $updateQuery = "UPDATE commande SET etat_commande = '$nouveauStatut' WHERE id_commande = '$id_commande'";
            break;
        default:
            $response = 'Erreur : État non géré';
            break;
    }
    $result = mysqli_query($con, $updateQuery);

    $msg_query = "INSERT INTO message (date_message, contenu_message, sens, idclient_message, idgestionnaire_message, type_message)
    VALUES (NOW(), '$message', 1, '$id_fournisseur', '$id_gestionnaire','notification')";
    
    $msg_execute = mysqli_query($con,$msg_query);

    if (mysqli_query($con, $updateQuery)) {

        $response = array('status' => 'success', 'message' => 'Statut mis à jour avec succès.');
        echo json_encode($response);
    } else {
        $response = array('status' => 'error', 'message' => 'Erreur lors de la mise à jour du statut : ' . mysqli_error($con));
        echo json_encode($response);
    }
    }
?>