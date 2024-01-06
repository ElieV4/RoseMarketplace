<?php 
    include("include/connect.php");
    session_start();

    if (isset($_SESSION['user_id'])) {
        $user = $_SESSION['user_id_id'];
    }
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rose. | Politique de confidentialité</title>
    <link rel="stylesheet" type="text/css" href="css/main_style.css">
    <link rel="stylesheet" type="text/css" href="css/chatbox.css">
    <link rel="stylesheet" type="text/css" href="css/page_basique.css">
    <link rel="stylesheet" type="text/css" href="css/confidentialite.css">
  </head>
  
<body>  
    <?php include('entete.php')?>
    <div class="outer-container">
        <div class="content">
            <h1>Politique de Confidentialité de ROSE.</h1><br>

            <p><strong>Dernière mise à jour :</strong> 14 novembre 2023</p><br>
        
            <h2>Collecte des Informations</h2><br>
            <p>Nous collectons des informations lorsque vous créez un compte sur notre Plateforme, passez une commande, participez à un concours ou communiquez avec nous. Les informations collectées incluent votre nom, votre adresse e-mail, votre numéro de téléphone, votre adresse de livraison, etc.</p><br>
        
            <h2>Utilisation des Informations</h2><br>
            <p>Les informations que nous collectons auprès de vous peuvent être utilisées pour :</p>
            <ul>
                <li>Personnaliser votre expérience utilisateur</li>
                <li>Traiter vos transactions</li>
                <li>Vous envoyer des notifications par e-mail</li>
                <li>Améliorer notre Plateforme</li>
                <li>Vous contacter par e-mail ou par téléphone</li>
            </ul><br>
        
            <h2>Protection des Informations</h2><br>
            <p>Nous mettons en œuvre une variété de mesures de sécurité pour protéger la sécurité de vos informations personnelles. Nous utilisons des protocoles de sécurisation des données lors de la transmission de données sensibles.</p><br>
        
            <h2>Cookies</h2><br>
            <p>Nous utilisons des cookies pour nous aider à mémoriser et traiter les articles dans votre panier, comprendre et enregistrer vos préférences pour vos futures visites, suivre les publicités et compiler des données agrégées sur le trafic et l'interaction sur la Plateforme.</p><br>
        
            <h2>Divulgation à des Tiers</h2><br>
            <p>Nous ne vendons, n'échangeons ni ne transférons vos informations personnelles identifiables à des tiers. Cela n'inclut pas les tiers de confiance qui nous aident à exploiter notre Plateforme, tant qu'ils acceptent de garder ces informations confidentielles.</p><br>
        
            <h2>Consentement</h2><br>
            <p>En utilisant notre Plateforme, vous consentez à notre politique de confidentialité.</p><br>
        
            <h2>Changements de notre Politique de Confidentialité</h2><br>
            <p>Si nous décidons de changer notre politique de confidentialité, nous publierons ces changements sur cette page.</p><br>
        
            <h2>Nous Contacter</h2><br>
            <p>Si vous avez des questions concernant cette politique de confidentialité, vous pouvez nous contacter en utilisant les informations ci-dessous :</p>
            <address>
                ROSE.<br>
                123 Rue des Outils Roses, 75001 Paris, France<br>
                contact@rose.com<br>
                +33 1 23 45 67 89
            </address><br>
        
            <p>Merci de faire confiance à ROSE.</p><br>
        </div>
    </div>
    <?php include('footer.php')?>
</body>
</html>