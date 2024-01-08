
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
    <title>Rose. | A propos de nous</title>
    <link rel="stylesheet" type="text/css" href="css/page_basique.css">
    <link rel="stylesheet" type="text/css" href="css/main_style.css">
</head>
<body> 
    <?php include('entete.php')?>
    <div class="outer-container">
        <div class="content">
            <h1>Notre Histoire</h1>
            <br>
            <p>Le projet de ROSE., est né de notre passion partagée pour le bricolage et de notre désir de casser les conventions. Nous nous sommes rencontrés en 2019 au début de nos études, et avons rapidement découvert notre point commun inhabituel. À l'obtention de notre diplôme, il est devenu évident que nous devions concrétiser notre vision.</p>
            <br>
            <div class="imgcontainer">
                <img src="images/friendssmiling.png">
            </div>
            <br>
            <p>En tant que fervents amateurs de bricolage, nous avons toujours pris plaisir à créer et à rénover. Cependant, nous avons rapidement remarqué le manque d'originalité et de personnalité dans le monde des outils et des fournitures de bricolage. C'est à ce moment-là que l'idée de ROSE. a commencé à prendre forme. Notre objectif était de transformer l'expérience du bricolage en créant une marketplace unique qui le rendrait plus amusant, accessible et inspirant pour tous.</p>
            <br>
            <p>Le nom "ROSE." a été choisi pour évoquer la créativité et pour casser les codes associés à la couleur rose dans le monde du bricolage. Notre marketplace est devenue un espace où la couleur, le style et la pratique du bricolage se sont réunis. Au-delà de la simple vente d'outils, nous avons créé une communauté où les amateurs de bricolage peuvent se rassembler, partager des idées, apprendre et s'inspirer mutuellement.</p>
            <br>
            <div class="imgcontainer" >
                <img class="sideimg" src="images/friendsstanding.png"><img class="sideimg" src="images/groupworking.png">
            </div>
            <br>
            <p>C'est ainsi qu'est née la première marketplace de bricolage rose. Notre passion commune pour le bricolage a donné naissance à une aventure passionnante. ROSE. est bien plus qu'un projet pour nous, c'est une aventure que nous sommes fiers de partager avec vous.</p>
        </div>
    </div>
    <?php include('footer.php')?>
</body>
</html>