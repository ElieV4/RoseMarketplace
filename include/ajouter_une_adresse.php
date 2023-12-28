
<?php 
    include("include/connect.php");

    if (isset($_SESSION['user_id'])) {
        //echo $_SESSION['user_id']." est connecté";
    } else {
        //echo "déconnecté";
    }
    $user = $_SESSION['user_id_id'];
    $form_adresse = $form_code_postal = $form_ville = "";

    if (isset($_POST["ajouter_adresse"])) {
        if (isset($_POST["copie_facturation"])) {
        
        }
        $form_adresse = mysqli_real_escape_string($con, $_POST["adresse"]);
        $form_code_postal = mysqli_real_escape_string($con,$_POST["code_postal"]);
        $form_ville = mysqli_real_escape_string($con,$_POST["ville"]);

        //insert query
        $insert_query_adresse = "INSERT INTO adresse (numetrue_adresse, codepostal_adresse, villeadresse_adresse, type_adresse, id_client)
                                    VALUES ('$form_adresse', '$form_code_postal', '$form_ville', 'livraison','$user')";
        $sql_execute_adresse = mysqli_query($con, $insert_query_adresse);

        if (!$sql_execute_adresse) {
            echo "Erreur SQLquery adresse: ";
            die(mysqli_error($con));
        } else {
            echo "<script>window.open('commande.php','_self')</script>";
        }

        $id_adresse_new = mysqli_insert_id($con);
        echo "voici". $id_adresse_new; // Récupère l'ID de la dernière insertion
    }
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rose. | Passer commande</title>
    <link rel="stylesheet" type="text/css" href="css/main_style.css">
</head>
<body>
    <form method="POST" action="" enctype="multipart/form-data">

    <label for="adresse" class="form-label">Adresse :</label><br>
    <input type="text" id="adresse" name="adresse" placeholder= "31, Rue du marteau" value="<?php echo htmlspecialchars($form_adresse); ?>"><br><br>
    
    <label for="code_postal" class="form-label">Code postal :</label><br>
    <input type="number" id="code_postal" name="code_postal" placeholder= "75002" value="<?php echo htmlspecialchars($form_code_postal); ?>"><br><br>
    
    <label for="ville" class="form-label">Ville :</label><br>
    <input type="text" id="ville" name="ville" placeholder= "Paris" value="<?php echo htmlspecialchars($form_ville); ?>"><br><br>
    
    <input type="submit" value="Ajouter" name="ajouter_adresse">
    <button><a href="commande.php">Annuler</a></button>
    </form>
    <br>
</body>
</html>