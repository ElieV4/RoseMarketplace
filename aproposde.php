
<?php 
    include("include/connect.php");
    session_start();

    if (isset($_SESSION['user_id'])) {
        $user = $_SESSION['user_id_id'];
    }
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rose. | A propos de nous</title>
    <link rel="stylesheet" type="text/css" href="css/main_style.css">
    <link rel="stylesheet" type="text/css" href="css/chatbox.css">
    <style>
        .outer-container{
            margin-left:25%;
            margin-right:25%;
            margin-top:10px;
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
                        echo '<li ><a href="espace_client_particulier.php"><img src="images/client.png"></a></li>';
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
        if(isset($_SESSION['user_id'])&&$_SESSION['user_type']==1){
            echo '<a href="espace_client_entreprise.php">Espace Entreprise</a>';
        } else if (isset($_SESSION['user_id'])&&$_SESSION['user_type']=='X'){
            echo '<a href="user_connexion.php">Espace Admin</a>';
        } else {
            echo '<a href="user_connexion.php">Espace Client</a>';
        }
        ?>
        <a href="produits.php">Tous les produits</a>
        <a href="produits.php?categorie=outillerie&marque=all">Outillerie</a>
        <a href="produits.php?categorie=peinture_droguerie&marque=all">Peinture</a>
        <a href="aproposde.php">A propos de ROSE.</a>
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
            <h1>Notre Histoire</h1>
            <br>
            <p>Le projet de ROSE., est né de notre passion partagée pour le bricolage et de notre désir de casser les conventions. Nous nous sommes rencontrés en 2019 au début de nos études, et avons rapidement découvert notre point commun inhabituel. À l'obtention de notre diplôme, il est devenu évident que nous devions concrétiser notre vision.</p>
            <br>
            <div class="imgcontainer">
                <img src="images/friendssmiling.png">
            </div>
            <br>
            <p>En tant que fervents amateurs de bricolage, nous avons toujours pris plaisir à créer et à rénover. Cependant, nous avons rapidement remarqué le manque d'originalité et de personnalité dans le monde des outils et des fournitures de bricolage. C'est à ce moment-là que l'idée de ROSE. a commencé à prendre forme. Notre objectif était de transformer l'expérience du bricolage en créant une marketplace unique qui le rendrait plus amusant, accessible et inspirant pour tous.</p>
            <br>
            <p>Le nom "ROSE." a été choisi pour évoquer la créativité et pour casser les codes associés à la couleur rose dans le monde du bricolage. Notre marketplace est devenue un espace où la couleur, le style et la pratique du bricolage se sont réunis. Au-delà de la simple vente d'outils, nous avons créé une communauté où les amateurs de bricolage peuvent se rassembler, partager des idées, apprendre et s'inspirer mutuellement.</p>
            <br>
            <div class="imgcontainer" >
                <img class="sideimg" src="images/friendsstanding.png"><img class="sideimg" src="images/groupworking.png">
            </div>
            <br>
            <p>C'est ainsi qu'est née la première marketplace de bricolage rose. Notre passion commune pour le bricolage a donné naissance à une aventure passionnante. ROSE. est bien plus qu'un projet pour nous, c'est une aventure que nous sommes fiers de partager avec vous.</p>
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
        <br><br>
    </footer>

    <script src="javascript/chatbox.js"></script>
    <script src="javascript/burgernavbar.js"></script>
    <script src="javascript/search.js"></script>
</body>
</html>