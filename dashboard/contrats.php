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
            border: 3px solid #ccc;
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
            background-color: black; /* Changez la couleur du bouton selon vos besoins */
            border-radius: 3px;
        }
        .en-attente-border {
        border-color: gray;
        }

        .valide-border {
            border-color: green;
        }

        .refuse-border {
            border-color: red;
        }
    </style>
        <script>
        function editerContrat(idClient) {
            // Ajouter la logique pour éditer le contrat
            alert('Éditer le contrat pour le client avec ID ' + idClient);
        }

        function voirFactures(idClient) {
            // Ajouter la logique pour voir les factures
            alert('Voir les factures pour le client avec ID ' + idClient);
        }
    </script>
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
            $statut = $client['statut_pro'];

            $query_adresse = "SELECT * FROM adresse WHERE id_adresse = '{$client['id_adresse']}'";
            $adresse = singleQuery($query_adresse);

            // Vérifier le type de client
            $type_client = $client['type_client'];
            $infos_societe = ($type_client == 1) ? "SIREN: {$client['siren_client']}, Raison sociale: {$client['raisonsociale_client']}" : '';
    ?>

<div class='vignette <?php echo getBorderColorClass($statut); ?>'>
        <p><strong>ID Client:</strong> <?php echo $id_client;?></p>
        <p><strong>Nom:</strong> <?php echo $nom_client;?></p>
        <p><strong>Prénom:</strong> <?php echo $prenom_client;?></p>
        <p><strong>Email:</strong> <?php echo $email_client;?></p>
        <p><strong>Adresse de Facturation:</strong> <?php echo "{$adresse['numetrue_adresse']}, {$adresse['codepostal_adresse']}, {$adresse['villeadresse_adresse']}" ?></p>
        <?php if($type_client==1) : ;?>
            <p><strong>Infos Société:</strong> <?php echo $infos_societe;?></p>
        <?php endif;?>
        <p><strong>Statut pro ROSE:</strong> <?php echo $statut;?></p>
        <div class="actions">
            <button class="action-button" onclick="validerCompte(<?php echo $id_client; ?>, 'validé')">Valider le compte</button>
            <button class="action-button" onclick="validerCompte(<?php echo $id_client; ?>, 'refusé')">Bloquer le compte</button>
            <a href="page_fournisseur.php?id=<?php echo urldecode($id_client); ?>"><button class="action-button">Gérer les annonces</button></a>
            <button class="action-button" onclick="editerContrat($id_client)">Éditer le contrat</button>
            <button class="action-button" onclick="voirFactures($id_client)">Voir les factures</button>
        </div>
    </div>
        <?php };?> 
</body>
</html>
