<?php
    include("./include/connect.php");
    include("./include/fonctions.php");
    $user = $_SESSION['user_id_id'];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Rose. | Contrats</title>
    <link rel="stylesheet" type="text/css" href="./css/contrats.css">
    <script src="javascript/dashboard.js"></script>
    <script src="javascript/admin.js"></script>
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
            <button class="action-button"><a href="espace_gestionnaire.php?factures&idf=<?php echo $id_client; ?>">Voir les factures</a></button>
        </div>
    </div>
        <?php };?> 
</body>
</html>
