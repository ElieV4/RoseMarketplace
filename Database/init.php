<?php

$server = "localhost";
$user = "root";
$password = "";
$database = "rosemarketplace";

$mysqli = new mysqli($server, $user, $password);

if ($mysqli->connect_error) {
    die("Erreur de connexion à la base de données : " . $mysqli->connect_error);
}

$sql = file_get_contents("createdatabase_marketplace.sql");
if ($mysqli->multi_query($sql)) {
    do {
        if ($result = $mysqli->store_result()) {
            $result->free();
        }
    } while ($mysqli->more_results() && $mysqli->next_result());

    // première requête succès
    if ($mysqli->errno === 0) {
        $con = mysqli_connect('localhost', 'root', '', 'rosemarketplace');
        if (!$con) {
            die(mysqli_error($con));
        } else {
            $sql2 = file_get_contents("photos.sql");
            if ($mysqli->multi_query($sql2)) {
                // Récupérer tous les résultats pour libérer la mémoire
                do {
                    if ($result = $mysqli->store_result()) {
                        $result->free();
                    }
                } while ($mysqli->more_results() && $mysqli->next_result());

                echo 'Base de données rosemarketplace créée avec succès !.<br>';
                echo '<a href="../index.php">Cliquez sur le lien suivant pour accéder au site</a>';
            } else {
                echo "Erreur lors de l'insertion des photos : " . $mysqli->error;
            }
        }
    } else {
        echo "Erreur lors de la création de la base de données : " . $mysqli->error;
    }
} else {
    echo "Erreur lors de la création de la base de données : " . $mysqli->error;
}

// Fermeture de la connexion
$mysqli->close();
?>
