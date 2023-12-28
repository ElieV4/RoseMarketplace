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
    <title>Rose. | Espace Client</title>
    <link rel="stylesheet" type="text/css" href="css/main_style.css">
    <link rel="stylesheet" type="text/css" href="css/chatbox.css">
    <style>
        .outer-container{
            margin-left:10%;
            margin-right:10%;
            margin-top:10px;
            margin-bottom:10px;
            background-color: white;
            align-items: center;
            text-align: left;
        }
        .content{
            text-align: justify;
        }
        .imgcontainer {
                display : flex;
                align-items: center;
                width:80px;
                height:auto;
        }
        .two-columns {
        display: flex;
        justify-content: space-between;
        }

        .col1 {
            width: 70%;
        }

        .col2 {
            width: 30%;
            margin-left : 100px;
            text-align: left;
            min-width : 300px;
        }
        .inputquantite {
            max-width: 30px;
        }
        .quantity-container {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .emptycart {
            align-items = center;
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
   
    <div class="outer-container">
        <div class="content">
            <h1>Votre panier</h1><br>
            <div class="two-columns">
                <div class="col1">
                    <?php
                        $select_query = "SELECT id_produit,quantité_produit,quantitestock_produit, description_produit,MIN(id_photo_produit) AS min_photo_id, image_type, image, nom_produit, categorie_produit, marque_produit, prixht_produit, raisonsociale_client 
                        FROM panier 
                        LEFT JOIN produit USING (id_produit) 
                        LEFT JOIN photo USING (id_produit) 
                        LEFT JOIN client ON produit.id_fournisseur = client.id_client 
                        GROUP BY id_produit
                        ORDER BY date_ajout_produit DESC";
                        $result = mysqli_query($con, $select_query);
                        $numrows = mysqli_num_rows($result);
                        if($numrows==0){
                            echo "<div class=emptycart>
                                <p>Vous n'avez pas de produits dans votre panier.<p><br>
                                <a href='produits.php'><button>Continuer vos achats</button></a>
                            </div>";
                        } else {
                            if ($result) {
                                $montant_commande = 0;
                                echo "<table border='0,5'>";
                                // Parcourir les résultats et afficher chaque ligne dans le tableau
                                while ($rowdata = mysqli_fetch_assoc($result)) {

                                    $id_produit = $rowdata['id_produit'];
                                    $filepath = $rowdata['image'];
                                    $image_type = $rowdata['image_type'];
                                    $produit = $rowdata['nom_produit'];
                                    $marque = $rowdata['marque_produit'];
                                    $vendeur = $rowdata['raisonsociale_client'];
                                    $prixTTC = $rowdata['prixht_produit'] * 1.2;
                                    $date_ajout = $rowdata['date_ajout_produit'];
                                    $quantitepanier = $rowdata['quantité_produit'];
                                    $montant_produit = $quantitepanier * $prixTTC ;
                                    $montant_commande = $montant_commande + $montant_produit;

                                    echo '<tr>
                                            <td><a href="page_produit.php?id='.$id_produit.'"><img class="imgcontainer" src="data:' . $image_type . ';base64,' . base64_encode($filepath) . '" style="max-width: 100%; max-height: 100%;"></a></td>
                                            <td><a href="page_produit.php?id='.$id_produit.'">'.$produit.' '.$marque.'</a><br><i>'.$vendeur.'</i></td>
                                            <td>     </td>
                                            <td>
                                                <div class="quantity-container">
                                                    <input class="inputquantite" type="number" min="1" id="quantiteInput_'.$id_produit.'" value="'.$quantitepanier.'">
                                                    <button onclick="updateQuantite('.$id_produit.')">OK</button>
                                                </div>
                                            </td>
                                            <td>'.$montant_produit.'€</td>
                                            <td><button onclick="supprimerProduit(' . $id_produit . ', function() { location.reload(); })">X</button></td>
                                        </tr>';
                                    
                                }
                                echo '</table><br>';
                            } else {
                                // En cas d'erreur lors de l'exécution de la requête
                                echo 'Erreur dans la requête : ' . mysqli_error($con);
                            }
                        }
                    ?>    
                </div>
                <div class="col2">
                    <?php
                        if($numrows>0){
                            echo '<br><h3>Panier(' .$numrows.')</h3>
                            <p>Frais de livraison estimés : Gratuit</p><br>
                            <p>Total (TVA incluse) : '.$montant_commande. '€</p><br><br>';

                            if(!isset($_SESSION['user_id'])){
                                echo '<a href="user_connexion.php"><button>Valider mon panier</button></a>';
                            } else {
                                echo '<a href="commande.php"><button>Valider votre panier</button></a>';
                            }
                            echo '<br><br><a href="produits.php"><button>Continuer vos achats</button></a>';
                        }
                    ?>
                </div>
            </div>
        </div>
    </div>



    <button class="questionmark">
        <div class="bar"></div>
    </button>


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
    <script src="javascript/cart.js"></script>
</body>
</html>