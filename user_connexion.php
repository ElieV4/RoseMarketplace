<?php 
    include("include/connect.php");
    // Vérifie si l'utilisateur est déjà connecté
    session_start();
    if (isset($_SESSION['user_id']) and !empty($_SESSION['user_id'])) {
        // Si l'utilisateur est connecté, redirige vers espace_client_entreprise.php
        if($_SESSION['user_type']!=1){
            //echo "part";
            header("Location: espace_client_particulier.php");
            exit();
        } else {
            //echo "entr";
            header("Location: espace_client_entreprise.php");
            exit();
        }
    }

    //reload clean var
    $Err1 = "";
    $email = $mot_de_passe = "";

    if (isset($_POST["user_login"])) {
        $email = $_POST["email"];
        $mot_de_passe = $_POST["mot_de_passe"];
 

        //verif password 
        $select_query1 = "SELECT * FROM client WHERE email_client='$email'";
        $result1 = mysqli_query($con,$select_query1);
        $rows_count1= mysqli_num_rows($result1);
        $rowdata = mysqli_fetch_assoc($result1);
        $typeclient = $rowdata['type_client'];

        if ($rows_count1 > 0) {
            if(password_verify($mot_de_passe,$rowdata['password_client'])){
                    @session_start();
                    $_SESSION['user_id']=$email;
                    $_SESSION['user_id_id']=$rowdata['id_client'];
                if($typeclient==0){
                    $_SESSION['user_type'] = 0;
                    echo "<script>alert('Connexion réussie')</script>";
                    echo "<script>window.open('espace_client_particulier.php','_self')</script>"; 
                    //header("Location: espace_client_particulier.php");
                    //exit();
                } else {
                    $_SESSION['user_type'] = 1;
                    echo "<script>alert('Connexion réussie')</script>";
                    echo "<script>window.open('espace_client_entreprise.php','_self')</script>"; 
                    //header("Location: espace_client_entreprise.php");
                    //exit();
                }
            }else{
            $Err1 = "Email et/ou mot de passe invalide";
            }
        }else{
            $Err1 = "Email et/ou mot de passe invalide";
        }

    }
?>


<!DOCTYPE html>
<html>
<head>
    <title>ROSE. | Connexion</title>
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
        .error{
            display: none;
        }
    </style>
</head>
<body> 
    <nav class="navbar">
        <div class="navdiv"> 
            <div class="search">
                <div class="icon"></div>
                <div class="input">
                    <input type="text" placeholder="Rechercher" id="mysearch">
                    <span class="clear" onclick="document.getElementById('mysearch').value = ''"></span>
                </div>
            </div>
            <div ></div>
            <div class="logo">
                <a href="index.php"><img src="images/rose.png"></a>
            </div>
            <ul>
                <?php
                    if(isset($_SESSION['user_id'])){
                        echo '<li ><a href="include/logout.php"><img src="images/logout1.png"></a></li>';
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
        <a href="#">Home</a>
        <?php 
        if(isset($_SESSION['user_id'])){
            echo '<a href="espace_client_entreprise.php">Espace Client</a>';
        } else {
            echo '<a href="user_connexion.php">Espace Client</a>';
        }
        ?>
        <a href="#">Produits</a>
        <a href="#">Catégories</a>
        <a href="aproposde.html">A propos de ROSE.</a>
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
        <h2>Connexion</h2>
    
        <form method="POST" action="" enctype="multipart/form-data">
            <br>
            <span style="color: red;" class="error">*<?php echo $Err1;?></span><br>
            <label for="email" class="form-label">Email :</label><br>
            <input type="email" id="email" name="email" placeholder= "juliendupond@gmail.com" required><br>         
            <label for="mot_de_passe" class="form-label">Mot de passe :</label><br>
            <input type="password" id="mot_de_passe" name="mot_de_passe" required><br>
            <p><a href="forgot_password_form.php">Mot de passe oublié ?</a></p>
            <br>
       
            <input type="submit" value="Connexion" name="user_login">
            <br><br>
            <p>Vous n'avez pas encore de compte ? <a href="user_registration.php">Inscrivez-vous</a></p>
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
        <div class="terms"><a target="_blank" href="confidentialite.html" id="privacy-policy" class="qa-privacypolicy-link" rel="noopener">Politique de confidentialité</a> | <a href="conditions-generales.html" id="terms-and-conditions" class="qa-tandc-link" target="_blank" rel="noopener">Termes et Conditions</a><p>Copyright © ROSE. 2023<br></p></div>
        <br><br>
    </footer>

    <script src="javascript/chatbox.js"></script>
    <script src="javascript/search.js"></script>
    <script src="javascript/burgernavbar.js"></script>
    <script src="javascript/form_input_pro.js"></script>
    <script src="javascript/form_errors.js"></script>
</body>
</html>