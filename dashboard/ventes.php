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
    <script>
        function resetFilters() {
            // Récupérer l'URL actuelle
            var currentUrl = window.location.href;

            // Vérifier s'il y a des paramètres GET dans l'URL
            if (currentUrl.indexOf('?') !== -1) {
                // Extraire la partie de l'URL avant le premier paramètre GET
                var baseUrl = currentUrl.split('?')[0];

                // Construire la nouvelle URL avec uniquement le paramètre 'ventes'
                var newUrl = baseUrl + '?ventes';

                // Rediriger vers la nouvelle URL
                window.location.href = newUrl;
            }
        }
    </script>
</head>
<body>  
    <div class="outer-container">
        <div class="content">
            <h3>Ventes</h3><br>
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

                function generateOptions($selectedValue, $query, $con) {
                    $options = '<option value="all">Tous</option>';
                
                    $result = mysqli_query($con, $query);
                    while ($row = mysqli_fetch_array($result)) {
                        $value = $row["value"]; // Remplacez "value" par le nom de la colonne contenant les valeurs
                        $selected = ($selectedValue == $value) ? 'selected' : '';
                        $options .= "<option value='$value' $selected>$value</option>";
                    }
                
                    return $options;
                }

                
            ?>
            <form action="espace_client_entreprise.php?ventes" id="filters-form" method="get">
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
                <label for="tri">Trier par :</label>
                <select name="tri" id="tri">
                    <option value="dateasc">Date + ancienne</option>
                    <option value="datedesc">Date + récente</option>
                    <option value="nbasc">Nb commandes croissant</option>
                    <option value="nbdesc">Nb commandes décroissant</option>
                    <option value="caasc">CA croissant</option>
                    <option value="cadesc">CA décroissant</option>
                </select>

                <button type="submit">Filtrer & trier</button>        <a href="#" onclick="resetFilters()"><i>Réinitialiser</i></a>
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
                    $tri = isset($_GET['tri']) ? $_GET['tri'] : 'datedesc';
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
                        $select_query .= " AND c.id_produit = '$valuefiltre'";
                    }
                    if (isset($_GET['reinit'])) {
                        $moisfiltre = $anneefiltre = $categoriefiltre = $marquefiltre = $valuefiltre = 'all';
                    }
                    $select_query .= "GROUP BY mois, nom_produit ORDER BY";

                    //rajout du tri
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
                        if ($result) {
                            // Afficher le tableau HTML
                            echo '<table border=1>
                            <tr>
                                <th>Mois</th>
                                <th>Nombre de commandes</th>';
                            if(isset($_GET['id']) && $_GET['id']!=='all'){
                                echo '<td>Produit</td>';
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
                                if(isset($_GET['id']) && $_GET['id']!=='all'){
                                    echo '<td>'.$nom_produit. ' '.$marque_produit.'</td>';
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
                            if(isset($_GET['id']) && $_GET['id']!=='all'){
                                echo '<td></td>';
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