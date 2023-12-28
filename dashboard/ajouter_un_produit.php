<?php 


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
                echo "<script>window.open('../espace_client_entreprise.php','_self')</script>";
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
            margin-left:25%;
            margin-right:25%;
            margin-top:5px;
            margin-bottom:10px;
            background-color: white;
            align-items: center;
            text-align: left;
            padding:20px;
        }
        .content{
            text-align: justify;
        }
        .img-toolbox {
            width : 300px;
        }
        .two-columns {
        display: flex;
        justify-content: space-between;
        }
        .col1 {
            width: 20%;
        }
        .col2 {
            width: 60%;
            margin-left : 50px;
            text-align: left;
        }
        .dash-button {
            width : 500px;
        }
        .dashboard {
            align-items : center;
            text-align:center;
        }
    </style>
    </style>
</head>
<body>  
    <div class="outer-container">
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
                <input type="number" id="prixht" name="prixht" min=0.01 step="0.01" value="<?php echo htmlspecialchars($prixht); ?>" required><br><br>

                <label for="quantite_stock" class="form-label">Quantité initiale en stock :</label><br>
                <input type="number" id="quantite_stock" min=1 name="quantite_stock" value="<?php echo htmlspecialchars($quantite_stock); ?>" required><br>
                <span style="color: red;" class="error" id="error-message1">*<?php echo $Err2;?></span><br>

                <label for="description" class="form-label">Description du produit (max 400 caractères) :</label><br>
                <textarea id="description" name="description" maxlength="400" rows="4" value="<?php echo htmlspecialchars($description); ?>"></textarea><br><br>

                <label for="images_produit_i">Image(s) du produit :</label><br>
                <input type="file" id="images_produit_i" name="images_produit_i[]" accept=".png, .jpg, .jpeg, .gif" multiple required><br><br>

                <?php
                    if (isset($id_produit)) {
                        $select_query3 = "SELECT * FROM photo WHERE id_produit = $id_produit";
                        $result3 = mysqli_query($con, $select_query3);
                
                        while ($rowdata3 = mysqli_fetch_assoc($result3)) {
                            $filepath = $rowdata3['image'];
                            $image_type = $rowdata3['image_type'];
                            echo '<img src="data:' . $image_type . ';base64,' . base64_encode($filepath) . '" style="max-width: 10%; max-height: 10%;">';
                        }
                    }
                ?><br>

                <input type="submit" value="Ajouter le produit" name="add_product">
                <br><br>
            </form>
            <br>
        </div>
    </div>
</body>
</html>