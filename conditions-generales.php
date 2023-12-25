<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rose. | Conditions générales</title>
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
                <div class="icon"></div>
                <div class="input">
                    <input type="text" placeholder="Rechercher" id="mysearch">
                    <span class="clear" onclick="document.getElementById('mysearch').value = ''"></span>
                </div>
            </div>
            <div ></div>
            <div class="logo"><a href="index.php"><img src="images/rose.png"></a></div>
            <ul>
                <li ><a href="user_connexion.php"><img src="images/client.png"></a></li>
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
   
    <div class="outer-container">
        <div class="content">
            <h1>Conditions Générales de ROSE.</h1><br>

            <p><strong>Dernière mise à jour :</strong> 14 novembre 2023</p><br>
        
            <h2>Acceptation des Conditions Générales</h2><br>
            <p>Merci de lire attentivement les présentes Conditions Générales avant d'utiliser la plateforme ROSE. (la "Plateforme"). En accédant ou en utilisant la Plateforme, vous acceptez d'être lié par ces Conditions Générales. Si vous n'acceptez pas toutes les conditions de cette entente, veuillez ne pas utiliser la Plateforme.</p><br>
        
            <h2>Utilisation de la Plateforme</h2><br>
            <p>Vous devez avoir au moins 18 ans pour utiliser la Plateforme. En utilisant la Plateforme et en acceptant ces Conditions Générales, vous déclarez et garantissez que vous avez au moins 18 ans.</p><br>
        
            <h2>Compte Utilisateur</h2><br>
            <p>Vous pouvez être amené à créer un compte pour accéder à certaines parties de la Plateforme. Vous êtes responsable du maintien de la confidentialité de votre compte et des informations associées. Toute activité effectuée sous votre compte est de votre seule responsabilité.</p><br>
        
            <h2>Produits et Commandes</h2><br>
            <p>Les produits disponibles sur la Plateforme sont destinés à un usage personnel ou professionnel. Les commandes sont sujettes à disponibilité. Nous nous réservons le droit de limiter la quantité d'articles pouvant être achetés par personne ou par commande.</p><br>
        
            <h2>Prix et Paiements</h2><br>
            <p>Les prix des produits sont indiqués sur la Plateforme. Les frais de livraison, le cas échéant, sont clairement indiqués lors du processus de commande. Les paiements doivent être effectués via les méthodes de paiement disponibles sur la Plateforme.</p><br>
        
            <h2>Contenu Utilisateur</h2><br>
            <p>En soumettant du contenu sur la Plateforme (commentaires, avis, etc.), vous accordez à ROSE. une licence mondiale, non exclusive, transférable, gratuite et sublicenciable pour utiliser, reproduire, distribuer, préparer des œuvres dérivées, afficher et exécuter ce contenu.</p><br>
        
            <h2>Respect de la Propriété Intellectuelle</h2><br>
            <p>ROSE. respecte les droits de propriété intellectuelle d'autrui. Vous vous engagez à respecter ces droits et à ne pas utiliser la Plateforme de manière à enfreindre ces droits.</p><br>
        
            <h2>Modification et Résiliation</h2><br>
            <p>ROSE. se réserve le droit de modifier, suspendre ou interrompre la Plateforme, en tout ou en partie, à tout moment. Vous pouvez résilier votre compte à tout moment en suivant les instructions sur la Plateforme.</p><br>
        
            <h2>Limitation de Responsabilité</h2><br>
            <p>ROSE. ne garantit pas l'exactitude, l'exhaustivité ou l'actualité des informations sur la Plateforme. L'utilisation de la Plateforme est à vos propres risques. ROSE. ne sera pas responsable des dommages directs, indirects, accessoires, spéciaux ou consécutifs découlant de l'utilisation de la Plateforme.</p><br>
        
            <h2>Dispositions Générales</h2><br>
            <p>Ces Conditions Générales constituent l'intégralité de l'accord entre vous et ROSE. en ce qui concerne l'utilisation de la Plateforme. Si l'une de ces conditions est jugée invalide, les autres resteront en vigueur. Les litiges seront résolus conformément aux lois en vigueur.</p><br>
        
            <p>Nous vous remercions d'utiliser ROSE.</p><br>
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