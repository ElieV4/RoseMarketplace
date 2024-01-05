<?php
    // Inclure les fichiers nécessaires (connexion à la base de données, fonctions, etc.)
    include("./include/connect.php");
    include("./include/fonctions.php");
    $user = $_SESSION['user_id_id'];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Rose. | Contrats</title>
    <script src="javascript/dashboard.js"></script>
    <style>
        .vignette {
            border: 1px solid #ccc;
            padding: 10px;
            margin: 10px;
            border-radius: 8px;
            position: relative;
        }

        .actions {
            position: absolute;
            top: 10px;
            right: 10px;
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .action-button {
            padding: 5px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <?php
        // Récupérer les clients rattachés au gestionnaire
        $query_clients = "SELECT * FROM client LEFT JOIN adresse USING (id_client) WHERE id_gestionnaire = '$user' AND type_adresse='facturation'";
        $result_clients = mysqli_query($con, $query_clients);

        // Afficher les clients sous forme de vignettes
        while ($client = mysqli_fetch_assoc($result_clients)) {
            $id_client = $client['id_client'];
            $nom_client = $client['nom_client'];
            $prenom_client = $client['prenom_client'];
            $email_client = $client['email_client'];

            $query_adresse = "SELECT * FROM adresse WHERE id_adresse = '{$client['id_adresse']}'";
            $adresse = singleQuery($query_adresse);

            // Vérifier le type de client
            $type_client = $client['type_client'];
            $infos_societe = ($type_client == 1) ? "SIREN: {$client['siren_client']}, Raison sociale: {$client['raisonsociale_client']}" : '';

            // Afficher la vignette du client
            echo "
                <div class='vignette'>
                    <p><strong>ID Client:</strong> $id_client</p>
                    <p><strong>Nom:</strong> $nom_client</p>
                    <p><strong>Prénom:</strong> $prenom_client</p>
                    <p><strong>Email:</strong> $email_client</p>
                    <p><strong>Adresse de Facturation:</strong> {$adresse['numetrue_adresse']}, {$adresse['codepostal_adresse']}, {$adresse['villeadresse_adresse']}</p>
                    <p><strong>Infos Société:</strong> $infos_societe</p>

                    <div class='actions'>
                        <button class='action-button' onclick='validerCompte($id_client)'>Valider le compte</button>
                        <button class='action-button' onclick='bloquerCompte($id_client)'>Bloquer le compte</button>
                        <button class='action-button' onclick='editerContrat($id_client)'>Éditer le contrat</button>
                        <button class='action-button' onclick='voirFactures($id_client)'>Voir les factures</button>
                    </div>
                </div>
            ";
        }
    ?>

    <script>
        function validerCompte(idClient) {
            // Ajouter la logique pour valider le compte
            alert('Valider le compte pour le client avec ID ' + idClient);
        }

        function bloquerCompte(idClient) {
            // Ajouter la logique pour bloquer le compte
            alert('Bloquer le compte pour le client avec ID ' + idClient);
        }

        function editerContrat(idClient) {
            // Ajouter la logique pour éditer le contrat
            alert('Éditer le contrat pour le client avec ID ' + idClient);
        }

        function voirFactures(idClient) {
            // Ajouter la logique pour voir les factures
            alert('Voir les factures pour le client avec ID ' + idClient);
        }
    </script>
</body>
</html>
