<?php
    include 'connect.php';
    include 'fonctions.php';
    
    session_start();
    $id_gestionnaire = $_SESSION['user_id_id'];

    // Récupérez les données envoyées depuis JavaScript
    $data = json_decode(file_get_contents("php://input"));
    $id_client = $data->idClient; // Modifié le nom de la propriété pour correspondre à celle utilisée dans JavaScript
    $nouveauStatut = $data->nouveauStatut;

    // Récupérer le statut actuel du client
    $clientQuery = "SELECT * FROM client WHERE id_client = '$id_client'";
    $clientRow = singleQuery($clientQuery);
    $statut = $clientRow['statut_pro'];

    switch ($nouveauStatut) {
        case 'validé':
            $message = "Votre compte professionnel a été validé par votre gestionnaire ROSE.";
            $updateQuery = "UPDATE client SET statut_pro = '$nouveauStatut' WHERE id_client = '$id_client'";
            break;
        case 'refusé':
            $message = "Votre compte professionnel a été bloqué par votre gestionnaire ROSE. Contactez-le pour régulariser votre situation.";
            $updateQuery = "UPDATE client SET statut_pro = '$nouveauStatut' WHERE id_client = '$id_client'";
            $desactivateProductsQuery = "UPDATE produit SET statut_produit = 'désactivé' WHERE id_fournisseur = '$id_client' AND statut_produit = 'disponible'";
            $desactivateProductsResult = mysqli_query($con, $desactivateProductsQuery);
            break;
        default:
            $response = array('status' => 'error', 'message' => 'Erreur : statut non géré');
            echo json_encode($response);
            exit(); // Ajout de l'instruction exit() pour éviter tout affichage HTML non souhaité
    }

    $updateresult = mysqli_query($con, $updateQuery);

    $msg_query = "INSERT INTO message (date_message, contenu_message, sens, idclient_message, idgestionnaire_message, type_message)
    VALUES (NOW(), '$message', 1, '$id_client', '$id_gestionnaire', 'notification')";
    
    $msg_execute = mysqli_query($con, $msg_query);

    if ($msg_execute) {
        $response = array('status' => 'success', 'message' => 'Statut mis à jour avec succès.');
        echo json_encode($response);
    } else {
        $response = array('status' => 'error', 'message' => 'Erreur lors de la mise à jour du statut : ' . mysqli_error($con));
        echo json_encode($response);
    }
?>

