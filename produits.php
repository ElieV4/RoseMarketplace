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
                width:250px;
                heigth:auto;
        }
    </style>    
</head>
<body>
    <nav class="navbar">
        <div class="navdiv"> 
            <div class="search">
                <form action="" method="GET">
                    <div class="icon"></div>
                    <div class="input">
                            <input type="text" placeholder="Rechercher" id="mysearch" name="mysearch">
                            <span class="clear" onclick="document.getElementById('mysearch').value = ''"></span>
                            <a href="produits.php?"></a><button type="submit">Go</button></a>
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
            <div class="slogan">
                <h1>Produits ROSE.</h1>
            </div>
            <form action="" method="GET">
                    <label for="categorie">Catégorie :</label>
                    <select name="categorie" id="categorie">
                        <option value="all">Toutes</option>
                        <?php 
                                $select_cat = "SELECT DISTINCT categorie_produit FROM produit";
                                $result_cat = mysqli_query($con,$select_cat);
                                while($rowcat=mysqli_fetch_array($result_cat)) {
                                    $categoriefiltre = $rowcat["categorie_produit"];
                                    echo '<option value="'.$categoriefiltre.'">'.$categoriefiltre.'</option>';
                                }
                        ?>
                    </select>
                    <label for="marque">Marque :</label>
                    <select name="marque" id="marque">
                        <option value="all">Toutes</option>
                        <?php 
                                $select_brands = "SELECT DISTINCT marque_produit FROM produit";
                                $result_brands = mysqli_query($con,$select_brands);
                                while($rowbrand=mysqli_fetch_array($result_brands)) {
                                    $marquefiltre = $rowbrand["marque_produit"];
                                    echo '<option value="'.$marquefiltre.'">'.$marquefiltre.'</option>';
                                }
                        ?>
                    </select>
                    <button type="submit">Voir les produits</button>
            </form>
            <br>
            <div class="product-container">
                <?php   
                    include('include/fonctions.php');
                                  
                    //differentes requetes écrites dans fonctions.php, pour l'instant ici mais lourd
                    if(isset($_GET['mysearch'])){
                        $search = $_GET['mysearch'];
                        
                        $select_query = "SELECT produit.id_produit,MIN(id_photo_produit) AS min_photo_id, image_type, image, nom_produit, categorie_produit, marque_produit, prixht_produit, raisonsociale_client,
                            CONCAT(nom_produit, categorie_produit, marque_produit, raisonsociale_client, description_produit) AS searchfield 
                        FROM produit 
                        LEFT JOIN photo USING (id_produit) 
                        LEFT JOIN client ON produit.id_fournisseur = client.id_client 
                        WHERE CONCAT(nom_produit, categorie_produit, marque_produit, raisonsociale_client, description_produit) like '%$search%'
                        GROUP BY produit.id_produit
                        ORDER BY date_ajout_produit DESC;";
                
                        $result = mysqli_query($con, $select_query);
                        $rows = mysqli_num_rows($result);
                        if($rows == 0){
                            echo "Aucun résultat actuellement sur le site, veuillez reformuler votre requête.";
                        } else {
                            echo $rows.' résultats pour votre recherche "'. $search.'"<br><br>';
                        }
                    } else if(isset($_GET['marque'])){
                        if($_GET['categorie']=='all' and $_GET['marque']=='all'){
                            
                            $select_query = "SELECT produit.id_produit,MIN(id_photo_produit) AS min_photo_id, image_type, image, nom_produit, categorie_produit, marque_produit, prixht_produit, raisonsociale_client 
                            FROM produit 
                            LEFT JOIN photo USING (id_produit) 
                            LEFT JOIN client ON produit.id_fournisseur = client.id_client 
                            WHERE quantitestock_produit > 0 
                            GROUP BY produit.id_produit
                            ORDER BY date_ajout_produit DESC;";
                        
                            $result = mysqli_query($con, $select_query);
                            $rows = mysqli_num_rows($result);
                            if($rows ==0){
                                echo "Pas de produits actuellement sur le site ";
                            } else {
                                echo $rows.' résultats pour votre recherche<br><br>';
                            }
                        } else if($_GET['categorie']== 'all'and $_GET['marque']!=='all'){
                            $marque = $_GET['marque'];
                            
                            $select_query = "SELECT produit.id_produit,MIN(id_photo_produit) AS min_photo_id, image_type, image, nom_produit, categorie_produit, marque_produit, prixht_produit, raisonsociale_client 
                            FROM produit 
                            LEFT JOIN photo USING (id_produit) 
                            LEFT JOIN client ON produit.id_fournisseur = client.id_client 
                            WHERE quantitestock_produit > 0 AND marque_produit = '$marque'
                            GROUP BY produit.id_produit
                            ORDER BY date_ajout_produit DESC;";
                    
                            $result = mysqli_query($con, $select_query);
                            $rows = mysqli_num_rows($result);
                    
                            if($rows ==0){
                                echo "Pas de produits de cette marque actuellement sur le site ";
                            } else {
                                echo $rows.' résultats pour votre recherche "'.$marque.'"<br><br>';
                            }
                        } else if($_GET['categorie']!== 'all'and $_GET['marque']=='all'){
                            $categorie = $_GET['categorie'];

                            $select_query = "SELECT produit.id_produit,MIN(id_photo_produit) AS min_photo_id, image_type, image, nom_produit, categorie_produit, marque_produit, prixht_produit, raisonsociale_client 
                            FROM produit 
                            LEFT JOIN photo USING (id_produit) 
                            LEFT JOIN client ON produit.id_fournisseur = client.id_client 
                            WHERE quantitestock_produit > 0 AND categorie_produit = '$categorie'
                            GROUP BY produit.id_produit
                            ORDER BY date_ajout_produit DESC;";
                    
                            $result = mysqli_query($con, $select_query);
                            $rows = mysqli_num_rows($result);
                            if($rows ==0){
                                echo "Pas de produits de cette catégorie actuellement sur le site ";
                            } else {
                                echo $rows.' résultats pour votre recherche "'.$categorie.'"<br><br>';
                            }
                        } else {
                            $categorie = $_GET['categorie'];
                            $marque = $_GET['marque'];
                            
                            $select_query = "SELECT produit.id_produit,MIN(id_photo_produit) AS min_photo_id, image_type, image, nom_produit, categorie_produit, marque_produit, prixht_produit, raisonsociale_client 
                            FROM produit 
                            LEFT JOIN photo USING (id_produit) 
                            LEFT JOIN client ON produit.id_fournisseur = client.id_client 
                            WHERE quantitestock_produit > 0 AND categorie_produit = '$categorie' AND marque_produit = '$marque'
                            GROUP BY produit.id_produit
                            ORDER BY date_ajout_produit DESC;";
                    
                            $result = mysqli_query($con, $select_query);
                            $rows = mysqli_num_rows($result);
                            if($rows ==0){
                                echo "Pas de produits de ce type actuellement sur le site ";
                            } else {
                                echo $rows.' résultats pour votre recherche "'.$categorie.' '.$marque.'"<br><br>';
                            }
                        }
                    } else {
                        $select_query = "SELECT produit.id_produit,MIN(id_photo_produit) AS min_photo_id, image_type, image, nom_produit, categorie_produit, marque_produit, prixht_produit, raisonsociale_client 
                        FROM produit 
                        LEFT JOIN photo USING (id_produit) 
                        LEFT JOIN client ON produit.id_fournisseur = client.id_client 
                        WHERE quantitestock_produit > 0 
                        GROUP BY produit.id_produit
                        ORDER BY date_ajout_produit DESC;";
                    
                        $result = mysqli_query($con, $select_query);
                        $rows = mysqli_num_rows($result);
                        if($rows ==0){
                            echo "Pas de produits actuellement sur le site ";
                        } else {
                            echo $rows.' résultats pour votre recherche<br><br>';
                        }
                    }

                    if(isset($result)){
                        while ($rowdata = mysqli_fetch_assoc($result)) {
                            $id_produit = $rowdata['id_produit'];
                            $filepath = $rowdata['image'];
                            $image_type = $rowdata['image_type'];
                            $produit = $rowdata['nom_produit'];
                            $marque = $rowdata['marque_produit'];
                            $vendeur = $rowdata['raisonsociale_client'];
                            $categorie = $rowdata['categorie_produit'];
                            $prixTTC = $rowdata['prixht_produit'] * 1.2;

                            echo '<div class="product">';
                            echo '<a href="page_produit.php?id=' . $id_produit . '"><img class="imgcontainer" src="data:' . $image_type . ';base64,' . base64_encode($filepath) . '" style="max-width: 100%; max-height: 100%;"></a><br>';
                            echo '' . $produit . " " . $marque . '<br>';
                            echo '' . $vendeur . " " . $prixTTC . '€<br><br>';
                            echo '</div>';
                        }
                    }
            
                ?>
            </div>
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
