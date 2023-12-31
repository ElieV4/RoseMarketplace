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
    <title>Rose. | Profil</title>
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
</head>
<body>  
    <div class="outer-container">
        <div class="content">
            <?php
                // Code PHP pour générer les options des menus déroulants
                function generateMonthOptions($selectedMonth) {
                    $months = [
                        '01' => 'Janvier',
                        '02' => 'Février',
                        '03' => 'Mars',
                        '04' => 'Avril',
                        '05' => 'Mai',
                        '06' => 'Juin',
                        '07' => 'Juillet',
                        '08' => 'Août',
                        '09' => 'Septembre',
                        '10' => 'Octobre',
                        '11' => 'Novembre',
                        '12' => 'Décembre'
                    ];

                    $options = '';
                    foreach ($months as $monthNum => $monthName) {
                        $selected = ($selectedMonth == $monthNum) ? 'selected' : '';
                        $options .= "<option value='$monthNum' $selected>$monthName</option>";
                    }

                    return $options;
                }

                function generateYearOptions($selectedYear) {
                    // Adapter la plage d'années selon vos besoins
                    $startYear = 2023;
                    $endYear = date('Y');

                    $options = '';
                    for ($year = $startYear; $year <= $endYear; $year++) {
                        $selected = ($selectedYear == $year) ? 'selected' : '';
                        $options .= "<option value='$year' $selected>$year</option>";
                    }

                    return $options;
                }
            ?>
            <form action="espace_client_entreprise.php?ventes" id="filters-form">
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
                    <option value="all">Toutes</option>
                    <?php 
                        $select_cat = "SELECT DISTINCT categorie_produit FROM produit WHERE id_fournisseur = '$user' ";
                        $result_cat = mysqli_query($con,$select_cat);
                        while($rowcat=mysqli_fetch_array($result_cat)) {
                            $categoriefiltre = $rowcat["categorie_produit"];
                            echo '<option value="'.$categoriefiltre.'">'.$categoriefiltre.'</option>';
                        }
                    ?>
                </select>

                <label for="br">Marque :</label>
                <select name="br" id="bran">
                    <option value="all">Toutes</option>
                    <?php 
                        $select_brands = "SELECT DISTINCT marque_produit FROM produit WHERE id_fournisseur = '$user' ";
                        $result_brands = mysqli_query($con,$select_brands);
                        while($rowbrand=mysqli_fetch_array($result_brands)) {
                            $marquefiltre = $rowbrand["marque_produit"];
                            echo '<option value="'.$marquefiltre.'">'.$marquefiltre.'</option>';
                        }
                    ?>
                </select>

                <label for="id">Produit :</label>
                <select name="id" id="id">
                    <option value="all">Tous</option>
                    <?php 
                        $select_brands = "SELECT DISTINCT nom_produit, id_produit FROM produit WHERE id_fournisseur = '$user' ";
                        $result_brands = mysqli_query($con,$select_brands);
                        while($rowbrand=mysqli_fetch_array($result_brands)) {
                            $valuefiltre = $rowbrand["id_produit"];
                            $nomfiltre = $rowbrand["nom_produit"];
                            echo '<option value="'.$valuefiltre.'">'.$nomfiltre.'</option>';
                        }
                    ?>
                </select>
                <br>

                <label for="tri">Trier par :</label>
                <select name="tri" id="tri">
                    <option value="dateasc">Date + ancienne</option>
                    <option value="datedesc">Date + récente</option>
                    <option value="nbasc">Nb commandes croissant</option>
                    <option value="nbdesc">Nb commandes décroissant</option>
                    <option value="caasc">CA croissant</option>
                    <option value="cadesc">CA décroissant</option>
                </select>

                <button type="submit">Voir les ventes</button>

            </form>
        
            <br>
            <div id="result-container">
                <?php
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
                    $moisfiltre = isset($_GET['mois']) ? $_GET['mois'] : null;
                    $anneefiltre = isset($_GET['annee']) ? $_GET['annee'] : null;
                    $select_query = "SELECT
                        DATE_FORMAT(date_commande, '%Y-%m') AS mois,
                        COUNT(*) AS nombre_commandes,
                        SUM(quantité_produit) AS quantite_commandee,
                        nom_produit, categorie_produit, marque_produit,
                        SUM(montant_total) AS montant_commande
                    FROM commande c
                    LEFT JOIN produit p ON c.id_produit = p.id_produit
                    WHERE c.id_fournisseur = '$user' AND etat_commande = 'validée'";

                    //rajout des filtres
                    if ($moisfiltre && $moisfiltre !== 'all') {
                        $select_query .= " AND MONTH(date_ajout_produit) = '$moisfiltre'";
                    }
                    if ($anneefiltre && $anneefiltre !== 'all') {
                        $select_query .= " AND YEAR(date_ajout_produit) = '$anneefiltre'";
                    }
                    if ($categoriefiltre !== 'all') {
                        $select_query .= " AND c.categorie_produit = '$categoriefiltre'";
                    }                    
                    if ($marquefiltre !== 'all') {
                        $select_query .= " AND c.marque_produit = '$marquefiltre'";
                    }

                    if ($valuefiltre !== 'all') {
                        $select_query .= " AND c.id_produit = '$valuefiltre'";
                    }
                    
                    $select_query .= "GROUP BY mois, nom_produit ORDER BY";

                    //rajout du tri
                    $tri = null;
                    switch ($tri) {
                        case 'dateasc':
                            $select_query .= " mois ASC";
                            break;
                        case 'datedesc':
                            $select_query .= " mois DESC";
                            break;
                        case 'nbasc':
                            $select_query .= " nombre_commandes ASC";
                            break;
                        case 'nbdesc':
                            $select_query .= " nombre_commandes DESC";
                            break;
                        case 'caasc':
                            $select_query .= " montant_commande ASC";
                            break;
                        case 'cadesc':
                            $select_query .= " montant_commande DESC";
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
                        echo '<h3>Ventes '.$nomfiltre.'</h3><br>';
                
                        if ($result) {
                            // Afficher le tableau HTML
                            echo '<table border=1>
                            <tr>
                                <th>Mois</th>
                                <th>Nombre de commandes</th>';
                            if(isset($_GET['nom']) && $_GET['nom']!=='all'){
                                echo '<th>Quantité commandée</th>';
                            }
                            echo '<th>Montant commande</th>
                                <th>Commission ROSE. (5%)</th>
                                <th>CA fournisseur</th>
                            </tr>';

                            $total_commandes = 0;
                            $total_quantité = 0;                            
                            $total_montant = 0;
                            $total_commission = 0;
                            $total_ca = 0;

                            // Parcourir les résultats et afficher chaque ligne dans le tableau
                            while ($rowdata = mysqli_fetch_assoc($result)) {
                                $mois = $rowdata['mois'];
                                $nombre_commandes = $rowdata['nombre_commandes'];
                                $quantite_commandee = $rowdata['quantite_commandee'];
                                $nom_produit = $rowdata['nom_produit'];
                                $marque_produit = $rowdata['marque_produit'];
                                $montant_commande = $rowdata['montant_commande'];
                                $commission = $montant_commande * 0.05 ;
                                $ca_fournisseur = $montant_commande - $commission;

                                //incrément des totaux
                                $total_commandes = $total_commandes+ $nombre_commandes;
                                $total_quantité = $total_quantité + $quantite_commandee;
                                $total_montant = $total_montant + $montant_commande;
                                $total_commission = $total_commission + $commission;
                                $total_ca = $total_ca + $ca_fournisseur;

                                echo '<tr>
                                    <td>'.$mois.'</td>
                                    <td>'.$nombre_commandes.'</td>';
                                if(isset($_GET['nom']) && $_GET['nom']!=='all'){
                                    echo '<td>'.$quantite_commandee.'</td>';
                                }
                                echo '<td>'.$montant_commande.'€</td>
                                    <td>'.$commission.'€</td>
                                    <td>'.$ca_fournisseur.'€</td></tr>';
                            }
                            //ligne totaux
                            echo '<tr>
                                <td>Total</td>
                                <td>'.$total_commandes.'</td>';
                            if(isset($_GET['nom']) && $_GET['nom']!=='all'){
                                echo '<td>'.$total_quantité.'</td>';
                            }
                            echo '<td>'.$total_montant.'€</td>
                                <td>'.$total_commission.'€</td>
                                <td>'.$total_ca.'€</td>
                            </tr></table>';
                        } else {
                            // En cas d'erreur lors de l'exécution de la requête
                            echo "Erreur dans la requête : " . mysqli_error($con);
                        }
                    }
                ?>
            </div>
        </div>
    </div> 
</body>
</html>