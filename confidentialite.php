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
    <title>Rose. | Politique de confidentialité</title>
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
        .content ul{
            flex-direction: column;
            text-align: left;

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
            <h1>Politique de Confidentialité de ROSE.</h1><br>

            <p><strong>Dernière mise à jour :</strong> 14 novembre 2023</p><br>
        
            <h2>Collecte des Informations</h2><br>
            <p>Nous collectons des informations lorsque vous créez un compte sur notre Plateforme, passez une commande, participez à un concours ou communiquez avec nous. Les informations collectées incluent votre nom, votre adresse e-mail, votre numéro de téléphone, votre adresse de livraison, etc.</p><br>
        
            <h2>Utilisation des Informations</h2><br>
            <p>Les informations que nous collectons auprès de vous peuvent être utilisées pour :</p>
            <ul>
                <li>Personnaliser votre expérience utilisateur</li>
                <li>Traiter vos transactions</li>
                <li>Vous envoyer des notifications par e-mail</li>
                <li>Améliorer notre Plateforme</li>
                <li>Vous contacter par e-mail ou par téléphone</li>
            </ul><br>
        
            <h2>Protection des Informations</h2><br>
            <p>Nous mettons en œuvre une variété de mesures de sécurité pour protéger la sécurité de vos informations personnelles. Nous utilisons des protocoles de sécurisation des données lors de la transmission de données sensibles.</p><br>
        
            <h2>Cookies</h2><br>
            <p>Nous utilisons des cookies pour nous aider à mémoriser et traiter les articles dans votre panier, comprendre et enregistrer vos préférences pour vos futures visites, suivre les publicités et compiler des données agrégées sur le trafic et l'interaction sur la Plateforme.</p><br>
        
            <h2>Divulgation à des Tiers</h2><br>
            <p>Nous ne vendons, n'échangeons ni ne transférons vos informations personnelles identifiables à des tiers. Cela n'inclut pas les tiers de confiance qui nous aident à exploiter notre Plateforme, tant qu'ils acceptent de garder ces informations confidentielles.</p><br>
        
            <h2>Consentement</h2><br>
            <p>En utilisant notre Plateforme, vous consentez à notre politique de confidentialité.</p><br>
        
            <h2>Changements de notre Politique de Confidentialité</h2><br>
            <p>Si nous décidons de changer notre politique de confidentialité, nous publierons ces changements sur cette page.</p><br>
        
            <h2>Nous Contacter</h2><br>
            <p>Si vous avez des questions concernant cette politique de confidentialité, vous pouvez nous contacter en utilisant les informations ci-dessous :</p>
            <address>
                ROSE.<br>
                123 Rue des Outils Roses, 75001 Paris, France<br>
                contact@rose.com<br>
                +33 1 23 45 67 89
            </address><br>
        
            <p>Merci de faire confiance à ROSE.</p><br>
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