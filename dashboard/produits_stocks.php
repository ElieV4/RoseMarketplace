<?php 


    if (isset($_SESSION['user_id'])) {
        //echo $_SESSION['user_id']." est connecté";
    } else {
        //echo "déconnecté";
    }
    $user = $_SESSION['user_id_id'];

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
            margin-top:50px;
            margin-left:10%;
            margin-right:10%;
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
        .main {
            background-color: white;
        }
    </style>
</head>
<body>  
    <div class="outer-container">
        <div class="content">
                <?php
                    $select_query = "SELECT id_produit,quantitestock_produit, date_ajout_produit, description_produit,MIN(id_photo_produit) AS min_photo_id, image_type, image, nom_produit, categorie_produit, marque_produit, prixht_produit, raisonsociale_client 
                    FROM produit 
                    LEFT JOIN photo USING (id_produit) 
                    LEFT JOIN client ON produit.id_fournisseur = client.id_client
                    WHERE id_client = '$user'
                    GROUP BY id_produit";
                    $result = mysqli_query($con, $select_query);
                    
                    if ($result) {
                        // Afficher le tableau HTML
                        echo "<table border='1'>
                            <tr>
                                <th>ID Produit</th>
                                <th></th>
                                <th>Nom du produit</th>
                                <th>Catégorie</th>
                                <th>Marque</th>
                                <th>Description</th>
                                <th>Quantité en stock</th>
                                <th>Prix HT</th>
                                <th>Prix TTC</th>
                                <th>Prix TTC après commission</th>
                            </tr>";
                            $montant_commande = 0;
                            // Parcourir les résultats et afficher chaque ligne dans le tableau
                            while ($rowdata = mysqli_fetch_assoc($result)) {

                                $id_produit = $rowdata['id_produit'];
                                $filepath = $rowdata['image'];
                                $image_type = $rowdata['image_type'];
                                $produit = $rowdata['nom_produit'];
                                $marque = $rowdata['marque_produit'];
                                $vendeur = $rowdata['raisonsociale_client'];
                                $categorie = $rowdata['categorie_produit'];
                                $prixHT = $rowdata['prixht_produit'];
                                $prixTTC = $prixHT * 1.2;
                                $prixpostcom = $prixTTC * 0.95;
                                $stock = $rowdata['quantitestock_produit'];
                                $date_ajout = $rowdata['date_ajout_produit'];
                                $description = $rowdata['description_produit'];
                                $quantitestock = $rowdata['quantitestock_produit'];


                            echo '<tr>
                                    <td>'.$id_produit.'</td>
                                    <td><img src="data:' . $image_type . ';base64,' . base64_encode($filepath) . '" style="max-width: 100%; max-height: 100px;"></td>
                                    <td>'.$produit.'</td>
                                    <td>'.$categorie.'</td>
                                    <td>'.$marque.'</td>
                                    <td>'.$description.'</td>
                                    <td>'.$quantitestock.'</td>
                                    <td>'.$prixHT.'€ </td>
                                    <td>'.$prixTTC.'€ </td>
                                    <td>'.$prixpostcom.'€ </td>
                            </tr>';
                            }
                        echo "</table><br>";
                    } else {
                        // En cas d'erreur lors de l'exécution de la requête
                        echo "Erreur dans la requête : " . mysqli_error($con);
                    }
                
                    echo '<button class="dash-button"><a href="espace_client_entreprise.php?ajouter_un_produit">Ajouter un produit</a></button><br><br>';
                ?>
        </div>
    </div> 
</body>
</html>