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
            margin-left:20%;
            margin-right:20%;
            margin-bottom:10%;
            background-color: white;
            align-items: center;
            text-align: center;
            border: 2px solid deeppink;
            background-color : #FFCFDE;
        }
        .content{
            text-align: justify;
        }
        .imgcontainer sideimg {
                display:flex;
                align-items: center;
                margin:5%;
                padding:10px;
                width:20%;
                height:auto;
        }
        <style>
        .main {
            background-color: white;
        }
    </style>
</head>
<body>  
    <div class="outer-container">
        <div class="content">
            <?php
                $select_query = "SELECT c.id_commande, c.quantité_produit, c.date_commande, c.id_fournisseur,
                                p.nom_produit, p.marque_produit, p.prixht_produit, cl.raisonsociale_client, cl.nom_client, cl.prenom_client, a.numetrue_adresse, a.villeadresse_adresse, a.codepostal_adresse                          
                                FROM commande c
                                LEFT JOIN produit p ON c.id_produit = p.id_produit
                                LEFT JOIN client cl ON c.idclient_commande = cl.id_client
                                LEFT JOIN adresse a ON c.idclient_commande = a.id_client
                                WHERE c.id_fournisseur = '$user'";
                $result = mysqli_query($con, $select_query);
                
                if ($result) {
                    // Afficher le tableau HTML
                    echo "<table border='1'>
                        <tr>
                            <th>ID fournisseur</th>

                            <th>ID Commande</th>
                            <th>Nom du produit</th>
                            <th>Marque</th>
                            <th>Quantité commandée</th>
                            <th>Date commande</th>
                        </tr>";
                    $montant_commande = 0;
                    // Parcourir les résultats et afficher chaque ligne dans le tableau
                    while ($rowdata = mysqli_fetch_assoc($result)) {
                        $id_commande = $rowdata['id_commande'];
                        $id_fournisseur = $rowdata['id_fournisseur'];
                        $nom_produit = $rowdata['nom_produit'];
                        $marque_produit = $rowdata['marque_produit'];
                        $quantité_produit = $rowdata['quantité_produit'];
                        $date_commande = $rowdata['date_commande'];

                        echo '<tr>
                                <td>'.$id_fournisseur.'</td>

                                <td>'.$id_commande.'</td>
                                <td>'.$nom_produit.'</td>
                                <td>'.$marque_produit.'</td>
                                <td>'.$quantité_produit.'</td>
                                <td>'.$date_commande.'</td>
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