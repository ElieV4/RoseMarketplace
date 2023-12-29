
<?php 
    include("include/connect.php");

    if (isset($_SESSION['user_id'])) {
        //echo $_SESSION['user_id']." est connecté";
    } else {
        //echo "déconnecté";
    }
    $user = $_SESSION['user_id_id'];
    $form_adresse = $form_code_postal = $form_ville = "";
    $id_adresse = $_GET['id'];

    $select_query = "SELECT *
                     FROM adresse  
                     WHERE id_adresse ='$id_adresse'";
    $select_result = mysqli_query($con, $select_query);
    $rowdata = mysqli_fetch_assoc($select_result);
    $form_adresse = $rowdata['numetrue_adresse'];
    $form_ville = $rowdata['villeadresse_adresse'];
    $form_codepostal = $rowdata['codepostal_adresse'];

    if (isset($_POST["modifier_adresse"])) {
        
        $form_adresse = mysqli_real_escape_string($con, $_POST["adresse"]);
        $form_code_postal = mysqli_real_escape_string($con,$_POST["code_postal"]);
        $form_ville = mysqli_real_escape_string($con,$_POST["ville"]);
        
        //modif query
        $modif_query_adresse = "UPDATE adresse SET 
            numetrue_adresse = '$form_adresse', 
            codepostal_adresse = '$form_code_postal', 
            villeadresse_adresse = '$form_ville' 
            WHERE id_adresse = '$id_adresse'";
        $sql_execute_adresse = mysqli_query($con, $modif_query_adresse);

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
    
    <input type="text" id="adresse" name="adresse" value="<?php echo htmlspecialchars($form_adresse); ?>"><br>
    
    <input type="number" id="code_postal" name="code_postal" pattern="\d{5}" value="<?php echo htmlspecialchars($form_codepostal); ?>"><br>
    
    <input type="text" id="ville" name="ville" value="<?php echo htmlspecialchars($form_ville); ?>"><br>
    
    <button><input type="submit" value="Modifier" name="modifier_adresse"></button>
    <button><a href="commande.php">Annuler</a></button>
    </form>
</body>
</html>