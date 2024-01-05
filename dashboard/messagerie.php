<?php
    include("./include/connect.php");
    include("./include/fonctions.php");

    if (isset($_SESSION['user_id'])) {
        //echo $_SESSION['user_id']." est connecté";
    } else {
        //echo "déconnecté";
    }
    $user = $_SESSION['user_id_id'];
    $user_type = $_SESSION['user_type'];

    //messagerie.php fonctionne en miroir pour gestionnaire et client > set ids
    if($user_type == 'X'){
        $id_gestionnaire = $user;
        $user_query = "SELECT * FROM client  ";
        if(isset($_GET['msg'])){
            $id_client = $_GET['msg'];
        } else {
            $id_client = 1;
        }
        $user_query .= "WHERE id_client = '$id_client'";
        $data = singleQuery($user_query);
        $client = $data['prenom_client'].' '. $data['nom_client'];
    } else {
        $id_client = $user;
        $user_query = "SELECT * FROM client  WHERE id_client = '$user'";
        $data = singleQuery($user_query);
        $id_gestionnaire = $data['id_gestionnaire'];
        $client = $data['prenom_client'].' '. $data['nom_client'];
    }

    if (($user_type == 'X' && isset($_GET['msg'])) || $user_type != 'X') {
        $messages = getMessages($con, $id_client,$id_gestionnaire);
    } else {
        $messages = [];
    }

    //envoyer un message
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['send'])) {
        $messageText = $_POST['message_text']; // Assurez-vous de valider/sanitiser les données entrées par l'utilisateur
        $sens = ($_SESSION['user_type'] == 'X') ? 1 : 0; // 1 pour gestionnaire>client, 0 pour client>gestionnaire

        if(isset($_POST['message_text'])){

        // Insérer le message dans la table "message"
        if (sendMessage($con, $messageText, $id_client, $id_gestionnaire, $sens)) {
            unset($_POST['message_text']);
            echo "<script>window.location.href = '" . $_SERVER['PHP_SELF'] . "?messagerie&msg={$id_client}';</script>";
        } else {
            echo "Erreur lors de l'envoi du message.";
        }
        }
    }

    // Gérer la suppression du message
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) {
        $messageIdToDelete = $_POST['message_id'];

        // Supprimer le message en utilisant la fonction deleteMessage
        if (deleteMessage($con, $messageIdToDelete)) {
            echo "<script>window.location.href = '" . $_SERVER['PHP_SELF'] . "?messagerie&msg={$id_client}';</script>";
        } else {
            echo "Erreur lors de la suppression du message.";
        }
    }


?>
<!DOCTYPE html>
<html>
<head>
    <title>Messagerie</title>
    <style>
        .container {
            display: flex; /* Utiliser la flexbox pour organiser les éléments en ligne ou en colonne */
        }
        .vignette-container {
            width: 20%;
            padding: 10px;
            box-sizing: border-box;
        }

        .vignette {
            cursor: pointer;
            margin-bottom: 10px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 8px;
        }
        .message-container {
            max-width: 600px;
            margin: auto;
        }

        .message {
            width: fit-content;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 8px;
            max-width: 80%;
            word-wrap: break-word; /* Force le texte à passer à la ligne si nécessaire */
        }

        .message-recu {
            background-color: #ddd; /* Couleur de fond pour les messages envoyés */
            color: #000; /* Couleur du texte */
            text-align: left; /* Aligner le texte à gauche */
        }

        .message-envoye {
            background-color: #2196f3; /* Couleur de fond pour les messages reçus */
            color: #fff; /* Couleur du texte */
            text-align: right; /* Aligner le texte à droite */
            margin-left: auto; /* Pousse la div à droite du conteneur */
            max-width: 70%; /* Ajout d'une largeur maximale */        
        }

        .message-form {
            max-width: 600px;
            margin: auto;
            margin-top: 20px;
        }
        .message-form input {
            width: 80%;
            padding: 8px;
            margin-right: 5px;
        }
        .message-form button {
            padding: 8px;
        }

    </style>
</head>
<body>
    <h2>Messagerie</h2>
    <div class="container">
        <?php if ($user_type == 'X') : ?>
            <div class="vignette-container">
                    <?php
                    $query_clients = "SELECT id_client, prenom_client, nom_client FROM client WHERE id_gestionnaire = '$user'";
                    $result_clients = $con->query($query_clients);

                    while ($client = $result_clients->fetch_assoc()) {
                        $id_client = $client['id_client'];
                        $last_message_query = "SELECT contenu_message, date_message FROM message WHERE idclient_message = '$id_client' AND type_message = 'message' ORDER BY date_message DESC LIMIT 1";
                        $last_message = singleQuery($last_message_query);
                        if ($last_message !== null) {
                            $truncated_message = (strlen($last_message['contenu_message']) > 20) ? substr($last_message['contenu_message'], 0, 20) . '...' : $last_message['contenu_message'];
                        } else {
                            $truncated_message = '';
                        }
                    ?>
                        <div class="vignette" onclick="submitForm(<?php echo $id_client; ?>)">
                            <p><strong><?php echo $client['prenom_client'] . ' ' . $client['nom_client']; ?></strong></p>
                            <p><?php echo ($last_message) ? $truncated_message : ''; ?></p>
                            <p><?php echo ($last_message) ? date('d/m/y H:i', strtotime($last_message['date_message'])) : ''; ?></p>
                            <form id="form_<?php echo $id_client; ?>" action="" method="get" style="display: none;">
                                <input type="hidden" name="msg" value="<?php echo $id_client; ?>">
                            </form>
                        </div>
                    <?php
                    }
                    ?>
            </div>
        <?php endif; ?>
        <div class="message-container">
            <?php foreach ($messages as $message) : ?>
                <?php
                if ($user_type == 'X') {
                    $messageClass = ($message['sens'] == 1) ? 'message-envoye' : 'message-recu';
                } else {
                    $messageClass = ($message['sens'] == 1) ? 'message-recu' : 'message-envoye';
                }
                ?>
                <p class="message <?= $messageClass; ?>">
                    <strong><?= ($message['sens'] == 1) ? $id_gestionnaire : $client; ?></strong><br>
                    <?= $message['contenu_message']; ?>
                    <br>
                    <em><?= date('d/m/y H:i', strtotime($message['date_message'])); ?></em>
                    <?php if ($messageClass == 'message-envoye') : ?>
                        <form action="" method="post" style="display:inline;">
                            <input type="hidden" name="message_id" value="<?= $message['id_message']; ?>">
                            <button type="submit" name="delete" style="color: red;">x</button>
                        </form>
                    <?php endif; ?>
                </p>
            <?php endforeach; ?>
            <form action="" method="post" class="message-form">
                <input type="text" name="message_text" placeholder="Votre message">
                <button type="submit" name="send">Envoyer</button>
            </form>
            <br>
        </div>
    </div>
    <script>
        function submitForm(clientId) {
            document.getElementById('form_' + clientId).submit();
        }
    </script>
</body>
</html>

<?php
// Fermer la connexion à la base de données
$con->close();
?>
