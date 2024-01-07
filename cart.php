<?php 
    include("include/connect.php");
    include("include/fonctions.php");
    // Vérifie si l'utilisateur est déjà connecté
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
    <title>Rose. | Espace Client</title>
    <link rel="stylesheet" type="text/css" href="css/main_style.css">
    <link rel="stylesheet" type="text/css" href="css/cart.css">
</head>
<body> 
<?php include('entete.php')?>
    <div class="outer-container">
        <div class="content">
            <h1>Votre panier</h1><br>
            <div class="two-columns">
                <div class="col1">
                    <?php
                        echo '<a href="' . $_SERVER['HTTP_REFERER'] . '">Page précédente</a><br>';

                        $select_query = "SELECT id_produit,quantité_produit,quantitestock_produit, quantitestock_produit - quantité_produit AS stock_disponible, description_produit,MIN(id_photo_produit) AS min_photo_id, image_type, image, nom_produit, categorie_produit, marque_produit, prixht_produit, raisonsociale_client 
                        FROM panier 
                        LEFT JOIN produit USING (id_produit) 
                        LEFT JOIN photo USING (id_produit) 
                        LEFT JOIN client ON produit.id_fournisseur = client.id_client 
                        GROUP BY id_produit";
                        $result = mysqli_query($con, $select_query);
                        $numrows = mysqli_num_rows($result);
                        if($numrows==0){
                            echo "<div class=emptycart>
                                <p>Vous n'avez pas de produits dans votre panier.<p><br>
                                <a href='produits.php'><button>Continuer vos achats</button></a>
                            </div>";
                        } else {
                            if ($result) {
                                $montant_commande = 0;
                                $validerok = 0;
                                echo "<table border='0,5'>";
                                // Parcourir les résultats et afficher chaque ligne dans le tableau
                                while ($rowdata = mysqli_fetch_assoc($result)) {

                                    $id_produit = $rowdata['id_produit'];
                                    $filepath = $rowdata['image'];
                                    $image_type = $rowdata['image_type'];
                                    $produit = $rowdata['nom_produit'];
                                    $marque = $rowdata['marque_produit'];
                                    $vendeur = $rowdata['raisonsociale_client'];
                                    $prixTTC = $rowdata['prixht_produit'] * 1.2;
                                    $quantitepanier = $rowdata['quantité_produit'];
                                    $quantitestock = $rowdata['quantitestock_produit'];
                                    $stock_disponible = $rowdata['stock_disponible'];
                                    if($stock_disponible>=0){
                                        $stockok = "En stock (".$quantitestock.")"; 
                                    } else {
                                        $stockok = 'Stock insuffisant ('.$quantitestock.' exemplaires)';
                                        $validerok = $validerok +1;


                                    }
                                    $montant_produit = $quantitepanier * $prixTTC ;
                                    $montant_commande = $montant_commande + $montant_produit;

                                    echo '<tr>
                                            <td><a href="page_produit.php?id='.$id_produit.'"><img class="imgcontainer" src="data:' . $image_type . ';base64,' . base64_encode($filepath) . '" style="max-width: 100%; max-height: 100%;"></a></td>
                                            <td><a href="page_produit.php?id='.$id_produit.'">'.$produit.' '.$marque.'</a><br><i>'.$vendeur.'</i><br><br>'.$stockok.'</td>
                                            <td>     </td>
                                            <td>
                                                <div class="quantity-container">
                                                    <input class="inputquantite" type="number" min=1  max="'.$quantitestock.'" id="quantiteInput_'.$id_produit.'" value="'.$quantitepanier.'">
                                                    <button onclick="updateQuantite('.$id_produit.')">OK</button>
                                                </div>
                                            </td>
                                            <td>'.$montant_produit.'€</td>
                                            <td><button onclick="supprimerProduit(' . $id_produit . ', function() { location.reload(); })">X</button></td>
                                        </tr>';
                                    
                                }
                                echo '</table><br>';
                            } else {
                                // En cas d'erreur lors de l'exécution de la requête
                                echo 'Erreur dans la requête : ' . mysqli_error($con);
                            }
                        }
                    ?>    
                </div>
                <div class="col2">
                    <?php
                        if($numrows>0){
                            echo '<br><h3>Panier(' .$numrows.')</h3>
                            <p>Frais de livraison estimés : Gratuit</p><br>
                            <p>Total (TVA incluse) : '.$montant_commande. '€</p><br><br>';

                            if(!isset($_SESSION['user_id'])){
                                echo '<a href="user_connexion.php"><button>Valider mon panier</button></a>';
                            } else {
                                if ($validerok == 0) {
                                    echo '<a href="commande.php"><button>Valider votre panier</button></a>';
                                } else {
                                    echo '<button disabled>Stock insuffisant</button>';
                                }                            
                            }
                            echo '<br><br><a href="produits.php"><button>Continuer vos achats</button></a>';
                        }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <?php include('footer.php')?>
</body>
</html>