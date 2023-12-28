<?php
include("include/connect.php");

// Récupérer les données de la requête Ajax
$data = json_decode(file_get_contents("php://input"));

// Action pour ajouter une adresse
if (isset($data->action) && $data->action == 'ajouterAdresse') {
    ajouterAdresse($data->idClient, $data->nouvelleAdresse);
}

// Action pour copier l'adresse de facturation comme adresse de livraison
if (isset($data->action) && $data->action == 'copierLivraison') {
    copierLivraison($data->idClient);
}

// Fonction pour ajouter une adresse
function ajouterAdresse($idClient, $nouvelleAdresse) {
    global $con;

    // Insérer la nouvelle adresse dans la base de données (à adapter selon votre structure)
    $insert_query = "INSERT INTO adresse (id_client, type_adresse, numetrue_adresse, codepostal_adresse, villeadresse_adresse) 
                     VALUES ('$idClient', '$nouvelleAdresse->type', '$nouvelleAdresse->numetrue', '$nouvelleAdresse->codepostal', '$nouvelleAdresse->ville')";

    $result = mysqli_query($con, $insert_query);

    if ($result) {
        echo "Adresse ajoutée avec succès";
    } else {
        echo "Erreur lors de l'ajout de l'adresse : " . mysqli_error($con);
    }

    mysqli_close($con);
}

// Fonction pour copier l'adresse de facturation comme adresse de livraison
function copierLivraison($idClient) {
    global $con;

    // Copier l'adresse de facturation comme adresse de livraison
    $copy_query = "INSERT INTO adresse (id_client, type_adresse, numetrue_adresse, codepostal_adresse, villeadresse_adresse) 
                   SELECT id_client, 'livraison', numetrue_adresse, codepostal_adresse, villeadresse_adresse
                   FROM adresse 
                   WHERE id_client = '$idClient' AND type_adresse = 'facturation'";

    $result = mysqli_query($con, $copy_query);

    if ($result) {
        echo "Adresse de livraison copiée avec succès";
    } else {
        echo "Erreur lors de la copie de l'adresse de livraison : " . mysqli_error($con);
    }

    mysqli_close($con);
}
?>
