
<?php 
    include("include/connect.php");
    include("include/fonctions.php");

    if (isset($_SESSION['user_id'])) {
        //echo $_SESSION['user_id']." est connecté";
    } else {
        //echo "déconnecté";
    }
    $user = $_SESSION['user_id_id'];
    $form_titulaire = $form_iban = $form_bic = "";

    if (isset($_POST["ajouter_iban"])) {
        $form_titulaire = mysqli_real_escape_string($con, $_POST["titulaire"]);
        $form_iban = mysqli_real_escape_string($con,$_POST["iban"]);
        $form_bic = mysqli_real_escape_string($con,$_POST["bic"]);

        //insert query
        $insert_query_paiement = "INSERT INTO paiement (titulaire, iban, bic, type_paiement, id_client)
                                    VALUES ('$form_titulaire', '$form_iban', '$form_bic', 'iban','$user')";
        $sql_execute_paiement = mysqli_query($con, $insert_query_paiement);

        if (!$sql_execute_paiement) {
            echo "Erreur SQLquery paiement: ";
            die(mysqli_error($con));
        } else {
            echo "<script>window.open('commande.php','_self')</script>";
        }

        $id_paiement_new = mysqli_insert_id($con);
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
    
    <label for="titulaire" class="form-label">Titulaire du compte :</label><br>
    <input type="text" id="titulaire" name="titulaire" required value="<?php echo htmlspecialchars($form_titulaire); ?>"><br><br>
    
    <label for="iban" class="form-label">IBAN :</label><br>
    <input type="text" id="iban" name="iban" required value="<?php echo htmlspecialchars($form_iban); ?>"><br><br>
    
    <label for="bic" class="form-label">BIC :</label><br>
    <input type="text" id="bic" name="bic" required value="<?php echo htmlspecialchars($form_bic); ?>"><br><br>
    
    <input type="submit" value="Ajouter" name="ajouter_iban">
    <button><a href="commande.php">Annuler</a></button>
    </form>
</body>
</html>