<?php 
    include("include/connect.php");
    session_start();

    if (!isset($_SESSION['user_id'])) {
        //echo "header vers reconnexion";
        header("Location: user_connexion.php");
        exit();
    }
    if($_SESSION['user_type']=='X'){
        //echo "header vers gestionnaire";
        header("Location: espace_gestionnaire.php");
        exit();
    }
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rose. | Espace Client</title>
    <link rel="stylesheet" type="text/css" href="css/dashboard.css">
    <link rel="stylesheet" type="text/css" href="css/main_style.css">
</head>
<body>
    <?php include('entete.php')?>
    <div class="two-columns">
        <div class="leftbar">
            <h1>Espace Client</h1><br>
            <?php echo "{$_SESSION['user_id']}"; ?><br><br>
            <button class="dash-button"><a href="espace_client_particulier.php?profil">Profil</a></button><br><br>
            <?php
                if($_SESSION['user_type']==1){
                    echo '<button class="dash-button"><a href="espace_client_entreprise.php">Espace entreprise</a></button><br><br>';
                }
            ?>
            <button class="dash-button"><a href="cart.php">Votre panier</a></button><br><br>
            <button class="dash-button"><a href="espace_client_particulier.php?historique_commandes">Historique d'achats</a></button><br><br>
            <button class="dash-button"><a href="espace_client_particulier.php?suivi_commande">Suivi de commande</a></button><br><br>
            <button class="dash-button"><a href="espace_client_particulier.php?messagerie">Messagerie</a></button><br><br>
            <button class="dash-button"><a href="include/logout.php">Déconnexion</a></button><br><br>
        </div>
        <div class="dashboard">
            <?php
                if(isset($_GET['profil'])){
                    include('dashboard/profil.php');
                }                
                else if(isset($_GET['historique_commandes'])){
                    include('dashboard/historique_commandes.php');
                }                
                else if(isset($_GET['suivi_commande'])){
                    include('dashboard/suivi_commande.php');
                } 
                else if(isset($_GET['tri_hist'])){
                    include('dashboard/historique_commandes.php');                
                } 
                else if(isset($_GET['idc'])){
                    include('dashboard/suivi_commande.php');
                } 
                else if(isset($_GET['messagerie'])){
                    include('dashboard/messagerie.php');
                }
                else if(isset($_GET['msg'])){
                    include('dashboard/messagerie.php');
                }
                else {
                    include('dashboard/profil.php');
                }
            ?>
        </div>
    </div>
    <?php include('footer.php')?>
</body>
</html>