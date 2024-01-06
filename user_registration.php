<?php 
    include("include/connect.php");
    // Vérifie si l'utilisateur est déjà connecté
    session_start();

    if (isset($_SESSION['user_id'])) {
        //echo $_SESSION['user_id']." est connecté";
        $user = $_SESSION['user_id_id'];
    } else {
        //echo "déconnecté";
    }
 
    //reload clean var
    $Err1 = $Err2 = $Err3 = $Err4 = $Err5 = "";
    $email = $raisonsociale = $siren = $nom = $prenom = $date_de_naissance = $mot_de_passe = $confirmer_mot_de_passe = $adresse = $code_postal = $ville = $telephone = "";

    if (isset($_POST["user_register"])) {
        $email = $_POST["email"];
        if ($_POST["type"] == "particulier") {
            $type = 0;
        } else {
            $type = 1;
        }
        $raisonsociale = $_POST["raisonsociale"];
        if (!isset($_POST["siren"])) {
            $siren = null;
        } else {
            $siren = $_POST["siren"];
        }
        $nom = $_POST["nom"];
        $prenom = $_POST["prenom"];
        $adresse = $_POST["adresse"];
        $code_postal = $_POST["code_postal"];
        $ville = $_POST["ville"];
        $telephone = $_POST["telephone"];
        $date_de_naissance = $_POST["date_de_naissance"];
        $mot_de_passe = $_POST["mot_de_passe"];
        $HASHED_mot_de_passe = password_hash($mot_de_passe, PASSWORD_DEFAULT);
        $confirmer_mot_de_passe = $_POST["confirmer_mot_de_passe"];

        //calcul age
        // Créez un objet DateTime à partir des chaînes de dates
        $date_naissance_objet = new DateTime($date_de_naissance);
        // Calcul de la différence en années
        $interval = $date_naissance_objet->diff(new DateTime(date("Y-m-d")));
        $age = $interval->y;

        //check unique email query
        $select_query1 = "SELECT * FROM client WHERE email_client='$email'";
        $result1 = mysqli_query($con, $select_query1);
        $rows_count1 = mysqli_num_rows($result1);

        $select_query2 = "SELECT * FROM client WHERE siren_client='$siren'";
        $result2 = mysqli_query($con, $select_query2);
        $rows_count2 = mysqli_num_rows($result2);

        if ($rows_count1 > 0) {
            $Err1 = "Cette adresse email est déjà enregistrée";
        }
        if ($type == 1 and (!is_numeric($siren) or $siren > 999999999 or $siren < 100000000)) {
            $Err2 = "Le N° de SIREN est invalide";
        }
        if ($rows_count1 > 0) {
            $Err2 = "Le N° de SIREN est déjà attribué à un compte";
        }
        if (!is_numeric($telephone)) {
            $Err3 = "Le numéro de téléphone est invalide";
        }
        if ($age < 18) {
            $Err4 = "Vous devez avoir 18 ans";
        }
        if ($mot_de_passe != $confirmer_mot_de_passe) {
            $Err5 = "Les mots de passe ne correspondent pas";
        }

        // If there are any errors, do not proceed with the insertion
        if ($Err1 || $Err2 || $Err3 || $Err4 || $Err5) {
            // Handle errors here if needed
        } else {
            $Err1 = $Err2 = $Err3 = $Err4 = $Err5 = "";

            //recup id_gestionnaire avec le moins de clients
            $gest_query = "SELECT id_gestionnaire, COUNT(id_gestionnaire) AS occurrences
                FROM client
                GROUP BY id_gestionnaire
                ORDER BY occurrences
                LIMIT 1;";
            $gest_result = mysqli_query($con, $gest_query);
            $gest_data = mysqli_fetch_assoc($gest_result);
            $id_gestionnaire = $gest_data['id_gestionnaire'];

            //insert query
            //d'abord client
            $insert_query_client = "INSERT INTO client (email_client, type_client, raisonsociale_client, siren_client, nom_client, prenom_client, password_client, numtel_client, datedenaissance_client, date_creation, id_gestionnaire)
                                   VALUES ('$email', '$type', '$raisonsociale', '$siren', '$nom', '$prenom', '$HASHED_mot_de_passe', '$telephone', '$date_de_naissance', CURRENT_TIMESTAMP, '$id_gestionnaire')";
            $sql_execute_client = mysqli_query($con, $insert_query_client);
            
            //recup id_client
            $id_query = "SELECT * FROM client 
                WHERE email_client = '$email'" ;
            $idresult = mysqli_query($con,$id_query);
            $rowdata2 = mysqli_fetch_assoc($idresult);
            $id_client = $rowdata2['id_client'];

            // puis l'adresse
            $insert_query_adresse = "INSERT INTO adresse (numetrue_adresse, codepostal_adresse, villeadresse_adresse, type_adresse, id_client)
            VALUES ('$adresse', '$code_postal', '$ville', 'facturation', '$id_client')";
            $sql_execute_adresse = mysqli_query($con, $insert_query_adresse);
            
            if (!$sql_execute_adresse) {
            echo "Erreur SQLquery adresse: ";
            die(mysqli_error($con));
            }

            if ($sql_execute_client) {
                echo "<script>alert('Compte ROSE ajouté avec succès')</script>";
                echo "<script>window.open('user_connexion.php','_self')</script>";
            } else {
                echo "Erreur SQLquery client: ";
                die(mysqli_error($con));
            }
        }
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>ROSE. | Rejoignez ROSE.</title>
    <link rel="stylesheet" type="text/css" href="css/main_style.css">
    <link rel="stylesheet" type="text/css" href="css/chatbox.css">
    <style>
        .main {
            background-color: white;
        }
        .outer-container{
            margin-left:30%;
            margin-right:30%;
            margin-top:10%;
            margin-bottom:10%;
            background-color: white;
            align-items: center;
            text-align: center;
            border: 2px solid deeppink;
            background-color : #FFCFDE;
        }
        .hidden {
            display: none;
        }

    </style>
</head>
<body> 
    <nav class="navbar">
        <div class="navdiv"> 
            <div class="search">
                    <form action="produits.php" method="GET">
                        <div class="icon"></div>
                        <div class="input">
                                <input type="text" placeholder="Rechercher" id="mysearch" name="mysearch">
                                <span class="clear" onclick="document.getElementById('mysearch').value = ''"></span>
                                <button type="submit">Go</button>
                        </div>
                    </form>
            </div>
            <div ></div>
            <div class="logo">
                <a href="index.php"><img src="images/rose.png"></a>
            </div>
            <ul>
                <?php
                    if(isset($_SESSION['user_id'])){
                        echo '<li ><a href="include/logout.php"><img src="images/logout1.png"></a></li>';
                        echo '<li ><a href="espace_client_particulier.php"><img src="images/client.png"></a></li>';
                    } else {
                        echo '<li ><a href="user_registration.php">Inscription</a></li>';
                        echo '<li ><a href="user_connexion.php"><img src="images/client.png"></a></li>';
                    }
                ?>
                <li ><a href="cart.php"><img src="images/cart.png"></a></li>
                <div ></div>
                <button class="hamburger">
                    <div class="bar"></div>
                </button>
            </ul>
        </div>
    </nav>

    <nav class="mobile-nav">
        <a href="index.php">Accueil</a>
        <?php 
        if(isset($_SESSION['user_id'])&&$_SESSION['user_type']==1){
            echo '<a href="espace_client_entreprise.php">Espace Entreprise</a>';
        } else if (isset($_SESSION['user_id'])&&$_SESSION['user_type']=='X'){
            echo '<a href="user_connexion.php">Espace Admin</a>';
        } else {
            echo '<a href="user_connexion.php">Espace Client</a>';
        }
        ?>
        <a href="produits.php">Tous les produits</a>
        <a href="produits.php?categorie=outillerie&marque=all">Outillerie</a>
        <a href="produits.php?categorie=peinture&droguerie&marque=all">Peinture</a>
        <a href="aproposde.php">A propos de ROSE.</a>
        <?php 
        if(isset($_SESSION['user_id'])){
            echo '<a href="include/logout.php">Déconnexion</a>';
        } else {
            echo '<a href="user_connexion.php">Connexion</a>';
        }
        ?>        
    </nav>

    <main class="main">
    <div class="outer-container">
        <br><h2>Rejoignez notre communauté de bricoleurs</h2><br>   
    
        <form method="POST" action="" enctype="multipart/form-data">
        	
            <label for="email" class="form-label">Email :</label><br>
            <input type="email" id="email" name="email" placeholder= "juliendupond@gmail.com" value="<?php echo htmlspecialchars($email); ?>" required><br>
            <span style="color: red;" class="error" id="error-message1">*<?php if(isset($Err1)){echo $Err1;}else{}?></span><br>
            
            <label for="type">Etes-vous :</label>
            <input type="radio" id="particulier" name="type" value="particulier" checked onclick="toggleFields()"> <label for="particulier">Un particulier</label>
            <input type="radio" id="entreprise" name="type" value="entreprise" onclick="toggleFields()"> <label for="entreprise">Une entreprise</label>
            <br><br>

            <div id="raisonSocialeField" class="hidden">
                <label for="raisonSociale">Raison Sociale :</label><br>
                <input type="text" id="raisonsociale" name="raisonsociale"  placeholder= "Rose Tools" value="<?php echo htmlspecialchars($raisonsociale); ?>"><br>
                <span style="color: red;">*</span><br>
            </div>

            <div id="sirenField" class="hidden">
                <label for="siren">N° de SIREN :</label><br>
                <input type="number" id="siren" name="siren" placeholder= "890283744" value="<?php echo htmlspecialchars($siren); ?>"><br>
                <span style="color: red;" class="error" id="error-message2">*<?php if(isset($Err2)){echo $Err2;}else{}?></span><br>
            </div>
            
            <label for="prenom" class="form-label">Prénom :</label><br>
            <input type="text" id="prenom" name="prenom" placeholder= "Dupond" value="<?php echo htmlspecialchars($prenom); ?>" required><br>
            <span style="color: red;">*</span><br> 

            <label for="nom" class="form-label">Nom :</label><br>
            <input type="text" id="nom" name="nom" placeholder= "Julien" value="<?php echo htmlspecialchars($nom); ?>" required><br>
            <span style="color: red;">*</span><br>
            
            <label for="adresse" class="form-label">Adresse :</label><br>
            <input type="text" id="adresse" name="adresse" placeholder= "31, Rue du marteau" value="<?php echo htmlspecialchars($adresse); ?>"><br><br>
            
            <label for="code_postal" class="form-label">Code postal :</label><br>
            <input type="text" id="code_postal" name="code_postal" placeholder= "75002" value="<?php echo htmlspecialchars($code_postal); ?>"><br><br>
            
            <label for="ville" class="form-label">Ville :</label><br>
            <input type="text" id="ville" name="ville" placeholder= "Paris" value="<?php echo htmlspecialchars($ville); ?>"><br><br>
            
            <label for="telephone" class="form-label">Numéro de téléphone :</label><br>
            <input type="tel" id="telephone" name="telephone" placeholder= "0601020304" value="<?php echo htmlspecialchars($telephone); ?>"><br>
            <span style="color: red;" class="error" id="error-message3"><?php if(isset($Err3)){echo $Err3;}else{}?></span><br>
            <br>
            
            <label for="date_de_naissance" class="form-label">Date de naissance :</label><br>
            <input type="date" id="date_de_naissance" name="date_de_naissance" value="<?php echo htmlspecialchars($date_de_naissance); ?>"><br>
            <span style="color: red;" class="error" id="error-message4"><?php if(isset($Err4)){echo $Err4;}else{}?></span><br>
            
            <label for="mot_de_passe" class="form-label">Mot de passe :</label><br>
            <input type="password" id="mot_de_passe" name="mot_de_passe" value="<?php echo htmlspecialchars($mot_de_passe); ?>" required><br>
            <span style="color: red;">*</span><br>
            <br>
            
            <label for="confirmer_mot_de_passe" class="form-label">Confirmer le mot de passe :</label><br>
            <input type="password" id="confirmer_mot_de_passe" name="confirmer_mot_de_passe" value="<?php echo htmlspecialchars($confirmer_mot_de_passe); ?>" required><br>
            <span style="color: red;" class="error" id="error-message5">*<?php if(isset($Err5)){echo $Err5;}else{}?></span><br>
            <br>
       
            <input type="submit" value="Inscription" name="user_register">
            <br><br>
            <p>Vous avez déjà un compte ? <a href="user_connexion.php">Connectez-vous</a></p>
        </form>
        <br>
		<br>
    </div>
    </main>

    <div class="chatbox">
        <div class="chat-header">
            Chat en direct
            <span class="close-chat">&times;</span>
        </div>
        <div class="chat-content">
            <!-- Contenu de la boîte de chat -->
        </div>
        <div class="chat-input">
            <input type="text" placeholder="Tapez votre message...">
            <button>Envoyer</button>
        </div>
    </div>

    <footer class="footer">
        <br><br>
        <div class="terms"><a target="_blank" href="confidentialite.php" id="privacy-policy" class="qa-privacypolicy-link" rel="noopener">Politique de confidentialité</a> | <a href="conditions-generales.php" id="terms-and-conditions" class="qa-tandc-link" target="_blank" rel="noopener">Termes et Conditions</a><p>Copyright © ROSE. 2023<br></p></div>
        <br><br>
    </footer>

    <script src="javascript/chatbox.js"></script>
    <script src="javascript/search.js"></script>
    <script src="javascript/burgernavbar.js"></script>
    <script src="javascript/form_input_pro.js"></script>
</body>
</html>

