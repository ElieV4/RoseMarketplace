<?php

$server = "localhost";
$user = "root"; 
$password = "";
$database = "rosemarketplace";

// Connexion à la base de données MySQL
$mysqli = new mysqli($server, $user, $password);

// Vérification de la connexion
if ($mysqli->connect_error) {
    die("Erreur de connexion à la base de données : " . $mysqli->connect_error);
}

// Création de la base de données
$sql = file_get_contents("createdatabase_marketplace.sql");
if ($mysqli->multi_query($sql)) {
    echo "Base de données créée avec succès.";
} else {
    echo "Erreur lors de la création de la base de données : " . $mysqli->error;
}

// insert compte gestionnaire 1
$pw = 'admin';
$password = password_hash($pw,PASSWORD_BCRYPT);
$insert_query_admin = "INSERT INTO gestionnaire (id_gestionnaire, email_gestionnaire, password_gestionnaire)
VALUES ('G1','gestionnaire@rose.com', '$password');";
$sql_execute_admin = mysqli_query($con, $insert_query_admin);

// Fermeture de la connexion
$mysqli->close();
?>