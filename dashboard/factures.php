<?php 
    include('./include/fonctions.php');
    if (isset($_SESSION['user_id'])) {
        //echo $_SESSION['user_id']." est connecté";
        $user = $_SESSION['user_id_id'];
        $type_client = $_SESSION['user_type'];
        if($type_client==1){
            $id_fournisseur = $user;
            $action = 'espace_client_entreprise.php?factures';
        } else if ($type_client=='X'){
            $action = 'espace_gestionnaire.php?factures';
        }
    } else {
        echo "Veuillez vous reconnectez pour accéder à cette page";
    }

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rose. | Factures</title>
    <link rel="stylesheet" type="text/css" href="./css/historique.css">
    <link rel="stylesheet" type="text/css" href="./css/main_style.css">
    <script src="javascript/dashboard.js"></script>
    <script>
        var idFournisseur = <?php echo json_encode($id_fournisseur); ?>;
        console.log(idFournisseur);
    </script>
</head>
<body>  
    <div class="outer-container">
        <div class="content">
        <h3>Vos factures</h3><br>
            <form action="<?php echo $action;?>" id="filters-form" method="get">
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

                <br>
                <label for="tri-fact">Trier par :</label>
                <select name="tri-fact" id="tri-fact">
                    <option value="dateasc">Date + ancienne</option>
                    <option value="datedesc">Date + récente</option>
                </select>

                <button type="submit">Filtrer & trier</button>
            </form>
            <br>
            <?php
                //declaration des variables pour la requete
                    $idf = isset($_GET['idf']) ? $_GET['idf'] : null;
                    $idf = isset($id_fournisseur) ? $id_fournisseur : null;
                    $tri = isset($_GET['tri-fact']) ? $_GET['tri-fact'] : 'datedesc';
                    $moisfiltre = isset($_GET['mois']) ? $_GET['mois'] : null;
                    $anneefiltre = isset($_GET['annee']) ? $_GET['annee'] : null;

                $select_query = "SELECT *, fr.raisonsociale_client AS fournisseur,
                    DATE_FORMAT(date_commande, '%Y-%m') AS mois
                    FROM commande c
                    LEFT JOIN client cl ON c.idclient_commande = cl.id_client
                    LEFT JOIN client fr ON c.id_fournisseur = fr.id_client";
                    if($type_client == 1){    
                    $select_query .= " WHERE c.id_fournisseur = '$user' ";
                    } else if ($type_client == 'X'){
                        $select_query .= " WHERE fr.id_gestionnaire = '$user'";
                    }
                //rajout des filtres
                    if ($moisfiltre && $moisfiltre !== 'all') {
                        $select_query .= " AND MONTH(date_commande) = '$moisfiltre'";
                    }
                    if ($anneefiltre && $anneefiltre !== 'all') {
                        $select_query .= " AND YEAR(date_commande) = '$anneefiltre'";
                    }
                    if ($idf !== null) {
                        $select_query .= " AND  c.id_fournisseur = '$idf'";
                    }

                    $select_query .= "ORDER BY";
                //rajout du tri
                    switch ($tri) {
                        case 'dateasc':
                            $select_query .= " mois ASC";
                            break;
                        case 'datedesc':
                            $select_query .= " mois DESC";
                            break;
                        default:
                            $select_query .= " mois DESC";
                            break;
                    }
                $result = mysqli_query($con, $select_query);
                $rows = mysqli_num_rows($result);
                if($rows == 0){
                    echo "<p>Aucun résultat<p><br>";
                } else { 
                    if ($result) {
                        while ($rowdata = mysqli_fetch_assoc($result)) {
                            $id_commande = $rowdata['id_commande'];
                            $fournisseur = $rowdata['fournisseur'];
                            $type_client = $rowdata['type_client'];
                            $href = 'dashboard/facture.php?idc="'.$id_commande.'"';
                            if($type_client==1){
                                $client = $rowdata['raisonsociale_client'];
                            } else {
                                $client = $rowdata['prenom_client'].' '.$rowdata['nom_client'];
                            }
                            echo 'Fournisseur : '.$fournisseur.'<br>Client : '.$client.'
                            <button><a href='.$href.' target="_blank">Voir la facture N° '.$id_commande.'</a></button><br>';
                            }                        
                        }
                        echo "</table>";
                    }
            ?>  
        </div>
    </div> 
</body>
</html>