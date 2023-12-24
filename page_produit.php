<?php 
    include("include/connect.php");
    // Vérifie si l'utilisateur est déjà connecté
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
    <title>Rose. | Produits</title>
    <link rel="stylesheet" type="text/css" href="css/main_style.css">
    <link rel="stylesheet" type="text/css" href="css/chatbox.css">
    <link rel="stylesheet" type="text/css" href="css/chatbox.css">


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

                <?php
                        $select_query = "SELECT *
                            FROM produit 
                            LEFT JOIN photo USING (id_produit) 
                            LEFT JOIN client ON produit.id_fournisseur = client.id_client 
                            WHERE id_produit = 19;";
                        $result = mysqli_query($con, $select_query);

                        $rowdata = mysqli_fetch_assoc($result);
                        $filepath = $rowdata['image'];
                        $image_type = $rowdata['image_type'];
                        $produit = $rowdata['nom_produit'];
                        $marque = $rowdata['marque_produit'];
                        $vendeur = $rowdata['raisonsociale_client'];
                        $categorie = $rowdata['categorie_produit'];
                        $prixTTC = $rowdata['prixht_produit'] * 1.2;
                        $stock = $rowdata['quantitestock_produit'];
                        $date_ajout = $rowdata['date_ajout_produit'];
                        $description = $rowdata['description_produit'];

                    echo '<img src="data:' . $image_type . ';base64,' . base64_encode($filepath) . '" style="max-width: 10%; max-height: 10%;"><br>';
                    echo ''.$produit." ".$marque.'<br>';
                    echo ''.$vendeur." ".$prixTTC.'€<br>';
                    echo ''.$description.'<br>';
                ?>
            <a href="#"><button>Acheter</button></a>
        </div>
    </div> 

    <button class="questionmark">
        <div class="bar"></div>
    </button>
    <div class="chatbox">
        <div class="chat-header">
            Chat en direct
            <span class="close-chat" id="closeNavBtn">&times;</span>
        </div>
        <div class="chat-content">
            <!-- Contenu de la boîte de chat -->
        </div>
        <div class="chat-input">
            <input type="text" placeholder="Tapez votre message...">
            <button><!--Envoyer--></button>
        </div>
    </div>

    <footer class="footer">
        <br><br>
        <div class="terms"><a target="_blank" href="confidentialite.html" id="privacy-policy" class="qa-privacypolicy-link" rel="noopener">Politique de confidentialité</a> | <a href="conditions-generales.html" id="terms-and-conditions" class="qa-tandc-link" target="_blank" rel="noopener">Termes et Conditions</a><p>Copyright © ROSE. 2023<br></p></div>
        <br><br>
    </footer>

    <script>
        const chatbox_btn = document.querySelector('.questionmark');
        const mobile_chatbox = document.querySelector('.chatbox')
        chatbox_btn.addEventListener('click', function() {
        chatbox_btn.classList.toggle('is-active');
        mobile_chatbox.classList.toggle('is-active');
        });
    </script>
    <script src="javascript/burgernavbar.js"></script>
    <script src="javascript/search.js"></script>
</body>
</html>
