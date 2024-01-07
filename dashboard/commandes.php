<?php 

    if (isset($_SESSION['user_id'])) {
        //echo $_SESSION['user_id']." est connecté";
    } else {
        //echo "déconnecté";
    }
    $user = $_SESSION['user_id_id'];
    include('./include/fonctions.php');
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rose. | Commandes</title>
    <link rel="stylesheet" type="text/css" href="./css/commandes_dashboard.css">
    <link rel="stylesheet" type="text/css" href="./css/main_style.css">
    <script src="javascript/dashboard.js"></script>
    
</head>
<body>  
    <div class="outer-container">
        <div class="content">
        <h3>Vos commandes en cours</h3><br>
            <form action="" id="filters-form" method="get">
                <label for="annee">Année :</label>
                <select name="annee" id="annee">
                    <option value="all">Toutes</option>
                    <?php echo generateYearOptions(isset($_GET['annee']) ? $_GET['annee'] : ''); ?>
                </select>

                <label for="mois">Mois :</label>
                <select name="mois" id="mois">
                    <option value="all">Tous</option>
                    <?php echo generateMonthOptions(isset($_GET['mois']) ? $_GET['mois'] : ''); ?>
                </select>

                <label for="categorie">Catégorie :</label>
                <select name="cat" id="cat">
                    <?php
                    $query_cat = "SELECT DISTINCT categorie_produit AS value FROM produit WHERE id_fournisseur = '$user'";
                    echo generateOptions(isset($_GET['cat']) ? $_GET['cat'] : 'all', $query_cat, $con);
                    ?>
                </select>

                <label for="br">Marque :</label>
                <select name="br" id="bran">
                    <?php
                    $query_brands = "SELECT DISTINCT marque_produit AS value FROM produit WHERE id_fournisseur = '$user'";
                    echo generateOptions(isset($_GET['br']) ? $_GET['br'] : 'all', $query_brands, $con);
                    ?>
                </select>

                <label for="id">Produit :</label>
                <select name="id" id="id">
                    <?php
                    $query_products = "SELECT DISTINCT nom_produit AS value, id_produit FROM produit WHERE id_fournisseur = '$user'";
                    echo generateOptions(isset($_GET['id']) ? $_GET['id'] : 'all', $query_products, $con);
                    ?>
                </select>
                <br>
                <label for="tri_comm">Trier par :</label>
                <select name="tri_comm" id="tri_comm">
                    <option value="dateasc">Date + ancienne</option>
                    <option value="datedesc">Date + récente</option>
                    <option value="prixasc">Prix croissant</option>
                    <option value="prixdesc">Prix décroissant</option>
                </select>

                <button type="submit">Filtrer & trier</button>        
            </form>
            <a href="#" onclick="resetFilters()"><i>Réinitialiser</i></a>
            <br>
            <?php
                //declaration des variables pour la requete
                    $categoriefiltre = 'all';
                    if (isset($_GET['cat'])) {
                        $categoriefiltre = $_GET['cat'];
                    }                    
                    $marquefiltre = 'all';
                    if (isset($_GET['br'])) {
                        $marquefiltre = $_GET['br'];
                    }                    
                    $valuefiltre = 'all';
                    if (isset($_GET['id'])) {
                        $valuefiltre = $_GET['id'];
                    }
                    $tri_comm = isset($_GET['tri_comm']) ? $_GET['tri_comm'] : 'datedesc';
                    $moisfiltre = isset($_GET['mois']) ? $_GET['mois'] : null;
                    $anneefiltre = isset($_GET['annee']) ? $_GET['annee'] : null;

                $select_query = "SELECT *,
                    DATE_FORMAT(date_commande, '%Y-%m') AS mois
                    FROM commande c
                    LEFT JOIN produit p ON c.id_produit = p.id_produit
                    LEFT JOIN client cl ON c.idclient_commande = cl.id_client
                    LEFT JOIN adresse a ON c.id_adresse = a.id_adresse
                    WHERE c.id_fournisseur = '$user' AND etat_commande <> 'validée'";

                //rajout des filtres
                    if ($moisfiltre && $moisfiltre !== 'all') {
                        $select_query .= " AND MONTH(date_commande) = '$moisfiltre'";
                    }
                    if ($anneefiltre && $anneefiltre !== 'all') {
                        $select_query .= " AND YEAR(date_commande) = '$anneefiltre'";
                    }
                    if ($categoriefiltre !== 'all') {
                        $select_query .= " AND p.categorie_produit = '$categoriefiltre'";
                    }                    
                    if ($marquefiltre !== 'all') {
                        $select_query .= " AND p.marque_produit = '$marquefiltre'";
                    }

                    if ($valuefiltre !== 'all') {
                        $select_query .= " AND nom_produit = '$valuefiltre'";
                    }
                    if (isset($_GET['reinit'])) {
                        $moisfiltre = $anneefiltre = $categoriefiltre = $marquefiltre = $valuefiltre = 'all';
                    }
                $select_query .= "ORDER BY";

                //rajout du tri
                    switch ($tri_comm) {
                        case 'dateasc':
                            $select_query .= " mois ASC";
                            break;
                        case 'datedesc':
                            $select_query .= " mois DESC";
                            break;
                        case 'prixasc':
                            $select_query .= " prixht_produit ASC";
                            break;
                        case 'prixdesc':
                            $select_query .= " prixht_produit DESC";
                            break;
                        default:
                            $select_query .= " mois DESC";
                            break;
                    }
                $result = mysqli_query($con, $select_query);

                $rows = mysqli_num_rows($result);
                if($rows == 0){
                    echo "Aucun résultat";
                } else { 
                    if ($result) {
                        // Afficher le tableau HTML
                        echo "<table border='1'>
                            <tr>
                                <th></th>
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
                            $id_produit = $rowdata['id_produit'];
                            $nom_produit = $rowdata['nom_produit'];
                            $marque_produit = $rowdata['marque_produit'];
                            $quantité_produit = $rowdata['quantité_produit'];
                            $date_commande = $rowdata['date_commande'];
                            $type_client = $rowdata['type_client'];
                            $href = 'dashboard/facture.php?idc="'.$id_commande.'"';

                            if($type_client==0){
                                $client = $rowdata['prenom_client']. ' ' . $rowdata['nom_client'];
                            } else {
                                $client = $rowdata['raisonsociale_client'];
                            }
                            $adresse = $rowdata['numetrue_adresse'];
                            $codepostal = $rowdata['codepostal_adresse'];
                            $ville = $rowdata['villeadresse_adresse'];
                            $statut = $rowdata['etat_commande'];

                            //select first photo
                            $photo_query = "SELECT MIN(id_photo_produit) AS min_photo_id, image_type, image
                                FROM photo 
                                WHERE id_produit = '$id_produit'
                                GROUP BY id_produit";
                            $photoresult = mysqli_query($con, $photo_query);
                            $photodata = mysqli_fetch_assoc($photoresult);
                            $filepath = $photodata['image'];
                            $image_type = $photodata['image_type'];
                            

                            echo '<tr>
                                    <td><a href="page_produit.php?id=' . $id_produit . '"><img class="imgcontainer" src="data:' . $image_type . ';base64,' . base64_encode($filepath) . '" style="max-width: 100px;"></a></td>
                                    <td>'.$id_commande.'<br><button><a href="'.$href.'" target="_blank">Voir la facture</a></button></td>
                                    <td>'.$client.'<br>'.$adresse.'<br>'.$codepostal.' '.$ville.'</td>
                                    <td>'.$nom_produit.'<br>'.$marque_produit.'</td>
                                    <td>'.$quantité_produit.'</td>
                                    <td>'.date('d/m/y H:i', strtotime($date_commande)).'</td>
                                    <td><button type="button" class="statut-btn" data-etat-commande="'.$statut.'" data-commande-id="'.$id_commande.'">'.$statut.'</button></td>
                                </tr>';
                        }
                        echo "</table>";
                    } else {
                        // En cas d'erreur lors de l'exécution de la requête
                        echo "Erreur dans la requête : " . mysqli_error($con);
                    }
                }
            ?>
        </div>
    </div>
</body>
</html>