<?php 
    include("include/connect.php");
    include("include/fonctions.php");
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
    <link rel="stylesheet" type="text/css" href="css/page_produit.css">
    <link rel="stylesheet" type="text/css" href="css/main_style.css">
</head>
<body>
    <?php include('include/entete.php')?>   
    <div class="outer-container">
        <?php if(isset($_GET['id'])) : ?>
        <div class="content">
            <div class="goback"><?php echo '<a href="' . $_SERVER['HTTP_REFERER'] . '">Page précédente</a><br>';?></div>
            <div class="product">
                <?php
                if(isset($_GET['id'])){
                        $id_produit = $_GET['id'];
                        $select_query = "SELECT *
                            FROM produit 
                            LEFT JOIN photo USING (id_produit) 
                            LEFT JOIN client ON produit.id_fournisseur = client.id_client 
                            WHERE id_produit = '$id_produit';";
                        $result = mysqli_query($con, $select_query);

                        while($rowdata = mysqli_fetch_assoc($result)){
                            $image = $rowdata['image'];
                            $image_type = $rowdata['image_type'];
                            $images[] = array(
                                'filepath' => $image,
                                'image_type' => $image_type
                            );
                            $produit = $rowdata['nom_produit'];
                            $marque = $rowdata['marque_produit'];
                            $vendeur = $rowdata['raisonsociale_client'];
                            $categorie = $rowdata['categorie_produit'];
                            $prixTTC = $rowdata['prixht_produit'] * 1.25;
                            $stock = $rowdata['quantitestock_produit'];
                            $date_ajout = $rowdata['date_ajout_produit'];
                            $description = $rowdata['description_produit'];
                            $id_fournisseur = $rowdata['id_fournisseur'];
                        }
                        
                    echo '<h2>' . $produit . " " . $marque . '</h2>';
                    echo '<div class="image-container">';
                    foreach ($images as $imageData) {
                        echo '<img class="imgcontainer" src="data:' . $imageData['image_type'] . ';base64,' . base64_encode($imageData['filepath']) . '" style="max-width: 300px; max-height: 10%; margin-right: 10px;">';
                    }
                    echo '</div>';
                    echo 'Vendu.e par <a href="page_fournisseur.php?id='.$id_fournisseur.'">'.$vendeur.'</a> '.$prixTTC.'€ TTC<br><br>';
                    echo ''.$description.'<br><br>';
                }else{
                    echo 'Erreur DB, veuillez revenir à la page précédente';
                }
                echo '<a href="page_produit.php?id='.$id_produit.'&cart='.$id_produit.'"><button>Ajouter au panier</button></a><br>';

                //ajouter au panier
                if(isset($_GET['cart'])){
                    $id_produit=$_GET['id'];

                    //requete verif produit pas déjà ajouté au panier
                    $select_query2 = "SELECT * FROM panier WHERE id_produit = '$id_produit'";
                    $result2 = mysqli_query($con, $select_query2);
                    $rowdata2 = mysqli_fetch_assoc($result2);
                    if($rowdata2==0){

                        if(isset($_GET['quantite_produit'])){
                            $quantité_produit = $_GET['quantite_produit'];
                        } else {
                            $quantité_produit = 1;
                        }
                        $adresse_ip = get_user_ip();
                        //ajout du produit au panier
                        $insertcart_query = "INSERT INTO panier (id_produit, quantité_produit, adresse_ip)
                        VALUES ('$id_produit', $quantité_produit, '$adresse_ip');";

                        if (mysqli_query($con, $insertcart_query)) {
                            echo '<div class="popup">Produit ajouté au panier<br>';
                            echo '<a href="cart.php"><button>Accéder au panier</button></a>';
                            echo ' <button>Annuler</button></div>';                            
                        } else {
                            echo "Error: " . $insertcart_query . "<br>" . mysqli_error($con);
                        }
                    } else {
                        echo '<div class="popup">Produit déjà présent dans votre panier<br>';
                        echo '<a href="cart.php"><button>Accéder au panier</button></a></div>';
                    }
                }
                ?>
            </div>
            <br>
            <div class="other-products">
                <?php
                    if(isset($_GET['id'])){
                        $id_produit = $_GET['id'];
                        $select_query = "SELECT *
                            FROM produit 
                            LEFT JOIN photo USING (id_produit) 
                            LEFT JOIN client ON produit.id_fournisseur = client.id_client 
                            WHERE categorie_produit='$categorie' AND id_produit <> '$id_produit'
                            GROUP BY id_produit
                            LIMIT 5;";
                        $result = mysqli_query($con, $select_query);
                        $rows = mysqli_num_rows($result);

                        echo '<table><tr>';
                        if($rows == 0){
                            $select_query2 = "SELECT *
                                FROM produit 
                                LEFT JOIN photo USING (id_produit) 
                                LEFT JOIN client ON produit.id_fournisseur = client.id_client 
                                WHERE categorie_produit<>'$categorie' AND id_produit <> '$id_produit'
                                GROUP BY id_produit
                                LIMIT 5;";
                            $result2 = mysqli_query($con, $select_query2);
                            $rows2 = mysqli_num_rows($result2);

                            while ($rowdata = mysqli_fetch_assoc($result2)) {
                                $id_produit2 = $rowdata['id_produit'];
                                $filepath = $rowdata['image'];
                                $image_type = $rowdata['image_type'];
                                $produit = $rowdata['nom_produit'];
                                $marque = $rowdata['marque_produit'];
                                $vendeur = $rowdata['raisonsociale_client'];
                                $prixTTC = $rowdata['prixht_produit'] * 1.2;
                                $id_fournisseur = $rowdata['id_fournisseur'];

                                echo '<td>
                                <a href="page_produit.php?id=' . $id_produit2 . '"><img class="imgcontainer" src="data:' . $image_type . ';base64,' . base64_encode($filepath) . '" style="max-width: 150px; max-height: 10%;"></a><br>';
                                echo ''.$produit."<br>".$marque.'<br>';
                                echo '<a href="page_fournisseur.php?id='.$id_fournisseur.'">'.$vendeur.'</a> '.$prixTTC.'€ TTC<br><br>
                                </td>';
                            }
                        } else {
                            while ($rowdata = mysqli_fetch_assoc($result)) {
                                $id_produit2 = $rowdata['id_produit'];
                                $filepath = $rowdata['image'];
                                $image_type = $rowdata['image_type'];
                                $produit = $rowdata['nom_produit'];
                                $marque = $rowdata['marque_produit'];
                                $vendeur = $rowdata['raisonsociale_client'];
                                $prixTTC = $rowdata['prixht_produit'] * 1.25;
                                $id_fournisseur = $rowdata['id_fournisseur'];

                                echo '<td>
                                <a href="page_produit.php?id=' . $id_produit2 . '"><img class="imgcontainer" src="data:' . $image_type . ';base64,' . base64_encode($filepath) . '" style="max-width: 150px; max-height: 10%;"></a><br>';
                                echo ''.$produit."<br>".$marque.'<br>';
                                echo '<a href="page_fournisseur.php?id='.$id_fournisseur.'">'.$vendeur.'</a> '.$prixTTC.'€<br><br>
                                </td>';
                            }
                        }
                        echo '</tr></table>';
                    }else{
                        echo 'Erreur DB, veuillez revenir à la page précédente';
                    }
                ?>
            </div>
        </div>
        <?php endif;?>
        <?php if(!isset($_GET['id'])) : ?>
            <div class="statut-error">
            <h2>Error 404</h2>
            <p>Veuillez nous en excusez, cette page est introuvable.</p>
            <p><button><a href="index.php">Retourner en lieu sûr</a></button></p>
        </div>
        <?php endif;?>
    </div> 

    <?php include('include/footer.php')?>
</body>
</html>
