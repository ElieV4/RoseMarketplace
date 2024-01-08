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
    <title>Rose. | Suivre vos commandes</title>
    <link rel="stylesheet" type="text/css" href="./css/commandes_dashboard.css">
    <link rel="stylesheet" type="text/css" href="./css/historique.css">
    <link rel="stylesheet" type="text/css" href="./css/main_style.css">
    <script src="javascript/dashboard.js"></script>
</head>
<body>  
    <div class="outer-container">
        <div class="content">
        <h3>Suivre vos commandes en cours</h3>
        <br>
        <p>Vous retrouvez ici vos commandes en cours, une fois validées, vous pouvez les retrouver <a href="espace_client_particulier.php?historique_commandes">ici</a>.</p>
        <p>Pour plus de détails, veuillez sélectionner une commande ci-dessous :</p>
        <br>
            <form action="" id="filters-form" method="get">

                <label for="idc">Commande N° :</label>
                <select name="idc" id="idc">
                    <?php
                    $query_products = "SELECT DISTINCT id_commande AS value FROM commande WHERE idclient_commande = '$user' AND etat_commande <> 'validée'";
                    echo generateOptions(isset($_GET['idc']) ? $_GET['idc'] : 'all', $query_products, $con);
                    ?>
                </select>
                <button type="submit">Voir l'état de la commande</button>
            </form>
            <br>
            <?php
                //declaration des variables pour la requete

                    $valuefiltre = 'all';
                    if (isset($_GET['idc'])) {
                        $valuefiltre = $_GET['idc'];
                    }

                $select_query = "SELECT *,
                    DATE_FORMAT(date_commande, '%Y-%m') AS mois
                    FROM commande c
                    LEFT JOIN produit pr ON c.id_produit = pr.id_produit
                    LEFT JOIN client cl ON c.idclient_commande = cl.id_client
                    LEFT JOIN client fn ON c.id_fournisseur = fn.id_client
                    LEFT JOIN paiement pm ON c.id_paiement = pm.id_paiement
                    LEFT JOIN adresse a ON c.id_adresse = a.id_adresse
                    WHERE c.idclient_commande = '$user' AND etat_commande <> 'validée' ";

                    if ($valuefiltre !== 'all') {
                        $select_query .= " AND id_commande = '$valuefiltre'";
                    }

                $result = mysqli_query($con, $select_query);
                $rows = mysqli_num_rows($result);
                if($rows == 0){
                    echo "Aucun résultat";
                } else {
                    if ($result) {
                        $evenRow = false;
                        echo "<table>";
                        while ($rowdata = mysqli_fetch_assoc($result)) {
                            $id_commande = $rowdata['id_commande'];
                            $fournisseur = $rowdata['raisonsociale_client'];
                            $date_commande = $rowdata['date_commande'];
                            $date_preparation = $rowdata['date_preparation'];
                            $date_envoi = $rowdata['date_envoi'];
                            $date_livraison = $rowdata['date_livraison'];
                            $date_livree = $rowdata['date_livree'];

                            $montant = $rowdata['montant_total'];
                            $type_client = $rowdata['type_client'];

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
                            
                            $adresse = $rowdata['numetrue_adresse'];
                            $codepostal = $rowdata['codepostal_adresse'];
                            $ville = $rowdata['villeadresse_adresse'];
                            $statut = $rowdata['etat_commande'];

                            $type_paiement = $rowdata['type_paiement'];
                            $titulaire =  $rowdata['titulaire'];     

                            echo '<tr class="'.($evenRow ? 'even' : 'odd').'">
                                <td><a href="page_produit.php?id=' . $id_produit . '"><img class="imgcontainer" src="data:' . $image_type . ';base64,' . base64_encode($filepath) . '" style="max-width: 100px;"></a>
                                <td>Commande N°'.$id_commande.'<br>effectuée le '.date('d/m/y à H:i', strtotime($date_commande)).'</td>
                                <td>('.$quantité_produit.') '.$nom_produit.' '.$marque_produit.'<br>Vendu.e par : '.$fournisseur.'<br>'.$montant.'€</td>
                                <td>'.$statut.'<br>';
                                if ($statut == 'livrée') {
                                    echo '<button onclick="updateStatut('.$id_commande.', \'validée\')">Accepter</button>
                                    <button onclick="updateStatut('.$id_commande.', \'refusée\')">Refuser</button>';
                                }
                                if ($statut == 'refusée') {
                                    echo '<button><a href="espace_client_particulier.php?messagerie">Contacter le SAV</a></button>';
                                }
                            echo '</tr>';
                        }
                        
                        // cette partie ne s'affiche que quand une commande est sélectionnée
                        if(isset($_GET['idc'])) {
                        if($_GET['idc']!=='all') {
                            mysqli_data_seek($result, 0);
                            $fin = false;
                            while($rowdata = mysqli_fetch_assoc($result)){
                                if($fin==false){
                                    $id_commande = $rowdata['id_commande'];
                                    $href = 'dashboard/facture.php?idc='.$id_commande;
                                    //celle ci que quand on a demandé plus de détails 
                                    echo '<tr id="details-'.$id_commande.'" class="details" style="display: none;">
                                        <td></td>';
                                        // les infos peuvent être supprimées par l'utilisateur, le cas étant, pas de résultat
                                        if($adresse==null){
                                            echo '<td>Informations de livraison supprimées</td>';
                                        } else {
                                        echo '<td>Adresse de livraison :<br>'.$adresse.'<br>'.$codepostal. ' '.$ville.'</td>';
                                        }
                                        if($type_paiement == 'iban'){
                                            $iban = $rowdata['iban'];
                                            echo '<td>Payée par :<br>Compte Courant '.$iban.'</td>';
                                        } else if($type_paiement == 'cb'){
                                            $banquecb = $rowdata['banquecb'];
                                            $numcb = $rowdata['numcb'];
                                            $expirationcb = $rowdata['expirationcb'];
                                            echo '<td>Payée par :<br>Carte bancaire '.$banquecb.'<br>'.$expirationcb.'<br></td>';
                                        } else {
                                            echo '<td>Informations de paiement supprimées<br></td>';
                                        }
                                    echo '<td><button><a href="'.$href.'" target="_blank">Voir la facture</a></button></td>
                                        </tr>';
                                    echo '<br><button onclick="toggleDetails('.$id_commande.')">Plus de détails</button></td>';
                                    echo "</table><br><br>";

                                    $fin = true;
                                }
                            } 
                            // la frise chrono s'affiche quoiqu'il arrive en dessous des produits   
                            echo '<table><tr>';
                            if($date_preparation!==null){
                            echo '<td>Prise en charge<br>'.($date_preparation ? date("d/m/y à H:i", strtotime($date_preparation)) : "").'</td>';
                            }
                            if($date_envoi!==null){
                            echo '<td>Fin de préparation<br>'.($date_envoi ? date("d/m/y à H:i", strtotime($date_envoi)) : "").'</td>';
                            }
                            if($date_livraison!==null){
                            echo '<td>Départ de livraison<br>'.($date_livraison ? date("d/m/y à H:i", strtotime($date_livraison)) : "").'</td>';
                            }
                            if($date_livree!==null){
                            echo '<td>Délivrée le<br>'.($date_livree ? date("d/m/y à H:i", strtotime($date_livree)) : "").'</td>';
                            }
                            echo '</tr></table>'; 
                        }
                        }  
                        //ferme la table si pas pas détails & pas de get
                        echo '</tr></table>'; 
                                                           
                    }  else {
                    echo "Erreur dans la requête : " . mysqli_error($con);
                    }
                }

            ?>  
        </div>
    </div> 
</body>
</html>