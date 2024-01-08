<?php 
    include("include/connect.php");
    session_start();

    if (isset($_SESSION['user_id'])) {
        //echo $_SESSION['user_id']." est connecté";
    } else {
        //echo "déconnecté";
    }
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rose. | Accueil</title>
    <link rel="stylesheet" type="text/css" href="css/index.css">
    <link rel="stylesheet" type="text/css" href="css/main_style.css">    
</head>
<body>
    <?php include('include/entete.php')?>
    <div class="outer-container">
        <div class="content">
            <div class="slogan">
                <h1>Construisez la vie en ROSE.</h1>
                <br>
                <?php
                if(!isset($_SESSION['user_id'])){
                    echo '<h1><a href="user_registration.php"><button class="bigbutton">Rejoignez-nous !</button></a></h1><br>';
                }
                ?>
                <br>
            </div>
            <div class="images-container">
                <a href="produits.php?categorie=jardin&marque=all"><img src="images/jardinerie_tools.png" class="category-img">Jardinerie</a>
                <a href="produits.php?categorie=menuiserie_bois&marque=all"><img src="images/woodshopworking.png" class="category-img">Menuiserie</a>
                <a href="produits.php?categorie=outillerie&marque=all"><img src="images/pinktools.png" class="category-img">Outillerie</a>
            </div>
        </div>
    </div>

    <div class="outer-container">
        <div class="content">
            <div class="images-container">
                <a href="produits.php?categorie=chauffage_plomberie&marque=all"><img src="images/bathroom.png" class="category-img">Plomberie</a>
                <a href="produits.php?categorie=peinture_droguerie&marque=all"><img src="images/painting.png" class="category-img">Peinture</a>
                <a href="produits.php?categorie=quincaillerie&marque=all"><img src="images/quincaillerie.png" class="category-img">Quincaillerie</a>
            </div>
        </div>
    </div>
    
    <script>
        const chatbox_btn = document.querySelector('.questionmark');
        const mobile_chatbox = document.querySelector('.chatbox')
        chatbox_btn.addEventListener('click', function() {
        chatbox_btn.classList.toggle('is-active');
        mobile_chatbox.classList.toggle('is-active');
        });
    </script>
     <?php include('include/footer.php')?>
</body>
</html>
