<?php 
    include("include/connect.php");
    // Vérifie si l'utilisateur est déjà connecté
    session_start();

    if (isset($_SESSION['user_id'])) {
        //echo $_SESSION['user_id'];
        //echo $_SESSION['user_type'];
    } else {
        //echo "header vers reconnexion";
        header("Location: user_connexion.php");
        exit();
    }
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rose. | Espace Client</title>
    <link rel="stylesheet" type="text/css" href="css/main_style.css">
    <link rel="stylesheet" type="text/css" href="css/chatbox.css">
    <style>
        body {
            margin: 0;
            padding: 0;
        }

        .leftbar {
            width: 20%;
            background-color: #f8f9fa; /* Change the background color as needed */
            padding: 20px;
            box-sizing: border-box;
        }

        .two-columns {
            display: flex;
            justify-content: space-between;
        }

        .dashboard {
            width: 80%;
            text-align: left;
        }

        .dash-button {
            width: 200px;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="navdiv"> 
            <div class="search">
                    <form action="produits.php" method="GET">
                        <div class="icon"></div>
                        <div class="input">
                                <input type="text" placeholder="Rechercher" id="mysearch" name="mysearch">
                                <span class="clear" onclick="document.getElementById('mysearch').value = ''"></span>
                                <button type="submit">Go</button>
                        </div>
                    </form>
            </div>
            <div ></div>
            <div class="logo">
                <a href="index.php"><img src="images/rose.png"></a>
            </div>
            <ul>
                <?php
                    if(isset($_SESSION['user_id'])){
                        echo '<li ><a href="include/logout.php"><img src="images/logout1.png"></a></li>';
                    } else {
                        echo '<li ><a href="user_registration.php">Inscription</a></li>';
                        echo '<li ><a href="user_connexion.php"><img src="images/client.png"></a></li>';
                    }
                ?>
                <li ><a href="cart.php"><img src="images/cart.png"></a></li>
                <div ></div>
                <button class="hamburger">
                    <div class="bar"></div>
                </button>
            </ul>
        </div>
    </nav>

    <nav class="mobile-nav">
        <a href="index.php">Accueil</a>
        <?php 
        if(isset($_SESSION['user_id'])){
            echo '<a href="espace_client_entreprise.php">Espace Client</a>';
        } else {
            echo '<a href="user_connexion.php">Espace Client</a>';
        }
        ?>
        <a href="produits.php">Tous les produits</a>
        <a href="produits.php?categorie=outillerie&marque=all">Outillerie</a>
        <a href="produits.php?categorie=peinture&droguerie&marque=all">Peinture</a>
        <a href="aproposde.php">A propos de ROSE.</a>
        <?php 
        if(isset($_SESSION['user_id'])){
            echo '<a href="include/logout.php">Déconnexion</a>';
        } else {
            echo '<a href="user_connexion.php">Connexion</a>';
        }
        ?>        
    </nav>

    <div class="two-columns">
        <div class="col2">
            <h1>Espace Client</h1><br>
            <button class="dash-button"><a href="espace_client_particulier.php?">Profil</a></button><br><br>
            <?php
                if($_SESSION['user_type']==1){
                    echo '<button class="dash-button"><a href="espace_client_entreprise.php">Espace entreprise</a></button><br><br>';
                }
            ?>
            <button class="dash-button"><a href="cart.php">Votre panier</a></button><br><br>
            <button class="dash-button"><a href="espace_client_particulier.php?">Historique de commandes</a></button><br><br>
            <button class="dash-button"><a href="espace_client_particulier.php?">Suivre votre commande</a></button><br><br>
            <button class="dash-button"><a href="espace_client_particulier.php?">Vos moyens de paiements</a></button><br><br>
            <button class="dash-button"><a href="include/logout.php">Déconnexion</a></button><br><br>
        </div>
        <div class="dashboard">
            <?php
                if(isset($_GET['#'])){
                    include('#');
                }
            ?>
        </div>
    </div>
    


    <div class="chatbox">
        <div class="chat-header">
            Chat en direct
            <span class="close-chat">&times;</span>
        </div>
        <div class="chat-content">
            <!-- Contenu de la boîte de chat -->
        </div>
        <div class="chat-input">
            <input type="text" placeholder="Tapez votre message...">
            <button>Envoyer</button>
        </div>
    </div>

    <footer class="footer">
        <br><br>
        <div class="terms"><a target="_blank" href="confidentialite.php" id="privacy-policy" class="qa-privacypolicy-link" rel="noopener">Politique de confidentialité</a> | <a href="conditions-generales.html" id="terms-and-conditions" class="qa-tandc-link" target="_blank" rel="noopener">Termes et Conditions</a><p>Copyright © ROSE. 2023<br></p></div>
        <br>
    </footer>

    <script src="javascript/chatbox.js"></script>
    <script src="javascript/burgernavbar.js"></script>
    <script src="javascript/search.js"></script>
</body>
</html>