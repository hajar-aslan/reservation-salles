<?php

session_start();
$userConnected = false;

if (isset($_SESSION['id'])) {

    $id = $_SESSION['id'];
    $query = "SELECT * FROM `utilisateurs` WHERE id = '$id'";

    $result = mysqli_query($connexion, $query);
    $user = mysqli_fetch_assoc($result);

    if ($user) {
        // utilisateur est connecté
        $userConnected = true;     
        $login = $user['login'];   
    }

}

?>