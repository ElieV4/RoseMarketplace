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

    // Vérifie si l'utilisateur est déjà connecté
    if (isset($_SESSION['user_id']) and !empty($_SESSION['user_id'])) {
        header("Location: espace_client_particulier.php");
        exit();
    }

    //reload clean var
    $email = $mot_de_passe = "";
    
    if (isset($_POST["user_login"])) {

        $email = $_POST["email"];
        $mot_de_passe = $_POST["mot_de_passe"];
        
        //verif password
                $select_query1 = "SELECT 
                    id_client AS id,
                    email_client AS email,
                    password_client AS password,
                    type_client AS type,
                    statut_pro AS statut
                FROM client
                WHERE email_client='$email'
                UNION
                SELECT 
                    id_gestionnaire AS id,
                    email_gestionnaire AS email,
                    password_gestionnaire AS password,
                    'gestionnaire' AS type,
                    'admin' AS statut
                FROM gestionnaire
                WHERE email_gestionnaire='$email';";
            $result1 = mysqli_query($con,$select_query1);
            $rows_count1= mysqli_num_rows($result1);
            $rowdata = mysqli_fetch_assoc($result1);
        if ($rows_count1 > 0) {
            $type = $rowdata['type'];
            if(password_verify($mot_de_passe,$rowdata['password'])){
                $_SESSION['user_id']=$email;
                $_SESSION['user_id_id']=$rowdata['id'];
                $_SESSION['statut']=$rowdata['statut'];
                if($type==0){
                    $_SESSION['user_type'] = 0;
                    echo "<script>alert('Connexion réussie')</script>";
                    echo "<script>window.open('espace_client_particulier.php','_self')</script>"; 
                }else if($type==1){
                    $_SESSION['user_type'] = 1;
                    echo "<script>alert('Connexion réussie')</script>";
                    echo "<script>window.open('espace_client_particulier.php','_self')</script>"; 
                }else{
                    $_SESSION['user_type'] = 'X';
                    echo "<script>alert('Connexion réussie')</script>";
                    echo "<script>window.open('espace_gestionnaire.php','_self')</script>"; 
                }
            }else{
                $Err1 = "Email et/ou mot de passe invalide";
            }
        }else{
            $Err1 = "Email absent de nos serveurs";
        }

    }
?>


<!DOCTYPE html>
<html>
<head>
    <title>ROSE. | Connexion</title>
    <link rel="stylesheet" type="text/css" href="css/main_style.css">
    <link rel="stylesheet" type="text/css" href="css/user.css">
</head>
<body> 
    <?php include('entete.php')?>

    <main class="main">
    <div class="outer-container">
        <h2>Connexion</h2>
    
        <form method="POST" action="" enctype="multipart/form-data">
            <br>
            <label for="email" class="form-label">Email :</label><br>
            <input type="email" id="email" name="email" placeholder= "juliendupond@gmail.com" required><br>         
            <label for="mot_de_passe" class="form-label">Mot de passe :</label><br>
            <input type="password" id="mot_de_passe" name="mot_de_passe" required><br>
            <span style="color: red;" class="error"><?php if(isset($Err1)){
                echo $Err1;}else{}?></span><br>
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
    <?php include('footer.php')?>
</body>
</html>