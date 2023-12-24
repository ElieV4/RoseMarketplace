<?php 
    include("include/connect.php");
    // Vérifie si l'utilisateur est déjà connecté
    session_start();

    if (isset($_SESSION['user_id'])) {
        //echo $_SESSION['user_id'];
        //echo $_SESSION['user_type'];
        // Si l'utilisateur est connecté et type 0, redirige vers espace_client_particulier.php
        if($_SESSION['user_type']==0){
            //echo "header vers particulier";
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
    <title>Rose. | Espace Entreprise</title>
    <link rel="stylesheet" type="text/css" href="css/main_style.css">
    <link rel="stylesheet" type="text/css" href="css/chatbox.css">
    <style>
        .outer-container{
            margin-left:25%;
            margin-right:25%;
            margin-top:5px;
            margin-bottom:10px;
            background-color: white;
            align-items: center;
            text-align: left;
            padding:20px;
        }
        .content{
            text-align: justify;
        }
        .imgcontainer sideimg {
                display:flex;
                align-items: center;
                margin:5%;
                padding:10px;
                width:30%;
                height:auto;
            }
    </style>
</head>

<body>

    <nav class="navbar">
        <div class="navdiv"> 
            <div class="search">
                <div class="icon"></div>
                <div class="input">
                    <input type="text" placeholder="Rechercher" id="mysearch">
                    <span class="clear" onclick="document.getElementById('mysearch').value = ''"></span>
                </div>
            </div>
            <div ></div>
            <div class="logo"><a href="index.php"><img src="images/rose.png"></a></div>
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
        <a href="index.php">Home</a>
        <?php 
        if(isset($_SESSION['user_id'])){
            echo '<a href="espace_client_entreprise.php">Espace Client</a>';
        } else {
            echo '<a href="user_connexion.php">Espace Client</a>';
        }
        ?>
        <a href="#">Produits</a>
        <a href="#">Catégories</a>
        <a href="aproposde.html">A propos de ROSE.</a>
        <?php 
        if(isset($_SESSION['user_id'])){
            echo '<a href="include/logout.php">Déconnexion</a>';
        } else {
            echo '<a href="user_connexion.php">Connexion</a>';
        }
        ?>
    </nav>
   
    <div class="outer-container">
        <div class="content">
            <br><div>Entreprise</div><br>
            <div class="row">
                <div>
                    <a href=""><img src="images/product1.png" alt="images/product1.png"></a>
                    <p><?php echo "{$_SESSION['user_id']}"; ?></p>
                </div>
                <ul>
                    <li><button><a href="">Produits & Stocks</a></button></li>
                    <li><button><a href="">Commandes</a></button></li>
                    <li><button><a href="">Paiements</a></button></li>
                    <li><button><a href="espace_client_entreprise.php?ajouter_un_produit">Ajouter un produit TEST</a></button></li>
                    <li><button><a href="include/logout.php">Déconnexion</a></button></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="dashboard">
        <?php
            if(isset($_GET['ajouter_un_produit'])){
                include('dashboard/ajouter_un_produit.php');
            }
        ?>
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
        <div class="terms"><a target="_blank" href="confidentialite.html" id="privacy-policy" class="qa-privacypolicy-link" rel="noopener">Politique de confidentialité</a> | <a href="conditions-generales.html" id="terms-and-conditions" class="qa-tandc-link" target="_blank" rel="noopener">Termes et Conditions</a><p>Copyright © ROSE. 2023<br></p></div>
        <br><br>
    </footer>

    <script src="javascript/chatbox.js"></script>
    <script src="javascript/burgernavbar.js"></script>
    <script src="javascript/search.js"></script>
</body>
</html>