
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
    $user = $_SESSION['user_id_id'];
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
            border : solid grey 1px;
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
            <h1>Votre commande</h1><br>
            <div class="two-columns">
                <div class="col1">
                    <?php
                        //adresse de facturation
                        $facturation_query = "SELECT *, count(DISTINCT id_adresse) AS nbadresses
                         FROM client c 
                         LEFT JOIN adresse a USING (id_adresse) 
                         WHERE c.id_client ='$user' AND type_adresse='facturation'";
                        $facturation_result = mysqli_query($con, $facturation_query);
                        
                        echo "<table border='0,5'>";
                        if ($facturation_result) {
                            while ($rowdata1 = mysqli_fetch_assoc($facturation_result)) {

                                $id_client = $user;
                                $typeclient = $rowdata1['type_client'];                               
                                if($typeclient ==1){
                                    $client = $rowdata1['raisonsociale_client'];
                                } else {
                                    $client = $rowdata1['prenom_client']. ' ' .$rowdata1['nom_client'];
                                }

                                $id_adresse = $rowdata1['id_adresse'];
                                $rue = $rowdata1['numetrue_adresse'];
                                $codepostal = $rowdata1['codepostal_adresse'];
                                $ville = $rowdata1['villeadresse_adresse'];
                                $typeadresse = $rowdata1['type_adresse'];
                            
                                echo '<tr>
                                        <td>Adresse de '.$typeadresse.'</td>
                                        <td>'.$client.'<br>'.$rue.'<br>'.$ville.', '.$codepostal.'</td>
                                        <td>     </td>
                                        <td><button onclick="modifierAdresse(' . $id_adresse . ', function() { location.reload(); })">Modifier</button></td>
                                        <td><button onclick="supprimerAdresse(' . $id_adresse . ', function() { location.reload(); })">Supprimer</button></td>
                                    </tr>';
                            }
                        
                            echo '</table><br>';
                        } else {
                            echo 'Erreur dans la requête : ' . mysqli_error($con);
                        }

                        //adresses de livraison 
                        $livraison_query = "SELECT *, count(DISTINCT id_adresse) AS nbadresses
                         FROM client c 
                         LEFT JOIN adresse a USING (id_adresse) 
                         WHERE c.id_client ='$user' AND type_adresse='livraison'";
                        $livraison_result = mysqli_query($con, $livraison_query);
                        $rowdata2 = mysqli_fetch_assoc($livraison_result);
                        $nbadresses = $rowdata2['nbadresses'];
                        echo "<table border='0,5'>";
                        if ($livraison_result) {
                            if($nbadresses==0){
                                echo '<button onclick="ajouterAdresse(' . $id_adresse . ', function() { location.reload(); })">Ajouter une adresse de livraison</button><br>';
                                echo '<button onclick="copierFacturation(' . $id_adresse . ', function() { location.reload(); })">Utiliser votre adresse de facturation comme adresse de livraison</button><br>';
                            } else {
                            while ($rowdata2) {

                                $id_client = $user;
                                $typeclient = $rowdata2['type_client'];                               
                                if($typeclient ==1){
                                    $client = $rowdata2['raisonsociale_client'];
                                } else {
                                    $client = $rowdata2['prenom_client']. ' ' .$rowdata2['nom_client'];
                                }

                                $id_adresse = $rowdata2['id_adresse'];
                                $rue = $rowdata2['numetrue_adresse'];
                                $codepostal = $rowdata2['codepostal_adresse'];
                                $ville = $rowdata2['villeadresse_adresse'];
                                $typeadresse = $rowdata2['type_adresse'];
                            
                                echo '<tr>
                                        <td>Adresse de '.$typeadresse.'</td>
                                        <td>'.$client.'<br>'.$rue.'<br>'.$ville.', '.$codepostal.'</td>
                                        <td>     </td>
                                        <td><button onclick="modifierAdresse(' . $id_adresse . ', function() { location.reload(); })">Modifier</button></td>
                                        <td><button onclick="supprimerAdresse(' . $id_adresse . ', function() { location.reload(); })">Supprimer</button></td>
                                    </tr>';
                            }
                            }
                            echo '</table><br>';
                        } else {
                            echo 'Erreur dans la requête : ' . mysqli_error($con);
                        }

                        //moyens de paiements
                        $paiement_query = "SELECT *, count( DISTINCT id_paiement) AS nbpaiements
                         FROM client c 
                         LEFT JOIN paiement p USING (id_client)
                         WHERE c.id_client ='$user'";
                        $paiement_result = mysqli_query($con, $paiement_query);
                        $numrows3 = mysqli_num_rows($paiement_result);
                        
                        echo "<table border='0,5'>";
                        if ($paiement_result) {
                            while ($rowdata3 = mysqli_fetch_assoc($paiement_result)) {

                                $id_client = $user;
                                $typeclient = $rowdata3['type_client'];                               
                                if($typeclient ==1){
                                    $client = $rowdata3['raisonsociale_client'];
                                } else {
                                    $client = $rowdata3['prenom_client']. ' ' .$rowdata3['nom_client'];
                                }

                                $id_paiement = $rowdata3['id_paiement'];
                                $type_paiement = $rowdata3['type_paiement'];
                                $titulaire = $rowdata3['nomcb'];
                                $iban = $rowdata3['iban'];
                                $bic = $rowdata3['bic'];
                                $banque = $rowdata3['banquecb'];
                                $expiration = $rowdata3['expirationcb'];
                                $cryptocb = $rowdata3['cryptogrammecb'];
                            
                                echo '<tr>
                                        <td>Moyen de paiement</td>
                                        <td>'.$client.'<br>'.$rue.'<br>'.$ville.', '.$codepostal.'</td>
                                        <td>     </td>
                                        <td><button onclick="modifierAdresse(' . $id_adresse . ', function() { location.reload(); })">Modifier</button></td>
                                        <td><button onclick="supprimerAdresse(' . $id_adresse . ', function() { location.reload(); })">Supprimer</button></td>
                                    </tr>';
                            }
                        
                            echo '</table><br>';
                        } else {
                            echo 'Erreur dans la requête : ' . mysqli_error($con);
                        }
                    ?>    
                </div>
                <div class="col2">
                    <?php
                        $select_query = "SELECT SUM(quantité_produit * prixht_produit * 1.2) OVER() AS montant, 
                            id_produit,quantité_produit,quantitestock_produit, prixht_produit, date_ajout_produit, MIN(id_photo_produit) AS min_photo_id, image_type, image 
                        FROM panier 
                        LEFT JOIN produit USING (id_produit) 
                        LEFT JOIN photo USING (id_produit) 
                        GROUP BY id_produit";
                        $result = mysqli_query($con, $select_query);
                        if ($result) {
                            // Parcourir les résultats et afficher chaque ligne dans le tableau
                            while ($rowdata = mysqli_fetch_assoc($result)) {
                                $id_produit = $rowdata['id_produit'];
                                $filepath = $rowdata['image'];
                                $image_type = $rowdata['image_type'];
                                $montant = $rowdata['montant'];
                            }
                            echo '<br><h3>Récapitulatif de la commande</h3><br>
                            <p>Total (TVA incluse) : '.$montant. '€</p>
                            <p>Frais de livraison estimés : Gratuit</p><br>
                            <p>Montant total : '.$montant. '€</p><br><br>
                            <a href="commande.php"><button>Passer commande</button></a>';
                        }
                    ?>
                    <br><br>
                    <a href="produits.php"><button>Continuer vos achats</button></a>
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
    <script src="javascript/adresse.js"></script>
</body>
</html>