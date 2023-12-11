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
// Fermeture de la connexion
$mysqli->close();
?>