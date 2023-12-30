
<?php 
    include("./include/connect.php");

    if (isset($_SESSION['user_id'])) {
        //echo $_SESSION['user_id']." est connecté";
    } else {
        //echo "déconnecté";
    }
    $user = $_SESSION['user_id_id'];
    $form_produit = $form_categorie = $form_marque = "";
    $id_produit = $_GET['id'];

    $select_query = "SELECT *
                     FROM produit  
                     WHERE id_produit ='$id_produit'";
    $select_result = mysqli_query($con, $select_query);
    $rowdata = mysqli_fetch_assoc($select_result);
    $form_produit = $rowdata['nom_produit'];
    $form_categorie = $rowdata['categorie_produit'];
    $form_marque = $rowdata['marque_produit'];

    if (isset($_POST["modifier_produit"])) {
        
        $form_produit = mysqli_real_escape_string($con, $_POST["nom_produit"]);
        $form_categorie = mysqli_real_escape_string($con,$_POST["categorie_produit"]);
        $form_marque = mysqli_real_escape_string($con,$_POST["marque_produit"]);
        
        //modif query
        $modif_query_produit = "UPDATE produit SET 
            nom_produit = '$form_produit', 
            categorie_produit = '$form_categorie', 
            marque_produit = '$form_marque' 
            WHERE id_produit = '$id_produit'";
        $sql_execute_produit = mysqli_query($con, $modif_query_produit);

        if (!$sql_execute_produit) {
            echo "Erreur SQLquery produit: ";
            die(mysqli_error($con));
        } else {
            $currentPath = $_SERVER['PHP_SELF'];
            $profilPageURL = $currentPath . "?profil";
            echo "<script>window.open('$profilPageURL','_self')</script>";
        }

        $id_produit_new = mysqli_insert_id($con);
        echo "voici". $id_produit_new; // Récupère l'ID de la dernière insertion
    }
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rose. | Modifier un produit</title>
    <link rel="stylesheet" type="text/css" href="css/main_style.css">
    <style>
    .outer-container{
            margin-left:20%;
            margin-right:20%;
            margin-bottom:10%;
            background-color: white;
            align-items: center;
            text-align: center;
        }
        .imgcontainer sideimg {
                display:flex;
                align-items: center;
                margin:5%;
                padding:10px;
                width:20%;
                height:auto;
        }
    </style>
</head>
<body>
    <div class="outer-container">
        <form method="POST" action="" enctype="multipart/form-data">
        
        <input type="text" id="produit" name="produit" value="<?php echo htmlspecialchars($form_produit); ?>"><br>
        
        <input type="number" id="categorie" name="categorie" pattern="\d{5}" value="<?php echo htmlspecialchars($form_categorie); ?>"><br>
        
        <input type="text" id="marque" name="marque" value="<?php echo htmlspecialchars($form_marque); ?>"><br>
        
        <button><input type="submit" value="Modifier" name="modifier_produit"></button>
        <button><a href="espace_client_entreprise.php?produits_stocks">Annuler</a></button>
    </form>
    </div>
</body>
</html>