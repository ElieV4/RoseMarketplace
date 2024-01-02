<?php

$server = "localhost";
$user = "root";
$password = "";
$database = "rosemarketplace1";

// Connexion à la base de données MySQL
$mysqli = new mysqli($server, $user, $password);

// Vérification de la connexion
if ($mysqli->connect_error) {
    die("Erreur de connexion à la base de données : " . $mysqli->connect_error);
}

// Création de la base de données
$sql = file_get_contents("createdatabase_marketplace.sql");
if ($mysqli->multi_query($sql)) {
    // Vérifier si la première requête s'est exécutée avec succès
    if ($mysqli->errno === 0) {
        $con = mysqli_connect('localhost', 'root', '', 'rosemarketplace1');
        if (!$con) {
            die(mysqli_error($con));
        } else {
            // insert compte gestionnaire 1 si l'id n'existe pas
            $pw = 'admin';
            $password = password_hash($pw, PASSWORD_BCRYPT);
            $id_gestionnaire = 'G1';
            $email_gestionnaire = 'gestionnaire@rose.com';

            $check_query = "SELECT id_gestionnaire FROM gestionnaire WHERE id_gestionnaire = '$id_gestionnaire'";
            $check_result = mysqli_query($con, $check_query);

            if (mysqli_num_rows($check_result) == 0) {
                $insert_query_admin = "INSERT INTO gestionnaire (id_gestionnaire, email_gestionnaire, password_gestionnaire)
                VALUES ('$id_gestionnaire', '$email_gestionnaire', '$password');";
                $sql_execute_admin = mysqli_query($con, $insert_query_admin);
            }
        }

        echo 'Base de données rosemarketplace créée avec succès !.<br>';
        echo '<a href="../index.php">Cliquez sur le lien suivant pour accéder au site</a>';
    } else {
        echo "Erreur lors de la création de la base de données : " . $mysqli->error;
    }
} else {
    echo "Erreur lors de la création de la base de données : " . $mysqli->error;
}

// Fermeture de la connexion
$mysqli->close();
?>
