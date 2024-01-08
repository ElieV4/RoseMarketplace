    <?php 
        include("include/connect.php");
        include('include/fonctions.php');
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
        <link rel="stylesheet" type="text/css" href="css/produits.css">
        <link rel="stylesheet" type="text/css" href="css/main_style.css">
        <script src="javascript/dashboard.js"></script>
    </head>

    <body>  
    <?php include('include/entete.php')?>
        <div class="outer-container">
            <div class="content">
                <div class="slogan">
                    <h1>Produits ROSE.</h1><br>
                </div>
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
                    <input type="text" name="search" id="search">
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
                        } else if (isset($_GET['search'])) {
                            $search = $_GET['search'];
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
                            echo '<div class="result">
                                Aucun résultat actuellement sur le site, veuillez reformuler votre requête.';
                        } else {
                            echo '<i>'.$rows.' résultats pour votre recherche '. $search.'</i><br><br>
                            </div>';
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
                                $id_fournisseur = $rowdata['id_fournisseur'];
                                $categorie = $rowdata['categorie_produit'];
                                $prixTTC = $rowdata['prixht_produit'] * 1.25;

                                echo '<div class="product">';
                                echo '<a href="page_produit.php?id=' . $id_produit . '"><img class="imgcontainer" src="data:' . $image_type . ';base64,' . base64_encode($filepath) . '" style="max-width: 100%; max-height: 100%;"></a><br>';
                                echo '' . $produit . " " . $marque . '<br>';
                                echo '<a href="page_fournisseur.php?id='.$id_fournisseur.'">' . $vendeur . '</a> ' . $prixTTC . '€ TTC<br><br>';
                                echo '</div>';
                            }
                        }
                        echo '</div>'
                
                    ?>
                </div>
            </div>
        </div> 

        <script>
            const chatbox_btn = document.querySelector('.questionmark');
            const mobile_chatbox = document.querySelector('.chatbox')
            chatbox_btn.addEventListener('click', function() {
            chatbox_btn.classList.toggle('is-active');
            mobile_chatbox.classList.toggle('is-active');
            });
        </script>
        <?php include('include/footer.php')?>
    </body>
    </html>
