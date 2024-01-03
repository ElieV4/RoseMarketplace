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
        .content {
            text-align : justify;
        }
    </style>
</head>
<body>  
    <div class="outer-container">
        <div class="content">
        <h3>Historique de commandes</h3><br>
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
            <form action="espace_client_particulier.php?ventes" id="filters-form" method="get">
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
                <label for="tri_hist">Trier par :</label>
                <select name="tri_hist" id="tri_hist">
                    <option value="dateasc">Date + ancienne</option>
                    <option value="datedesc">Date + récente</option>
                    <option value="prixasc">Prix croissant</option>
                    <option value="prixdesc">Prix décroissant</option>
                </select>

                <button type="submit">Filtrer & trier</button>        <a href="#" onclick="resetFilters()"><i>Réinitialiser</i></a>
            </form>
            <br>
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
                $tri_hist = isset($_GET['tri_hist']) ? $_GET['tri_hist'] : 'datedesc';
                $moisfiltre = isset($_GET['mois']) ? $_GET['mois'] : null;
                $anneefiltre = isset($_GET['annee']) ? $_GET['annee'] : null;
                $select_query = "SELECT *, 
                    CONCAT(nom_produit, ' ', marque_produit, ' ',categorie_produit, ' ', description_produit),         -- si ajout d'une searchbar                
                    DATE_FORMAT(date_commande, '%Y-%m') AS mois
                    FROM commande c
                    LEFT JOIN produit pr ON c.id_produit = pr.id_produit
                    LEFT JOIN photo ph ON c.id_produit = ph.id_produit
                    LEFT JOIN client cl ON c.idclient_commande = cl.id_client
                    LEFT JOIN client fn ON c.id_fournisseur = fn.id_client
                    LEFT JOIN paiement pm ON c.id_paiement = pm.id_paiement
                    LEFT JOIN adresse a ON c.id_adresse = a.id_adresse
                    WHERE c.idclient_commande = '$user'";
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
                $select_query .= "ORDER BY";

                //rajout du tri
                switch ($tri_hist) {
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

                        $type_paiement = $rowdata['type_paiement'];
                        $titulaire =  $rowdata['titulaire'];      

                        echo '<tr>
                                <td>Commande N°'.$id_commande.'<br>effectuée le <br>'.$date_commande.'</td>
                                <td>('.$quantité_produit.') '.$nom_produit.'<br>'.$marque_produit.'<br>Vendu par : '.$fournisseur.'</td>
                                <td><button>'.$statut.'</button><br><button>Plus de détails</button></td>
                            </tr>
                            <tr class=details>
                                <td>Adresse de livraison :<br>'.$adresse.'<br>'.$codepostal. ' '.$ville.'</td>';
                        if($type_paiement == 'iban'){
                            $iban = $rowdata['iban'];
                            echo '<td>Payée par :<br>Compte Courant '.$iban.'</td>';
                        } else {
                            $banquecb = $rowdata['banquecb'];
                            $numcb = $rowdata['numcb'];
                            $expirationcb = $rowdata['expirationcb'];
                            echo '<td>Payée par :<br>Carte bancaire '.$banquecb.'<br>'.$expirationcb.'<br></td>';
                        }
                        echo '<td></td>
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