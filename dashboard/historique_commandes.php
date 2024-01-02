<?php 
    if (isset($_SESSION['user_id'])) {
        //echo $_SESSION['user_id']." est connecté";
        $user = $_SESSION['user_id_id'];
    } else {
        echo "Veuillez vous reconnectez pour accéder à cette page";
    }

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rose. | Historique de commandes</title>
    <link rel="stylesheet" type="text/css" href="./css/main_style.css">
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
        .content {
            text-align : justify;
        }
        </style>
</head>
<body>  
    <div class="outer-container">
        <div class="content">
            <?php
                $select_query = "SELECT *, CONCAT(nom_produit, ' ', marque_produit, ' ',categorie_produit, ' ', description_produit)
                                FROM commande c
                                LEFT JOIN produit pr ON c.id_produit = pr.id_produit
                                LEFT JOIN photo ph ON c.id_produit = ph.id_produit
                                LEFT JOIN client cl ON c.idclient_commande = cl.id_client
                                LEFT JOIN client fn ON c.id_fournisseur = fn.id_client
                                LEFT JOIN paiement pm ON c.id_paiement = pm.id_paiement
                                LEFT JOIN adresse a ON c.id_adresse = a.id_adresse
                                WHERE c.idclient_commande = '$user'
                                ORDER BY date_commande DESC";
                $result = mysqli_query($con, $select_query);
                
                echo "<table>";
                if ($result) {
                    // Parcourir les résultats et afficher chaque ligne dans le tableau
                    while ($rowdata = mysqli_fetch_assoc($result)) {
                        $id_commande = $rowdata['id_commande'];
                        $fournisseur = $rowdata['raisonsociale_client'];
                        $nom_produit = $rowdata['nom_produit'];
                        $marque_produit = $rowdata['marque_produit'];
                        $quantité_produit = $rowdata['quantité_produit'];
                        $date_commande = $rowdata['date_commande'];
                        $type_client = $rowdata['type_client'];
                        
                        $adresse = $rowdata['numetrue_adresse'];
                        $codepostal = $rowdata['codepostal_adresse'];
                        $ville = $rowdata['villeadresse_adresse'];
                        $statut = $rowdata['etat_commande'];

                        echo '<tr>
                                <td>Commande N°'.$id_commande.'<br>effectuée le <br>'.$date_commande.'</td>
                                <td>('.$quantité_produit.') '.$nom_produit.'<br>'.$marque_produit.'<br>Vendu par : '.$fournisseur.'</td>
                                <td><button>'.$statut.'</button></td>
                            </tr>';
                    }
                    echo "</table>";
                } else {
                    // En cas d'erreur lors de l'exécution de la requête
                    echo "Erreur dans la requête : " . mysqli_error($con);
                }
            ?>
        </div>
    </div> 
</body>
</html>