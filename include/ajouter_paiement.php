
<?php 
    include("include/connect.php");

    if (isset($_SESSION['user_id'])) {
        //echo $_SESSION['user_id']." est connecté";
    } else {
        //echo "déconnecté";
    }
    $user = $_SESSION['user_id_id'];
    $form_banquecb = $form_titulaire = $form_numcb = $form_expirationcb = $form_cryptogrammecb = "";

    $currentPath = $_SERVER['PHP_SELF'];

    if (isset($_POST["ajouter_paiement"])) {
        $form_titulaire = mysqli_real_escape_string($con, $_POST["titulaire"]);
        $form_numcb = mysqli_real_escape_string($con,$_POST["numcb"]);
        $form_expirationcb = mysqli_real_escape_string($con,$_POST["expirationcb"]);
        $form_banquecb = mysqli_real_escape_string($con,$_POST["banquecb"]);
        $form_cryptogrammecb = mysqli_real_escape_string($con,$_POST["expirationcb"]);

        //insert query
        $insert_query_paiement = "INSERT INTO paiement (banquecb, titulaire, numcb, expirationcb, cryptogrammecb, type_paiement, id_client)
                                    VALUES ('$form_banquecb', '$form_titulaire', '$form_numcb', '$form_expirationcb', '$form_cryptogrammecb', 'cb','$user')";
        $sql_execute_paiement = mysqli_query($con, $insert_query_paiement);

        if (!$sql_execute_paiement) {
            echo "Erreur SQLquery paiement: ";
            die(mysqli_error($con));
        } else {
            echo "<script>window.open('$currentPath','_self')</script>";
        }
    }
    if (isset($_POST["annuler"])) {
        echo "<script>window.open('$currentPath','_self')</script>";
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
    
    <label for="banquecb" class="form-label">Banque :</label><br>
    <input type="text" id="banquecb" name="banquecb" required value="<?php echo htmlspecialchars($form_banquecb); ?>"><br><br>
    
    <label for="titulaire" class="form-label">Titulaire de la carte :</label><br>
    <input type="text" id="titulaire" name="titulaire" required value="<?php echo htmlspecialchars($form_titulaire); ?>"><br><br>
    
    <label for="numcb" class="form-label">Numéro de carte :</label><br>
    <input type="number" id="numcb" name="numcb" required value="<?php echo htmlspecialchars($form_numcb); ?>"><br><br>
    
    <label for="expirationcb" class="form-label">Date d'expiration :</label><br>
    <input type="month" id="expirationcb" name="expirationcb" placeholder="MM/YY" pattern="([0-9]{2}[/]?){2}" required value="<?php echo htmlspecialchars($form_expirationcb); ?>"><br><br>
    
    <label for="cryptogrammecb" class="form-label">CVV :</label><br>
    <input type="number" id="cryptogrammecb" name="cryptogrammecb" pattern="\d{3}" required value="<?php echo htmlspecialchars($form_cryptogrammecb); ?>"><br><br>
    
    <button><input type="submit" value="Ajouter" name="ajouter_paiement"></button>
    <button><input type="submit" value="Annuler" name="annuler"></button>
    </form>
    <br>
</body>
</html>