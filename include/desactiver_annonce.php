<?php
    include 'connect.php';
    include 'fonctions.php';
    session_start();
    $id_gestionnaire = $_SESSION['user_id_id'];

    $data = json_decode(file_get_contents("php://input"));
    $id_produit = $data->idProduit; 

    $produitQuery = "SELECT * FROM produit WHERE id_produit = '$id_produit'";
    $produitRow = singleQuery($produitQuery);
    $statut_produit = $produitRow['statut_produit'];

    switch ($statut_produit) {
        case 'disponible':
            $message = "Votre annonce".$produitRow['nom_produit']." a été désactivée par votre gestionnaire ROSE.";
            $updateQuery = "UPDATE produit SET statut_produit = 'désactivé' WHERE id_produit = '$id_produit'";
            $buttonText = "Réactiver l'annonce";
            break;
        case 'désactivé':
            $message = "Votre annonce".$produitRow['nom_produit']." a été réactivée par votre gestionnaire ROSE.";
            $updateQuery = "UPDATE produit SET statut_produit = 'disponible' WHERE id_produit = '$id_produit'";
            $buttonText = "Désactiver l'annonce";
            break;
        default:
            $response = array('status' => 'error', 'message' => 'Erreur : statut non géré');
            echo json_encode($response);
            exit();
    }

    $updateresult = mysqli_query($con, $updateQuery);

    $msg_query = "INSERT INTO message (date_message, contenu_message, sens, idclient_message, idgestionnaire_message, type_message)
    VALUES (NOW(), '$message', 1, '$id_produit', '$id_gestionnaire', 'notification')";

    $msg_execute = mysqli_query($con, $msg_query);

    if ($msg_execute) {
        $response = array('status' => 'success', 'message' => 'Statut du produit mis à jour avec succès.', 'buttonText' => $buttonText);
        echo json_encode($response);
    } else {
        $response = array('status' => 'error', 'message' => 'Erreur lors de la mise à jour du statut du produit : ' . mysqli_error($con), 'buttonText' => '');
        echo json_encode($response);
    }
?>
