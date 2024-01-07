<?php 
    if (isset($_SESSION['user_id'])) {
        //echo $_SESSION['user_id']." est connecté";
        $user = $_SESSION['user_id_id'];
    } else {
        echo "Veuillez vous reconnectez pour accéder à cette page";
    }
    include('./include/fonctions.php');
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rose. | Historique de commandes</title>
    <link rel="stylesheet" type="text/css" href="./css/commandes_dashboard.css">
    <link rel="stylesheet" type="text/css" href="./css/historique.css">
    <link rel="stylesheet" type="text/css" href="./css/main_style.css">
    <script src="javascript/dashboard.js"></script>
</head>
<body>  
    <div class="outer-container">
        <div class="content">
        <h3>Votre historique d'achats</h3><br>
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
                    $query_cat = "SELECT DISTINCT categorie_produit AS value FROM produit LEFT JOIN commande USING (id_produit) WHERE idclient_commande = '$user' AND etat_commande IN ('validée','refusée')";
                    echo generateOptions(isset($_GET['cat']) ? $_GET['cat'] : 'all', $query_cat, $con);
                    ?>
                </select>

                <label for="br">Marque :</label>
                <select name="br" id="bran">
                    <?php
                    $query_brands = "SELECT DISTINCT marque_produit AS value FROM produit LEFT JOIN commande USING (id_produit) WHERE idclient_commande = '$user' AND etat_commande IN ('validée','refusée')";
                    echo generateOptions(isset($_GET['br']) ? $_GET['br'] : 'all', $query_brands, $con);
                    ?>
                </select>

                <label for="id">Produit :</label>
                <select name="id" id="id">
                    <?php
                    $query_products = "SELECT DISTINCT nom_produit AS value, id_produit FROM produit LEFT JOIN commande USING (id_produit) WHERE idclient_commande = '$user' AND etat_commande IN ('validée','refusée')";
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
                    $tri_hist = isset($_GET['tri_hist']) ? $_GET['tri_hist'] : 'datedesc';
                    $moisfiltre = isset($_GET['mois']) ? $_GET['mois'] : null;
                    $anneefiltre = isset($_GET['annee']) ? $_GET['annee'] : null;

                $select_query = "SELECT *,
                    CONCAT(nom_produit, ' ', marque_produit, ' ',categorie_produit, ' ', description_produit) AS searchbar,         -- si ajout d'une searchbar                
                    DATE_FORMAT(date_commande, '%Y-%m') AS mois
                    FROM commande c
                    LEFT JOIN produit pr ON c.id_produit = pr.id_produit
                    LEFT JOIN client cl ON c.idclient_commande = cl.id_client
                    LEFT JOIN client fn ON c.id_fournisseur = fn.id_client
                    LEFT JOIN paiement pm ON c.id_paiement = pm.id_paiement
                    LEFT JOIN adresse a ON c.id_adresse = a.id_adresse
                    WHERE c.idclient_commande = '$user' ";
                //rajout des filtres
                    if ($moisfiltre && $moisfiltre !== 'all') {
                        $select_query .= " AND MONTH(date_commande) = '$moisfiltre'";
                    }
                    if ($anneefiltre && $anneefiltre !== 'all') {
                        $select_query .= " AND YEAR(date_commande) = '$anneefiltre'";
                    }
                    if ($categoriefiltre !== 'all') {
                        $select_query .= " AND pr.categorie_produit = '$categoriefiltre'";
                    }                    
                    if ($marquefiltre !== 'all') {
                        $select_query .= " AND pr.marque_produit = '$marquefiltre'";
                    }

                    if ($valuefiltre !== 'all') {
                        $select_query .= " AND nom_produit = '$valuefiltre'";
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
                $rows = mysqli_num_rows($result);
                if($rows == 0){
                    echo "<p>Aucun résultat<p><br>
                        <a href='produits.php'><button>Visiter la boutique</button></a>";
                } else { 
                    if ($result) {
                        // Parcourir les résultats et afficher chaque ligne dans le tableau
                        $evenRow = false;
                        $productDetails = array();
                        echo "<table>";
                        while ($rowdata = mysqli_fetch_assoc($result)) {
                            $id_commande = $rowdata['id_commande'];
                            $fournisseur = $rowdata['raisonsociale_client'];
                            $date_commande = $rowdata['date_commande'];
                            $montant = $rowdata['montant_total'];
                            $type_client = $rowdata['type_client'];

                            $adresse = $rowdata['numetrue_adresse'];
                            $codepostal = $rowdata['codepostal_adresse'];
                            $ville = $rowdata['villeadresse_adresse'];
                            $statut = $rowdata['etat_commande'];

                            $type_paiement = $rowdata['type_paiement'];
                            $titulaire =  $rowdata['titulaire'];
                            if($type_paiement == 'iban'){
                                $iban = $rowdata['iban'];
                            } else {
                                $banquecb = $rowdata['banquecb'];
                                $numcb = $rowdata['numcb'];
                                $expirationcb = $rowdata['expirationcb'];
                            }
                            $href = 'dashboard/facture.php?idc="'.$id_commande.'"';

                            //informations produit
                            $id_produit = $rowdata['id_produit'];
                            $nom_produit = $rowdata['nom_produit'];
                            $marque_produit = $rowdata['marque_produit'];
                            $quantité_produit = $rowdata['quantité_produit'];
                            $produit = $rowdata['nom_produit'];
                            
                            //select first photo
                            $photo_query = "SELECT MIN(id_photo_produit) AS min_photo_id, image_type, image
                                FROM photo 
                                WHERE id_produit = '$id_produit'
                                GROUP BY id_produit";
                            $photoresult = mysqli_query($con, $photo_query);
                            $photodata = mysqli_fetch_assoc($photoresult);
                            $filepath = $photodata['image'];
                            $image_type = $photodata['image_type'];
                            ?>

                            <tr>
                                <?php echo '<td><a href="page_produit.php?id=' . $id_produit . '"><img class="imgcontainer" src="data:' . $image_type . ';base64,' . base64_encode($filepath) . '" style="max-width: 100px;"></a>'?>
                                <br><button onclick="toggleDetails(<?php echo $id_commande; ?>)">Plus de détails</button></td>
                                <td>Commande N°<?php echo $id_commande; ?><br>effectuée le <?php echo date('d/m/y à H:i', strtotime($date_commande)) ?></td>
                                <td>(<?php echo $quantité_produit; ?>) <?php echo $nom_produit; ?> <?php echo $marque_produit; ?><br>Vendu.e par : <?php echo $fournisseur; ?><br><?php echo $montant; ?>€</td>
                                <td><button><?php echo $statut; ?></button></td>
                            </tr>
                            <tr id="details-<?php echo $id_commande; ?>" class="details" style="display: none;">
                                <td></td>
                                <td>Adresse de livraison :<br><?php echo $adresse; ?><br><?php echo $codepostal; ?> <?php echo $ville; ?></td>'
                                <td>Payée par :<br><?php if($type_paiement == 'iban') : ?>
                                        Compte Courant <?php echo $iban; ?>
                                    <?php endif ?>
                                    <?php if($type_paiement == 'cb') : ?>
                                        Carte bancaire <?php echo $banquecb; ?><br><?php echo $expirationcb; ?>
                                    <?php endif ?>
                                </td>
                                <td><button><a href="<?php echo $href; ?>" target="_blank">Voir la facture</a></button></td>';
                            </tr>

                            
                        <?php } //endwhile et endtable
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