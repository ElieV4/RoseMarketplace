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
    <title>Rose. | Commandes</title>
    <link rel="stylesheet" type="text/css" href="./css/main_style.css">
    <script src="javascript/dashboard.js"></script>
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
                $select_query = "SELECT *
                                FROM commande c
                                LEFT JOIN produit p ON c.id_produit = p.id_produit
                                LEFT JOIN client cl ON c.idclient_commande = cl.id_client
                                LEFT JOIN adresse a ON c.id_adresse = a.id_adresse
                                WHERE c.id_fournisseur = '$user'
                                ORDER BY date_commande DESC";
                $result = mysqli_query($con, $select_query);
                
                if ($result) {
                    // Afficher le tableau HTML
                    echo "<table border='1'>
                        <tr>
                            <th>ID Commande</th>
                            <th>Informations client</th>
                            <th>Nom du produit</th>
                            <th>Quantité commandée</th>
                            <th>Date commande</th>
                            <th>Statut commande</th>
                        </tr>";
                    $montant_commande = 0;
                    // Parcourir les résultats et afficher chaque ligne dans le tableau
                    while ($rowdata = mysqli_fetch_assoc($result)) {
                        $id_commande = $rowdata['id_commande'];
                        $nom_produit = $rowdata['nom_produit'];
                        $marque_produit = $rowdata['marque_produit'];
                        $quantité_produit = $rowdata['quantité_produit'];
                        $date_commande = $rowdata['date_commande'];
                        $type_client = $rowdata['type_client'];
                        if($type_client==0){
                            $client = $rowdata['prenom_client']. ' ' . $rowdata['nom_client'];
                        } else {
                            $client = $rowdata['raisonsociale_client'];
                        }
                        $adresse = $rowdata['numetrue_adresse'];
                        $codepostal = $rowdata['codepostal_adresse'];
                        $ville = $rowdata['villeadresse_adresse'];
                        $statut = $rowdata['etat_commande'];

                        echo '<tr>
                                <td>'.$id_commande.'</td>
                                <td>'.$client.'<br>'.$adresse.'<br>'.$codepostal.' '.$ville.'</td>
                                <td>'.$nom_produit.'<br>'.$marque_produit.'</td>
                                <td>'.$quantité_produit.'</td>
                                <td>'.$date_commande.'</td>
                                <td><button class="statut-btn" data-etat-commande="'.$statut.'" data-commande-id="'.$id_commande.'">'.$statut.'</button></td>
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