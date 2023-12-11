<?php
    //session
    session_start();
    if(isset($_SESSION['user_id'])){
        echo $_SESSION['user_id']. " est connecté.";
    }else{
        echo "Connectez-vous pour continuer";
    }
?>