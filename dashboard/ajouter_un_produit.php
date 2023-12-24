<?php 
    include("../include/connect.php");
    // Vérifie si l'utilisateur est déjà connecté
    session_start();

    if (isset($_SESSION['user_id'])) {
        //echo $_SESSION['user_id']." est connecté";
    } else {
        //echo "déconnecté";
    }
    $user = $_SESSION['user_id_id'];



    //reload clean var
    $nom_produit = $categorie =  $marque = $prixht = $quantite_stock = $description = "";

    $Err1 = $Err2 = $Err3 = $Err4 = $Err5 = "";
    $image_produit = $images_produit_i = "";

    //load new var, insert & upload files
    if (isset($_POST["add_product"])) {
        $nom_produit = $_POST["nom_produit"];
        $categorie = $_POST["categorie"];
        $marque = $_POST["marque"];
        $prixht = $_POST["prixht"];
        $quantite_stock = $_POST["quantite_stock"];
        $description = $_POST["description"];

        $uploaded_images_data = array();
        foreach ($_FILES['images_produit_i']['tmp_name'] as $key => $tmp_name) {
            // recup file_data
            $image_data = file_get_contents($_FILES['images_produit_i']['tmp_name'][$key]);
            $image_data = mysqli_real_escape_string($con, $image_data);
            $image_name = $_FILES["images_produit_i"]['name'][$key];
            $image_type = $_FILES["images_produit_i"]['type'][$key];

            // Create an associative array for each image
            $image_info = array(
                'name' => $image_name,
                'type' => $image_type,
                'file_data' => $image_data
            );

            // Add the array to the uploaded_images_data array
            $uploaded_images_data[] = $image_info;
        }

        //check unique email query
        $select_query1 = "SELECT * FROM produit 
            WHERE nom_produit='$nom_produit' AND marque_produit='$marque' AND id_fournisseur=$user" ;
        $result1 = mysqli_query($con,$select_query1);
        $rows_count1= mysqli_num_rows($result1);
    
        //error check
        if ($rows_count1 > 0) {
            $Err1 = "Vous proposez déjà ce produit sur notre boutique";
        }
        if ($quantite_stock < 1) {
            $Err2 = "Le stock initial ne peut pas être nul";
        }
        //check file extension ?

        // If there are any errors, do not proceed with the insertion
        if ($Err1 || $Err2) {
            // Handle errors here if needed
        } else {
            //insert produit dans produit
            $insert_query = "INSERT INTO 
                produit (nom_produit, categorie_produit, marque_produit,prixht_produit, quantitestock_produit,description_produit,id_fournisseur) 
                VALUES ('$nom_produit','$categorie','$marque','$prixht','$quantite_stock','$description','$user')";
            $sql_execute=mysqli_query($con,$insert_query);

            if ($sql_execute) {
                echo "<script>alert('Produit ajouté avec succès')</script>";
            } else {
                echo "Erreur SQLquery1_insprod1 : ";
                die(mysqli_error($con));
            }

            //recup id_produit
            $select_query2 = "SELECT * FROM produit 
                WHERE nom_produit='$nom_produit' AND marque_produit='$marque' AND id_fournisseur=$user" ;
            $result2 = mysqli_query($con,$select_query2);
            $rowdata2 = mysqli_fetch_assoc($result2);
            $id_produit = $rowdata2['id_produit'];

            //insert images produit dans photo
            foreach($uploaded_images_data as $image_produit){

                $image_name = $image_produit['name'];
                $image_data = $image_produit['file_data'];
                $image_type = $image_produit['type'];

                $insert_query2 = "INSERT INTO 
                    photo (id_produit, file_photo_produit, image, image_type)
                    VALUES ('$id_produit', '$image_name','$image_data', '$image_type')";
                $sql_execute2=mysqli_query($con,$insert_query2);
            }
            
            if ($sql_execute2) {
                echo "<script>alert('Image(s) ajoutée(s) avec succès')</script>"; 
                if (isset($id_produit)) {
                    $select_query3 = "SELECT * FROM photo WHERE id_produit = $id_produit";
                    $result3 = mysqli_query($con, $select_query3);
                
                    while ($rowdata3 = mysqli_fetch_assoc($result3)) {
                        $filepath = $rowdata3['image'];
                        $image_type = $rowdata3['image_type'];
                        echo '<img src="data:' . $image_type . ';base64,' . base64_encode($filepath) . '" style="max-width: 10%; max-height: 10%;">';
                    }
                }
                //echo "<script>window.open('../espace_client_entreprise.php','_self')</script>";
            } else {
                echo "Erreur SQLquery_insimg2 : ";
                die(mysqli_error($con));
            }
        }
    }
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rose. | Ajouter un produit</title>
    <link rel="stylesheet" type="text/css" href="../css/main_style.css">
    <link rel="stylesheet" type="text/css" href="../css/chatbox.css">
    <style>
        .outer-container{
            margin-left:20%;
            margin-right:20%;
            margin-top:10%;
            margin-bottom:10%;
            background-color: white;
            align-items: center;
            text-align: center;
            border: 2px solid deeppink;
            background-color : #FFCFDE;
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
        <style>
        .main {
            background-color: white;
        }
    </style>
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
            <div class="logo"><a href="../index.php"><img src="../images/rose.png"></a></div>
            <ul>
                <?php
                    if(isset($_SESSION['user_id'])){
                        echo '<li ><a href="../include/logout.php"><img src="../images/logout1.png"></a></li>';
                    } else {
                        echo '<li ><a href="../user_registration.php">Inscription</a></li>';
                        echo '<li ><a href="../user_connexion.php"><img src="../images/client.png"></a></li>';
                    }
                ?>
                <li ><a href="../cart.php"><img src="../images/cart.png"></a></li>
                <div ></div>
                <button class="hamburger">
                    <div class="bar"></div>
                </button>
            </ul>
        </div>
    </nav>

    <nav class="mobile-nav">
        <a href="../index.php">Home</a>
        <?php 
        if(isset($_SESSION['user_id'])){
            echo '<a href="../espace_client_entreprise.php">Espace Client</a>';
        } else {
            echo '<a href="../user_connexion.php">Espace Client</a>';
        }
        ?>
        <a href="#">Produits</a>
        <a href="#">Catégories</a>
        <a href="../aproposde.html">A propos de ROSE.</a>
        <?php 
        if(isset($_SESSION['user_id'])){
            echo '<a href="../include/logout.php">Déconnexion</a>';
        } else {
            echo '<a href="../user_connexion.php">Connexion</a>';
        }
        ?>
    </nav>
   
    <div class="outer-container">
        <div class="content">
            <br><div>Entreprise</div><br>

            <div class="dashboard">
                <br><h2>Ajouter un produit</h2><br>   
    
                    <form method="POST" action="" enctype="multipart/form-data">
                    
                    <label for="nom_produit" class="form-label">Nom du produit :</label><br>
                    <input type="nom_produit" id="nom_produit" name="nom_produit" value="<?php echo htmlspecialchars($nom_produit); ?>" required><br>
                    <span style="color: red;" class="error" id="error-message1">*<?php echo $Err1;?></span><br>
                    
                    <label for="categorie" class="form-label">Catégorie:</label>
                    <select id="categorie" name="categorie" value="<?php echo htmlspecialchars($categorie); ?>" required>
                        <option value="chauffage_plomberie">Chauffage & plomberie</option>
                        <option value="menuiserie_bois">Menuiserie & bois</option>
                        <option value="peinture_droguerie">Peinture & droguerie</option>
                        <option value="outillerie">Outillerie</option>
                        <option value="quincaillerie">Quincaillerie</option>
                        <option value="jardin">Jardin</option>
                        <!-- Ajoutez d'autres catégories au besoin -->
                    </select><br><br>

                    <label for="marque" class="form-label">Marque :</label><br>
                    <input type="text" id="marque" name="marque" value="<?php echo htmlspecialchars($marque); ?>" required><br><br>

                    <label for="prixht" class="form-label">Prix HT (en €) :</label><br>
                    <input type="number" id="prixht" name="prixht" step="0.01" value="<?php echo htmlspecialchars($prixht); ?>" required><br><br>

                    <label for="quantite_stock" class="form-label">Quantité initiale en stock :</label><br>
                    <input type="number" id="quantite_stock" name="quantite_stock" value="<?php echo htmlspecialchars($quantite_stock); ?>" required><br>
                    <span style="color: red;" class="error" id="error-message1">*<?php echo $Err2;?></span><br>

                    <label for="description" class="form-label">Description du produit (max 400 caractères) :</label><br>
                    <textarea id="description" name="description" maxlength="400" rows="4" value="<?php echo htmlspecialchars($description); ?>"></textarea><br><br>

                    <label for="images_produit_i">Image(s) du produit :</label><br>
                    <input type="file" id="images_produit_i" name="images_produit_i[]" accept=".png, .jpg, .jpeg, .gif" multiple required><br><br>

                    <input type="submit" value="Ajouter le produit" name="add_product">
                    <br><br>
                </form>
                <br>
            </div>

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
        <div class="terms"><a target="_blank" href="../confidentialite.html" id="privacy-policy" class="qa-privacypolicy-link" rel="noopener">Politique de confidentialité</a> | <a href="../conditions-generales.html" id="terms-and-conditions" class="qa-tandc-link" target="_blank" rel="noopener">Termes et Conditions</a><p>Copyright © ROSE. 2023<br></p></div>
        <br><br>
    </footer>

    <script src="../javascript/chatbox.js"></script>
    <script src="../javascript/burgernavbar.js"></script>
    <script src="../javascript/search.js"></script>
</body>
</html>