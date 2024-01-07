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
    <link rel="stylesheet" type="text/css" href="./css/chatbox.css">
    <link rel="stylesheet" type="text/css" href="./css/profil.css">
    <link rel="stylesheet" type="text/css" href="./css/main_style.css">    
</head>
<body>  
    <div class="outer-container">
        <div class="content">
            <div class="col1">
                <?php
                    $select_query = "SELECT *
                                    FROM client
                                    WHERE id_client = '$user'";
                    $result = mysqli_query($con, $select_query);
                    
                    if ($result) {
                        
                        if ($rowdata = mysqli_fetch_assoc($result)) {
                            $id_client = $rowdata['id_client'];
                            $prenom_client = $rowdata['prenom_client'];
                            $nom_client = $rowdata['nom_client'];
                            $email_client = $rowdata['email_client'];
                            $type_client = $rowdata['type_client'];
                            if($type_client==0){
                                $client = $rowdata['prenom_client']. ' '. $rowdata['nom_client'];
                            } else {
                                $entreprise = $rowdata['raisonsociale_client'];
                                $siren = $rowdata['siren_client'];
                                $client = $prenom_client . ' ' . $nom_client;
                            }

                            echo "<table border='1'>
                            <tr>
                                <th>ID Client</th>
                                <th>Email</th>
                                <th>Titulaire du compte</th>";
                                if(isset($entreprise)){
                                    echo "<th>Entreprise</th>";
                                    echo "<th>SIREN</th>";
                                }
                            echo "</tr>";
                            echo '<tr>
                                <td>'.$id_client.'</td>
                                <td>'.$email_client.'</td>
                                <td>'.$client.'</td>';
                                if(isset($entreprise)){
                                    echo '<td>'.$entreprise.'</td>';
                                    echo '<td>'.$siren.'</td>';
                                }
                            echo '</tr>';
                        }
                        echo '</table><br>';
                    } else {
                        // En cas d'erreur lors de l'exécution de la requête
                        echo "Erreur dans la requête : " . mysqli_error($con);
                    }
                    //adresse de facturation
                    $facturation_query = "SELECT *, count(DISTINCT id_adresse) AS nbadresses
                        FROM client c 
                        LEFT JOIN adresse a USING (id_client) 
                        WHERE c.id_client ='$user' AND type_adresse='facturation'";
                    $facturation_result = mysqli_query($con, $facturation_query);
                    echo "<h3>Votre adresse de facturation</h3>";
                    echo "<table border='0,5'>";
                    if ($facturation_result) {
                        while ($rowdata1 = mysqli_fetch_assoc($facturation_result)) {

                            $id_client = $user;
                            $typeclient = $rowdata1['type_client'];                               
                            if($typeclient ==1){
                                $client = $rowdata1['raisonsociale_client'];
                            } else {
                                $client = $rowdata1['prenom_client']. ' ' .$rowdata1['nom_client'];
                            }

                            $id_adresse = $rowdata1['id_adresse'];
                            $rue = $rowdata1['numetrue_adresse'];
                            $codepostal = $rowdata1['codepostal_adresse'];
                            $ville = $rowdata1['villeadresse_adresse'];
                            $typeadresse = $rowdata1['type_adresse'];
                        
                            echo '<tr>
                                    <td class="info">';
                            if(isset($_GET['modifadresse'])&& $_GET['id']==$id_adresse){
                                include('include/modifier_une_adresse.php');
                            } else {
                                echo $client.'<br>'.$rue.'<br>'.$ville.", ".$codepostal.'</td>';
                                echo '<td><button><a href="espace_client_particulier.php?profil&modifadresse&id='.$id_adresse.'">Modifier</a></button></td></tr>';                                        
                            }
                                
                        }
                        echo '</table><br>';
                    } else {
                        echo 'Erreur dans la requête : ' . mysqli_error($con);
                    }

                    //adresses de livraison 
                    $livraison_query = "SELECT *, (SELECT COUNT(DISTINCT id_adresse) FROM adresse a WHERE a.id_client = c.id_client) AS nbadresses
                        FROM client c 
                        LEFT JOIN adresse a USING (id_client) 
                        WHERE c.id_client ='$user' AND type_adresse='livraison'";
                    $livraison_result = mysqli_query($con, $livraison_query);

                    echo "<h3>Vos adresses de livraison</h3>";
                    echo "<table border='0,5'>";
                    //contenu livraison (formulaire ou info)
                    if ($livraison_result) {
                        $rowdata2 = mysqli_fetch_assoc($livraison_result);
                        if(!$rowdata2==null){
                            mysqli_data_seek($livraison_result, 0);
                            while ($rowdata2=mysqli_fetch_assoc($livraison_result)) {
                                $id_client = $user;
                                $typeclient = $rowdata2['type_client'];                               
                                if($typeclient ==1){
                                    $client = $rowdata2['raisonsociale_client'];
                                } else {
                                    $client = $rowdata2['prenom_client']. ' ' .$rowdata2['nom_client'];
                                }

                                $id_adresse = $rowdata2['id_adresse'];
                                $rue = $rowdata2['numetrue_adresse'];
                                $codepostal = $rowdata2['codepostal_adresse'];
                                $ville = $rowdata2['villeadresse_adresse'];
                                $typeadresse = $rowdata2['type_adresse'];
                            
                                echo '<tr>                                        
                                    <td class="info">';
                                if(isset($_GET['modifadresse'])&& $_GET['id']==$id_adresse){
                                    include('include/modifier_une_adresse.php');
                                } else {
                                    echo $client.'<br>'.$rue.'<br>'.$ville.", ".$codepostal.'</td>';
                                    echo '<td><button><a href="espace_client_particulier.php?profil&modifadresse&id='.$id_adresse.'">Modifier</a></button></td>';                                        
                                }
                                echo '<td><button onclick="supprimerAdresse(' . $id_adresse . ', function() { location.reload(); })">Supprimer</button></td>
                                    </tr>';
                            }
                        }
                    echo '</table><br>';

                    //moyens de paiements
                    $paiement_query = "SELECT *,(SELECT COUNT(DISTINCT id_paiement) FROM paiement p WHERE p.id_client = c.id_client) AS nbpaiements
                        FROM paiement p
                        LEFT JOIN client c USING (id_client)
                        WHERE p.id_client = '$user';";
                    $paiement_result = mysqli_query($con, $paiement_query); 

                    echo "<h3>Vos moyens de paiement</h3>";
                    echo "<table border='0,5'>";
                    //contenu paiement (formulaire ou info)
                    if ($paiement_result) {
                        $rowdata3 = mysqli_fetch_assoc($paiement_result);
                        if(!$rowdata3==null){
                            mysqli_data_seek($paiement_result, 0);
                            while ($rowdata3=mysqli_fetch_assoc($paiement_result)) {
                                $id_client = $user;
                                $id_paiement = $rowdata3['id_paiement'];
                                $type_paiement = $rowdata3['type_paiement'];
                                $titulaire =  $rowdata3['titulaire'];                              
                                if($type_paiement == 'iban'){
                                    $iban = $rowdata3['iban'];
                                    $bic = $rowdata3['bic'];

                                    echo '<tr>
                                        <td class="info">Compte bancaire '.$titulaire.'<br>'.$iban.'</td>';
                                    echo '<td><button onclick="supprimerPaiement(' . $id_paiement . ', function() { location.reload(); })">Supprimer</button></td>
                                    </tr>';

                                } else {
                                    $banquecb = $rowdata3['banquecb'];
                                    $numcb = $rowdata3['numcb'];
                                    $titulaire = $rowdata3['titulaire'];
                                    $expirationcb = $rowdata3['expirationcb'];
                                    $cryptogrammecb = $rowdata3['cryptogrammecb'];

                                    echo '<tr>
                                        <td class="info">Carte bancaire '.$banquecb.'<br>'.$titulaire.' '.$expirationcb.'</td>';                                        
                                    echo '<td><button onclick="supprimerPaiement(' . $id_paiement . ', function() { location.reload(); })">Supprimer</button></td>
                                    </tr>';
                                }
                            
                            }
                        }
                    echo '</table><br>';
                ?>
            </div>
            <div class="col2">
                <?php
                    if(isset($_GET['ajoutadresse'])){
                        include('include/ajouter_une_adresse.php');
                    } else {
                        echo '<button><a href="espace_client_particulier.php?profil&ajoutadresse">Ajouter une adresse de livraison</a></button><br><br>';
                    }
                    } else {
                        echo 'Erreur dans la requête : ' . mysqli_error($con);
                    }
                    if(isset($_GET['ajoutpaiement'])){
                        include('include/ajouter_paiement.php');
                    } else {
                        echo '<button><a href="espace_client_particulier.php?profil&ajoutpaiement">Ajouter une carte de débit ou de crédit</a></button><br><br>';
                    }
                    if(isset($_GET['ajoutiban'])){
                        include('include/ajouter_iban.php');
                    } else {
                        echo '<button><a href="espace_client_particulier.php?profil&ajoutiban">Ajouter un compte bancaire</a></button><br>';
                    }
                    } else {
                        echo 'Erreur dans la requête : ' . mysqli_error($con);
                    }
                ?>
            </div>
        </div>
    </div>
    <script src="javascript/commande.js"></script>
</body>
</html>