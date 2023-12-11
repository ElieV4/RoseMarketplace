<?php
    //session // pour l'instant connecté à rien
    session_start();
    $_SESSION['user_id']=$email;
    $_SESSION['user_id_id']=$rowdata['id_client'];
    $_SESSION['password']=$rowdata['password_client'];
    $_SESSION['user_type'] = $rowdata['type_client'];
    echo "session data is saved";
?>