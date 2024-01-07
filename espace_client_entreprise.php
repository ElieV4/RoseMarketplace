<?php 
    include("include/connect.php");
    // Vérifie si l'utilisateur est déjà connecté
    session_start();

    if (isset($_SESSION['user_id'])) {
        $statut = $_SESSION['statut'];
        $user_id = $_SESSION['user_id_id'];
        //echo $_SESSION['user_id'];
        //echo $_SESSION['user_type'];
        // Si l'utilisateur est connecté et type 0, redirige vers espace_client_particulier.php
        if($_SESSION['user_type']==0){
            //echo "header vers particulier";
            header("Location: espace_client_particulier.php");
            exit();
        } else if($_SESSION['user_type']=='X'){
            //echo "header vers gestionnaire";
            header("Location: espace_gestionnaire.php");
            exit();
        }
    } else {
        //echo "reconnexion demandée";
        header("Location: user_connexion.php");
        exit();
    }
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Rose. | Espace Entreprise</title>
    <link rel="stylesheet" type="text/css" href="css/dashboard.css">
    <link rel="stylesheet" type="text/css" href="css/statut_error.css">
    <link rel="stylesheet" type="text/css" href="css/main_style.css">
    <script src="javascript/produits.js"></script>
</head>

<body>
<?php include('entete.php')?>
    <?php if ($statut == 'validé') : ?>
    <div class="two-columns">
        <div class="leftbar">
            <h1>Espace Entreprise</h1><br>
                    <?php echo "{$_SESSION['user_id']}"; ?><br><br>
                    <button class="dash-button"><a href="espace_client_particulier.php">Espace client</a></button><br><br>
                    <button class="dash-button"><a href="espace_client_entreprise.php?produits_stocks">Produits & Stocks</a></button><br><br>
                    <button class="dash-button"><a href="espace_client_entreprise.php?commandes">Commandes en cours</a></button><br><br>
                    <button class="dash-button"><a href="espace_client_entreprise.php?ventes">Ventes</a></button><br><br>
                    <button class="dash-button"><a href='espace_client_entreprise.php?factures&idf='.$user_id>Factures</a></button><br><br>
                    <button class="dash-button"><a href="include/logout.php">Déconnexion</a></button><br><br>
        </div>
        <div class="dashboard">
            <?php

                if(isset($_GET['produits_stocks'])){
                    include('dashboard/produits_stocks.php');
                } 
                else if(isset($_GET['commandes'])){
                    include('dashboard/commandes.php');
                }
                else if(isset($_GET['tri_comm'])){
                    include('dashboard/commandes.php');
                }
                else if(isset($_GET['ajouter_un_produit'])){
                    include('dashboard/ajouter_un_produit.php');
                }                
                else if(isset($_GET['modifproduit'])){
                    include('dashboard/modifier_produit.php');
                }
                else if(isset($_GET['ventes'])){
                    include('dashboard/ventes.php');
                }               
                else if(isset($_GET['tri-vt'])){
                        include('dashboard/ventes.php');
                }
                else if(isset($_GET['factures'])){
                    include('dashboard/factures.php');
                }                
                else if(isset($_GET['tri-fact'])){
                    include('dashboard/factures.php');
                } else {
                    include('dashboard/produits_stocks.php');
                }
            ?>
        </div>
    </div>
    <?php endif; ?>

    <?php if ($statut == 'refusé') : ?>
        <div class="statut-error">
            <h2>Votre accès professionel a été refusé par le gestionnaire.</h2>
            <p>Veuillez <a href="espace_client_particulier.php?messagerie">le contacter</a> pour régulariser votre situation.</p>
            <p><button><a href="espace_client_particulier.php?">Retourner à l'espace client</a></button></p>
        </div>
    <?php endif; ?>
    <?php if ($statut == 'en attente') : ?>
        <div class="statut-error">
            <h2>Votre accès professionel est en cours de traitement par votre gestionnaire référent.</h2>
            <p>Veuillez nous en excusez, votre compte devrait être disponible sous peu.</p>
            <p><button><a href="espace_client_particulier.php?">Retourner à l'espace client</a></button></p>
        </div>
    <?php endif; ?>
    <?php include('footer.php')?>
</body>
</html>