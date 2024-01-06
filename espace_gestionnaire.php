<?php 
    include("include/connect.php");
    // Vérifie si l'utilisateur est déjà connecté
    session_start();

    if (isset($_SESSION['user_id'])) {
        //echo $_SESSION['user_id'];
        //echo $_SESSION['user_type'];
        // Si l'utilisateur est connecté et type 0, redirige vers espace_client_particulier.php
        if($_SESSION['user_type']==0){
            echo "header vers particulier";
            header("Location: espace_client_particulier.php");
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
    <title>Rose. | Admin</title>
    <link rel="stylesheet" type="text/css" href="css/main_style.css">
    <link rel="stylesheet" type="text/css" href="css/chatbox.css">
    <link rel="stylesheet" type="text/css" href="css/dashboard.css">
    <script src="javascript/produits.js"></script>
</head>

<body>
    <?php include('entete.php')?>
    <div class="two-columns">
        <div class="leftbar">
            <h1>Espace Admin</h1><br>
                    <?php echo "{$_SESSION['user_id']}"; ?><br><br>
                    <?php if($_SESSION['user_id_id']=='G1'){
                        echo '<button class="dash-button"><a href="espace_gestionnaire.php?gestionnaires">Gestionnaires</a></button><br><br>';
                    }?>
                    <button class="dash-button"><a href="espace_gestionnaire.php?contrats">Contrats</a></button><br><br>
                    <button class="dash-button"><a href="espace_gestionnaire.php?factures">Factures</a></button><br><br>
                    <button class="dash-button"><a href="espace_gestionnaire.php?messagerie">Messagerie</a></button><br><br>
                    <button class="dash-button"><a href="include/logout.php">Déconnexion</a></button><br><br>
        </div>
        <div class="dashboard">
            <?php
                if(isset($_GET['gestionnaires'])){
                    include('dashboard/gestionnaires.php');
                } 
                if(isset($_GET['contrats'])){
                    include('dashboard/contrats.php');
                } 
                else if(isset($_GET['factures'])){
                    include('dashboard/factures.php');
                }
                else if(isset($_GET['messagerie'])){
                    include('dashboard/messagerie.php');
                }
                else if(isset($_GET['msg'])){
                    include('dashboard/messagerie.php');
                }
            ?>
        </div>
    </div>
    <?php include('footer.php')?>
</body>
</html>