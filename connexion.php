<?php

include('config.php');
include('auth.php');

// si l'utilisateur est deja connecter
if ($userConnected) {
    // verifie si l'utilisateur veut se deconnecter
    if (isset($_GET['deconnecter'])) {
        session_unset();
        header('Location: index.php');
        // $_SESSION['id'] = '1';
    }
    header('Location: index.php');
} 

    
if (isset($_POST['login']) && isset($_POST['password'])){
    function validate ($data){
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    $login = validate($_POST['login']);
    $password = validate($_POST['password']);

    $resultat = mysqli_query($connexion, "SELECT * FROM `utilisateurs` WHERE login = '$login'");

    if ($resultat->num_rows > 0) {
        // utilisateur / login existe dans la base de donnée
        $user = mysqli_fetch_assoc($resultat);
        
        $hash_password = $user['password'];

        echo "password -> " . $password;
        echo "hash_password -> " . $hash_password;

        if (password_verify($password, $hash_password)) {
            // utilisateur est connecté
            $_SESSION['id'] = $user['id'];
            header('Location: index.php');
            
        }else {
            header('Location: connexion.php?erreur=2');
        }


    } else {
        header('Location: connexion.php?erreur=1');
    }

    
        // var_dump($resultat);
    
    
}


?><!DOCTYPE html>

<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title> Connexion </title>
</head>
<body>

    <?php $_GET['page'] = "connexion"; include('header.php') ?>


    <main>

        <?php 
        /*if ($erreur)  echo "<h3 class='erreur'>1msg_erreur</h3>"; */
        if(isset($_GET['erreur'])){
            $err = (int) $_GET['erreur'];
            $msg = "";
    
            switch ($err) {
                case 1:
                    $msg = "login ou mot de passe incorrecte";
                    break;
                case 2:
                    $msg = "Mot de passe incorrecte";
                    break;
                default:
                    $msg = "Erreur de connexion";
                }
    
            echo "<p style='color:red'>$msg</p>";
    
        }
        
        ?>
    
        <form action="" method="post"> <!-- ceation le login-->
            <h2> Login </h2>
            
            <label> Login </label>  <!-- creation la case de login -->
            <input type="text" name="login" placeholder=" Votre Login...." required><br> 
            <label> Password </label> <!-- creation la case de login -->
            <input type="password" name="password" placeholder=" Votre Password...." required><br>
            <button type="submit"> Connexion </button>
        </form>

    </main>



    <?php include('footer.php') ?>
</body>
</html>
