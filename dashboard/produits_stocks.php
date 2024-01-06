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
    <title>Rose. | Produits & Stocks</title>
    <link rel="stylesheet" type="text/css" href="./css/main_style.css">
    <link rel="stylesheet" type="text/css" href="./css/chatbox.css">
    <style>
        .outer-container{
            margin-top:50px;
            margin-left:5%;
            margin-right:5%;
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
    <script src="./javascript/produits.js"></script>
</head>
<body>
    <div class="outer-container">
        <div class="content">
            <h3>Votre boutique ROSE.</h3><br>
                <?php
                    $select_query = "SELECT id_produit,quantitestock_produit, date_ajout_produit, description_produit,MIN(id_photo_produit) AS min_photo_id, image_type, image, nom_produit, categorie_produit, marque_produit, prixht_produit, raisonsociale_client, statut_produit 
                    FROM produit 
                    LEFT JOIN photo USING (id_produit) 
                    LEFT JOIN client ON produit.id_fournisseur = client.id_client
                    WHERE id_client = '$user' AND (statut_produit = 'disponible' OR statut_produit = 'désactivé')
                    GROUP BY id_produit
                    ORDER BY date_ajout_produit DESC";
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
                                <th>Stock</th>
                                <th>Prix HT</th>
                                <th>Prix TTC</th>
                                <th>TTC après commission</th>
                                <th>Action</th>

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
                                    <td><a href="page_produit.php?id='.$id_produit.'">'.$produit.'</a></td>
                                    <td>'.$categorie.'</td>
                                    <td>'.$marque.'</td>
                                    <td>'.$description.'</td>
                                    <td>
                                        <div class="quantity-container">
                                        <input class="inputquantite" type="number" min=1  id="quantiteInput_'.$id_produit.'" value="'.$quantitestock.'" style="width: 50px;">
                                        <button onclick="updateStock('.$id_produit.')">OK</button>
                                        </div>
                                    </td>
                                    <td>'.$prixHT.'€ </td>
                                    <td>'.$prixTTC.'€ </td>
                                    <td>'.$prixpostcom.'€ </td>
                                    <td class="info">';
                             
                                    echo '<button><a href="espace_client_entreprise.php?modifproduit&id='.$id_produit.'">Modifier</a></button>';
                                      
                                    echo '<br><button onclick="retirerProduit(' . $id_produit . ', function() { location.reload(); })">Supprimer</button></td>
                            </tr>';
                            }
                        echo "</table><br>";
                    } else {
                        // En cas d'erreur lors de l'exécution de la requête
                        echo "Erreur dans la requête : " . mysqli_error($con);
                    }
                ?>
            <button class="dash-button"><a href="espace_client_entreprise.php?ajouter_un_produit">Ajouter un produit</a></button><br><br>
            <div id="produitssupprimés" style="display: none;">
            <h3>Anciens produits</h3><br>
                <?php
                    $select_query = "SELECT id_produit,quantitestock_produit, date_ajout_produit, description_produit,MIN(id_photo_produit) AS min_photo_id, image_type, image, nom_produit, categorie_produit, marque_produit, prixht_produit, raisonsociale_client, statut_produit 
                    FROM produit 
                    LEFT JOIN photo USING (id_produit) 
                    LEFT JOIN client ON produit.id_fournisseur = client.id_client
                    WHERE id_client = '$user' AND statut_produit = 'supprimé'
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
                                <th>Prix HT</th>
                                <th>Prix TTC</th>
                                <th>Prix TTC après commission</th>
                                <th>Action</th>

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
                                    <td>'.$prixHT.'€ </td>
                                    <td>'.$prixTTC.'€ </td>
                                    <td>'.$prixpostcom.'€ </td>
                                    <td class="info">';
                                    echo '<br><button type="button" onclick="ajouterProduit(' . $id_produit . ')">Ajouter</button></td>
                            </tr>';
                            }
                        echo "</table><br>";
                    } else {
                        // En cas d'erreur lors de l'exécution de la requête
                        echo "Erreur dans la requête : " . mysqli_error($con);
                    }
                ?>
            </div>
            <button id="toggleTableButton" onclick="toggleTable()">Afficher vos anciens produits en stock</button><br><br>
        </div>
    </div>
</body>
</html>