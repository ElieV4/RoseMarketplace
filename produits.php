    <?php 
        include("include/connect.php");
        include('include/fonctions.php');
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
        <script src="javascript/dashboard.js"></script>

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
            .product-container {
                display: flex;
                flex-wrap: wrap;
                justify-content: space-between;
            }
            .product {
                width: 23%; 
                margin-bottom: 20px;
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
                <?php
                    function generateOptions($selectedValue, $query, $con) {
                        $options = '<option value="all">Tous</option>';
                    
                        $result = mysqli_query($con, $query);
                        while ($row = mysqli_fetch_array($result)) {
                            $value = $row["value"]; // Remplacez "value" par le nom de la colonne contenant les valeurs
                            $selected = ($selectedValue == $value) ? 'selected' : '';
                            $options .= "<option value='$value' $selected>$value</option>";
                        }
                    
                        return $options;
                    }                
                ?>
                <form action="produits.php" id="filters-form" method="get">
                    <label for="categorie">Catégorie :</label>
                    <select name="cat" id="cat">
                        <?php
                        $query_cat = "SELECT DISTINCT categorie_produit AS value FROM produit";
                        echo generateOptions(isset($_GET['cat']) ? $_GET['cat'] : 'all', $query_cat, $con);
                        ?>
                    </select>

                    <label for="br">Marque :</label>
                    <select name="br" id="bran">
                        <?php
                        $query_brands = "SELECT DISTINCT marque_produit AS value FROM produit ";
                        echo generateOptions(isset($_GET['br']) ? $_GET['br'] : 'all', $query_brands, $con);
                        ?>
                    </select>

                    <label for="tri-vt">Trier par :</label>
                    <select name="tri-vt" id="tri-vt">
                        <option value="dateasc">Date + ancienne</option>
                        <option value="datedesc">Date + récente</option>
                        <option value="prixasc">Prix croissant</option>
                        <option value="prixdesc">Prix décroissant</option>
                    </select>
                    <input type="text" id="mysearch">
                    <br>
                    <button type="submit">Filtrer & trier</button>        <a href="#" onclick="resetFilters()"><i>Réinitialiser</i></a>
                </form>
            
                <br>
                <div class="product-container">
                    <?php                                     
                        //differentes requetes écrites dans fonctions.php, pour l'instant ici mais lourd
                        $categoriefiltre = 'all';
                        if (isset($_GET['cat'])) {
                            $categoriefiltre = $_GET['cat'];
                        }                    
                        $marquefiltre = 'all';
                        if (isset($_GET['br'])) {
                            $marquefiltre = $_GET['br'];
                        }                    
                        if (isset($_GET['mysearch'])) {
                            $search = $_GET['mysearch'];
                        }
                        $tri = isset($_GET['tri-vt']) ? $_GET['tri-vt'] : 'datedesc';
                        $select_query = "SELECT *,
                            MIN(id_photo_produit) AS min_photo_id,
                            CONCAT(nom_produit, categorie_produit, marque_produit, raisonsociale_client, description_produit) AS searchfield 
                            FROM produit p
                            LEFT JOIN photo ph USING (id_produit) 
                            LEFT JOIN client c ON p.id_fournisseur = c.id_client                    
                            WHERE statut_produit = 'disponible' ";
                        
                        //rajout des filtres
                            if ($categoriefiltre !== 'all') {
                                $select_query .= " AND p.categorie_produit = '$categoriefiltre'";
                            }                    
                            if ($marquefiltre !== 'all') {
                                $select_query .= " AND p.marque_produit = '$marquefiltre'";
                            }
                            if (isset($search)) {
                                $select_query .= "AND CONCAT(nom_produit, categorie_produit, marque_produit, raisonsociale_client, description_produit) like '%$search%'";
                            }
                            if (isset($_GET['reinit'])) {
                                $categoriefiltre = $marquefiltre = $valuefiltre = 'all';
                            }

                            $select_query .= " GROUP BY id_produit ORDER BY";

                        //rajout du tri
                        switch ($tri) {
                            case 'dateasc':
                                $select_query .= " date_ajout_produit ASC";
                                break;
                            case 'datedesc':
                                $select_query .= " date_ajout_produit DESC";
                                break;
                            case 'prixasc':
                                $select_query .= " prixht_produit ASC";
                                break;
                            case 'prixdesc':
                                $select_query .= " prixht_produit DESC";
                                break;
                            default:
                                $select_query .= " date_ajout_produit DESC";
                                break;
                        }
                        $result = mysqli_query($con, $select_query);
                        $rows = mysqli_num_rows($result);
                        $search = null;
                        if($rows == 0){
                            echo "Aucun résultat actuellement sur le site, veuillez reformuler votre requête.";
                        } else {
                            echo '<i>'.$rows.' résultats pour votre recherche '. $search.'</i><br><br>';
                        }

                        echo '<div class="product-container">';
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
                                echo '<a href="produits.php?mysearch='.$vendeur.'">' . $vendeur . '</a> ' . $prixTTC . '€ TTC<br><br>';
                                echo '</div>';
                            }
                        }
                        echo '</div>'
                
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
