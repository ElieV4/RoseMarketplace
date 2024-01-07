
<?php 
    include("include/connect.php");

    if (isset($_SESSION['user_id'])) {
        //echo $_SESSION['user_id']." est connecté";
    } else {
        //echo "déconnecté";
    }
    $user = $_SESSION['user_id_id'];
    $form_adresse = $form_code_postal = $form_ville = "";

    $currentPath = substr($_SERVER['PHP_SELF'], 16);

    $ex = false;
    $livraison_query = "SELECT * FROM adresse WHERE id_client ='$user' AND type_adresse='livraison'";
    $livraison_result = mysqli_query($con, $livraison_query);
    $livraison_exist = mysqli_num_rows($livraison_result) > 0;
    
    if (isset($_POST["copier_adresse"])) {
        $factu_query = "SELECT *
        FROM adresse a
        LEFT JOIN client c USING (id_client) 
        WHERE c.id_client ='$user' AND type_adresse='facturation'";
       $factu_result = mysqli_query($con, $factu_query);
       $data = mysqli_fetch_assoc($factu_result);
       $form_adresse = $data['numetrue_adresse'];
       $form_code_postal = $data['codepostal_adresse'];
       $form_ville = $data['villeadresse_adresse'];
       $ex = true;
    }

    if (isset($_POST["ajouter_adresse"])) {
        $form_adresse = mysqli_real_escape_string($con, $_POST["adresse"]);
        $form_code_postal = mysqli_real_escape_string($con,$_POST["code_postal"]);
        $form_ville = mysqli_real_escape_string($con,$_POST["ville"]);
        $ex = true;
    }

    if($ex == true){
        //insert query
        $insert_query_adresse = "INSERT INTO adresse (numetrue_adresse, codepostal_adresse, villeadresse_adresse, type_adresse, id_client)
            VALUES ('$form_adresse', '$form_code_postal', '$form_ville', 'livraison','$user')";
        $sql_execute_adresse = mysqli_query($con, $insert_query_adresse);

        if (!$sql_execute_adresse) {
            echo "Erreur SQLquery adresse: ";
            die(mysqli_error($con));
        } else {
            echo "<script>window.open('.$currentPath','_self')</script>";
        }
    }
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rose. | Passer commande</title>
    <link rel="stylesheet" type="text/css" href="css/main_style.css">
    <script src="./javascript/dashboard.js"></script>
</head>
<body>
    <?php if (!$livraison_exist): ?>
    <form method="POST" action="" enctype="multipart/form-data">
    <button><input type="submit" value="Utiliser l'adresse de facturation" name="copier_adresse"></button><br>
    </form>
    <?php endif; ?>


    <form method="POST" action="" enctype="multipart/form-data">

    <label for="adresse" class="form-label">Adresse :</label><br>
    <input type="text" id="adresse" name="adresse" placeholder= "31, Rue du marteau" required value="<?php echo htmlspecialchars($form_adresse); ?>"><br><br>
    
    <label for="code_postal" class="form-label">Code postal :</label><br>
    <input type="number" id="code_postal" name="code_postal" placeholder= "75002" required value="<?php echo htmlspecialchars($form_code_postal); ?>"><br><br>
    
    <label for="ville" class="form-label">Ville :</label><br>
    <input type="text" id="ville" name="ville" placeholder= "Paris" required value="<?php echo htmlspecialchars($form_ville); ?>"><br><br>
    
    <button><input type="submit" value="Ajouter" name="ajouter_adresse"></button></button><button onclick="resetFilters()">Annuler</button>
    </form>
    <br>
</body>
</html>