<?php 
    include("include/connect.php");
    include("include/fonctions.php");
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
    <style>
        .outer-container{
            margin-left:15%;
            margin-right:15%;
            margin-top:10px;
            margin-bottom:10px;
            background-color: white;
            align-items: center;
            text-align: left;
            padding:20px;
        }
        .imgcontainer {
                align-items: center;
                width:500px;
                heigth:auto;
        }
        .popup{
            border:solid grey 1px;
            padding:4px;
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

                <?php
                echo '<a href="' . $_SERVER['HTTP_REFERER'] . '">Page précédente</a><br>';

                if(isset($_GET['id'])){
                        $id_produit = $_GET['id'];
                        $select_query = "SELECT *
                            FROM produit 
                            LEFT JOIN photo USING (id_produit) 
                            LEFT JOIN client ON produit.id_fournisseur = client.id_client 
                            WHERE id_produit = '$id_produit';";
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
 
                    echo '<img class="imgcontainer" src="data:' . $image_type . ';base64,' . base64_encode($filepath) . '" style="max-width: 10%; max-height: 10%;"><br>';
                    echo ''.$produit." ".$marque.'<br>';
                    echo '<a href="produits.php?mysearch='.$vendeur.'">'.$vendeur.'</a> '.$prixTTC.'€ TTC<br><br>';
                    echo ''.$description.'<br><br>';
                }else{
                    echo 'Erreur DB, veuillez revenir à la page précédente';
                }
                echo '<a href="page_produit.php?id='.$id_produit.'&cart='.$id_produit.'"><button>Ajouter au panier</button></a><br>';

                //ajouter au panier
                if(isset($_GET['cart'])){
                    $id_produit=$_GET['id'];

                    //requete verif produit pas déjà ajouté au panier
                    $select_query2 = "SELECT * FROM panier WHERE id_produit = '$id_produit'";
                    $result2 = mysqli_query($con, $select_query2);
                    $rowdata2 = mysqli_fetch_assoc($result2);
                    if($rowdata2==0){

                        if(isset($_GET['quantite_produit'])){
                            $quantité_produit = $_GET['quantite_produit'];
                        } else {
                            $quantité_produit = 1;
                        }
                        $adresse_ip = get_user_ip();
                        //ajout du produit au panier
                        $insertcart_query = "INSERT INTO panier (id_produit, quantité_produit, adresse_ip)
                        VALUES ('$id_produit', $quantité_produit, '$adresse_ip');";

                        if (mysqli_query($con, $insertcart_query)) {
                            echo '<div class="popup">Produit ajouté au panier<br>';
                            echo '<a href="cart.php"><button>Accéder au panier</button></a>';
                            echo ' <button>Annuler</button></div>';                            
                        } else {
                            echo "Error: " . $insertcart_query . "<br>" . mysqli_error($con);
                        }
                    } else {
                        echo '<div class="popup">Produit déjà présent dans votre panier<br>';
                        echo '<a href="cart.php"><button>Accéder au panier</button></a></div>';
                    }
                }
                ?>
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
        <div class="terms"><a target="_blank" href="confidentialite.php" id="privacy-policy" class="qa-privacypolicy-link" rel="noopener">Politique de confidentialité</a> | <a href="conditions-generales.php" id="terms-and-conditions" class="qa-tandc-link" target="_blank" rel="noopener">Termes et Conditions</a><p>Copyright © ROSE. 2023<br></p></div>
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
