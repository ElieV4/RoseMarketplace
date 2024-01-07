
<?php 
    include("./include/connect.php");

    if (isset($_SESSION['user_id'])) {
        //echo $_SESSION['user_id']." est connecté";
    } else {
        //echo "déconnecté";
    }
    $user = $_SESSION['user_id_id'];
    $id_produit = $_GET['id'];

    //reload clean var
    $form_produit = $form_categorie =  $form_marque = $form_prixht = $form_quantite_stock = $form_description = "";

    $Err1 = $Err2 = $Err3 = $Err4 = $Err5 = "";
    $image_produit = $images_produit_i = "";

    $select_query = "SELECT *
                     FROM produit  
                     WHERE id_produit ='$id_produit'";
    $select_result = mysqli_query($con, $select_query);
    $rowdata = mysqli_fetch_assoc($select_result);
    $form_produit = $rowdata['nom_produit'];
    $form_categorie = $rowdata['categorie_produit'];
    $form_marque = $rowdata['marque_produit'];
    $form_prixht = $rowdata['prixht_produit'];
    $form_quantite_stock = $rowdata['quantitestock_produit'];
    $form_description = $rowdata['description_produit'];

    if (isset($_POST["modifier_produit"])) {


    //load new var, insert & upload files
        $form_produit = mysqli_real_escape_string($con, $_POST["nom_produit"]);
        $form_categorie = mysqli_real_escape_string($con, $_POST["categorie"]);
        $form_marque = mysqli_real_escape_string($con, $_POST["marque"]);
        $form_prixht = mysqli_real_escape_string($con, $_POST["prixht"]);
        $form_quantite_stock = mysqli_real_escape_string($con, $_POST["quantite_stock"]);
        $form_description = mysqli_real_escape_string($con, $_POST["description"]);

        //modif query
        $modif_query_produit = "UPDATE produit SET 
            nom_produit = '$form_produit', 
            categorie_produit = '$form_categorie', 
            marque_produit = '$form_marque',
            prixht_produit = '$form_prixht',
            quantitestock_produit = '$form_quantite_stock',
            description_produit = '$form_description'
            WHERE id_produit = '$id_produit'";
        $sql_execute_produit = mysqli_query($con, $modif_query_produit);

        if (!$sql_execute_produit) {
            echo "Erreur SQLquery produit: ";
            die(mysqli_error($con));
        } else {
            //insert images produit dans photo
            if (isset($_FILES['images_produit_i']['tmp_name'][$key])) {
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

                foreach($uploaded_images_data as $image_produit){

                    $image_name = $image_produit['name'];
                    $image_data = $image_produit['file_data'];
                    $image_type = $image_produit['type'];

                    $delete_query = "DELETE FROM photo WHERE id_produit = $id_produit";
                    $sql_execute2=mysqli_query($con,$delete_query);
                    $insert_query2 = "INSERT INTO 
                        photo (id_produit, file_photo_produit, image, image_type)
                        VALUES ('$id_produit', '$image_name','$image_data', '$image_type')";
                    $sql_execute3=mysqli_query($con,$insert_query2);
                    if ($sql_execute3) {
                        echo "<script>alert('Produit modifié avec succès')</script>"; 
                        echo "<script>window.open('./espace_client_entreprise.php?produits_stocks','_self')</script>";
                    } else {
                        echo "Erreur SQLquery_insimg2 : ";
                        die(mysqli_error($con));
                    }
                } 
            }
            //si pas d'images retour au tableau
            echo "<script>alert('Produit modifié avec succès')</script>"; 
            echo "<script>window.open('./espace_client_entreprise.php?produits_stocks','_self')</script>";
        }
    }
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rose. | Modifier un produit</title>
    <link rel="stylesheet" type="text/css" href="./css/main_style.css">
    <link rel="stylesheet" type="text/css" href="./css/produit_dashboard.css">
    
</head>
<body>
    <div class="outer-container">
    <br><h2>Ajouter un produit</h2><br>   

        <form method="POST" action="" enctype="multipart/form-data">

        <!-- formulaire -->
        
        <label for="nom_produit" class="form-label">Nom du produit :</label><br>
            <input type="nom_produit" id="nom_produit" name="nom_produit" value="<?php echo htmlspecialchars($form_produit); ?>" required><br>
            <span style="color: red;" class="error" id="error-message1">*<?php echo $Err1;?></span><br>
            
            <label for="categorie" class="form-label">Catégorie:</label>
            <select id="categorie" name="categorie" required>
                <option value="chauffage_plomberie" <?php echo ($form_categorie === 'chauffage_plomberie') ? 'selected' : ''; ?>>Chauffage & plomberie</option>
                <option value="menuiserie_bois" <?php echo ($form_categorie === 'menuiserie_bois') ? 'selected' : ''; ?>>Menuiserie & bois</option>
                <option value="peinture_droguerie" <?php echo ($form_categorie === 'peinture_droguerie') ? 'selected' : ''; ?>>Peinture & droguerie</option>
                <option value="outillerie" <?php echo ($form_categorie === 'outillerie') ? 'selected' : ''; ?>>Outillerie</option>
                <option value="quincaillerie" <?php echo ($form_categorie === 'quincaillerie') ? 'selected' : ''; ?>>Quincaillerie</option>
                <option value="jardin" <?php echo ($form_categorie === 'jardin') ? 'selected' : ''; ?>>Jardin</option>
                <!-- Ajoutez d'autres catégories au besoin -->
            </select><br><br>

            <label for="marque" class="form-label">Marque :</label><br>
            <input type="text" id="marque" name="marque" minlength=1 value="<?php echo htmlspecialchars($form_marque); ?>" required><br><br>

            <label for="prixht" class="form-label">Prix HT (en €) :</label><br>
            <input type="number" id="prixht" name="prixht" min=0.01 step="0.01" value="<?php echo htmlspecialchars($form_prixht); ?>" required><br><br>

            <label for="quantite_stock" class="form-label">Quantité initiale en stock :</label><br>
            <input type="number" id="quantite_stock" min=1 name="quantite_stock" value="<?php echo htmlspecialchars($form_quantite_stock); ?>" required><br>
            <span style="color: red;" class="error" id="error-message1">*<?php echo $Err2;?></span><br>

            <label for="description" class="form-label">Description du produit (max 400 caractères) :</label><br>
            <textarea id="description" name="description" maxlength="400" rows="4" required><?php echo htmlspecialchars($form_description); ?></textarea><br><br>

            <label for="images_produit_i">Image(s) du produit :</label><br>
            <input type="file" id="images_produit_i" name="images_produit_i[]" accept=".png, .jpg, .jpeg, .gif" multiple><br><br>

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
        
        <button><input type="submit" value="Modifier" name="modifier_produit"></button>
        <button><a href="espace_client_entreprise.php?produits_stocks">Annuler</a></button>
    </form>
    </div>
</body>
</html>