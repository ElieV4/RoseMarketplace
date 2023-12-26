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
            margin-left:15%;
            margin-right:15%;
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
        .imgcontainer {
                display : flex;
                align-items: center;
                width:120px;
                heigth:auto;
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
                <h1>Mon panier</h1><br>
                <?php
                    $select_query = "SELECT id_produit,quantité_produit,quantitestock_produit, date_ajout_produit, description_produit,MIN(id_photo_produit) AS min_photo_id, image_type, image, nom_produit, categorie_produit, marque_produit, prixht_produit, raisonsociale_client 
                    FROM panier 
                    LEFT JOIN produit USING (id_produit) 
                    LEFT JOIN photo USING (id_produit) 
                    LEFT JOIN client ON produit.id_fournisseur = client.id_client 
                    GROUP BY id_produit";
                    $result = mysqli_query($con, $select_query);
                    
                    if ($result) {
                        // Afficher le tableau HTML
                        echo "<table border='1'>
                                <tr>
                                    <th></th>
                                    <th>ID Produit</th>
                                    <th>Nom du produit</th>
                                    <th>Détails du produit</th>
                                    <th>Fournisseur</th>
                                    <th>Quantité</th>
                                    <th>Prix unitaire TTC</th>
                                    <th>Montant TTC</th>
                                </tr>";
                        $montant_commande = 0;
                        // Parcourir les résultats et afficher chaque ligne dans le tableau
                        while ($rowdata = mysqli_fetch_assoc($result)) {

                            $id_produit = $rowdata['id_produit'];
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
                            $quantitepanier = $rowdata['quantité_produit'];
                            $quantitestock = $rowdata['quantitestock_produit'];
                            $montant_produit = $quantitepanier * $prixTTC ;
                            $montant_commande = $montant_commande + $montant_produit;

                            echo '<tr>
                                    <td><img class="imgcontainer" src="data:' . $image_type . ';base64,' . base64_encode($filepath) . '" style="max-width: 100%; max-height: 100%;"></td>
                                    <td>'.$id_produit.'</td>
                                    <td>'.$produit.'</td>
                                    <td>'.$categorie.', '.$marque. ', '.$description.'</td>
                                    <td>'.$vendeur.'</td>
                                    <td>'.$quantitepanier.'</td>
                                    <td>'.$prixTTC.'€</td>
                                    <td>'.$montant_produit.'€</td>
                                  </tr>';
                        }
                    
                        echo "</table><br>";
                    } else {
                        // En cas d'erreur lors de l'exécution de la requête
                        echo "Erreur dans la requête : " . mysqli_error($con);
                    }
                    echo "Montant de la commande : ". $montant_commande. "€";

            if(!isset($_SESSION['user_id'])){
                echo '<a href="user_connexion.php"><button>Passez commande</button></a>';
            } else {
                echo '<a href="commande.php"><button>Passez commande</button></a>';
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