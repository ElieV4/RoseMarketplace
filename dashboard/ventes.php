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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <script>
        $(document).ready(function() {
            // Fonction pour mettre à jour les résultats en fonction des filtres
            function updateResults() {
                // Récupérer les valeurs des filtres
                var annee = $('#annee').val();
                var mois = $('#mois').val();
                var categorie = $('#categorie').val();
                var marque = $('#marque').val();
                var nom = $('#nom').val();

                // Effectuer une requête AJAX pour obtenir les résultats mis à jour
                $.ajax({
                    type: 'GET',
                    url: 'votre_script_php.php',
                    data: {
                        annee: annee,
                        mois: mois,
                        categorie: categorie,
                        marque: marque,
                        nom: nom
                    },
                    success: function(response) {
                        // Mettre à jour le contenu de la div des résultats
                        $('#result-container').html(response);
                    },
                    error: function(error) {
                        console.error('Erreur lors de la requête AJAX:', error);
                    }
                });
            }

            // Gérer les changements d'entrée pour déclencher la mise à jour des résultats
            $('#filters-form').change(function() {
                updateResults();
            });

            // Initialiser les résultats au chargement de la page
            updateResults();
        });
    </script>

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
            <form id="filters-form">
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
                <select name="categorie" id="categorie">
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

                <label for="marque">Marque :</label>
                <select name="marque" id="marque">
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

                <label for="marque">Produit :</label>
                <select name="nom" id="nom">
                    <option value="all">Tous</option>
                    <?php 
                        $select_brands = "SELECT DISTINCT nom_produit FROM produit WHERE id_fournisseur = '$user' ";
                        $result_brands = mysqli_query($con,$select_brands);
                        while($rowbrand=mysqli_fetch_array($result_brands)) {
                            $nomfiltre = $rowbrand["nom_produit"];
                            echo '<option value="'.$nomfiltre.'">'.$nomfiltre.'</option>';
                        }
                    ?>
                </select>
            </form>
        
            <br>
            <div id="result-container">
                <?php
                    $moisfiltre = isset($_GET['mois']) ? $_GET['mois'] : null;
                    $anneefiltre = isset($_GET['annee']) ? $_GET['annee'] : null;
                    $select_query = "SELECT *
                    FROM commande c
                    LEFT JOIN produit p ON c.id_produit = p.id_produit
                    WHERE c.id_fournisseur = '$user' AND etat_commande = 'validée'";

                    if ($moisfiltre && $moisfiltre !== 'all') {
                        $select_query .= " AND MONTH(date_ajout_produit) = '$moisfiltre'";
                    }

                    if ($anneefiltre && $anneefiltre !== 'all') {
                        $select_query .= " AND YEAR(date_ajout_produit) = '$anneefiltre'";
                    }
                
                    $select_query .= " ORDER BY date_commande DESC";
                    $result = mysqli_query($con, $select_query);
                    $rows = mysqli_num_rows($result);
                    if($rows == 0){
                        echo "Aucun résultat";
                        echo $select_query;
                    } else {   
                        echo $select_query;
                
                        if ($result) {
                            // Afficher le tableau HTML
                            echo "<table border='1'>
                                <tr>
                                    <th>ID Commande</th>
                                    <th>Date commande</th>
                                    <th>Nom du produit</th>
                                    <th>Infos produit</th>
                                    <th>Quantité commandée</th>
                                    <th>Montant commande</th>
                                    <th>Commission ROSE. (5%)</th>
                                    <th>CA fournisseur</th>
                                </tr>";
                            $montant_commande = 0;
                            // Parcourir les résultats et afficher chaque ligne dans le tableau
                            while ($rowdata = mysqli_fetch_assoc($result)) {
                                $id_commande = $rowdata['id_commande'];
                                $nom_produit = $rowdata['nom_produit'];
                                $categorie_produit = $rowdata['categorie_produit'];
                                $marque_produit = $rowdata['marque_produit'];
                                $quantité_produit = $rowdata['quantité_produit'];
                                $date_commande = $rowdata['date_commande'];
                                $montant = $rowdata['montant_total'];
                                $commission = $montant * 0.05 ;
                                $ca_fournisseur = $montant - $commission;

                                echo '<tr>
                                        <td>'.$id_commande.'</td>
                                        <td>'.$date_commande.'</td>
                                        <td>'.$nom_produit.'</td>
                                        <td>'.$categorie_produit.'<br>'.$marque_produit.'</td>
                                        <td>'.$quantité_produit.'</td>
                                        <td>'.$montant.'</td>
                                        <td>'.$commission.'</td>
                                        <td>'.$ca_fournisseur.'</td>                               
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
    </div> 
</body>
</html>