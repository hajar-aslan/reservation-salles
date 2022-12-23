<?php   

require 'config.php';
require 'auth.php';

$register=false;
$erreur = '';

if (isset($_POST['valider'])){   //$_POST['name']
    $login = $_POST['login'];
    $password = $_POST['password'];
    $confirmation = $_POST['confirmation'];

    // echo "login => $login";
    // echo "password => $password";
    // echo "confirmation => $confirmation";
    
    if ($login != null && $password != null && $confirmation != null){  // si tous les champs sont remplis
        // on fait l'appel à notre base de donnée
        $requet = "Select * from utilisateurs where login = '$login'";
        //vérifier les données de bd
        $sql = mysqli_query($connexion, $requet);
        // Vérifier les donnée de la base de donnée ligne par ligne
        $row_cnt = mysqli_num_rows($sql);


        var_dump($row_cnt);

        // si la ligne de tableau est égale à zéro, ça veut dire que les donnée qu'on cherche n'existe pas 
        if ($row_cnt == 0){

            if ($password == $confirmation){ // si le mot passe et la confirmation sont égaux 
                $hash = password_hash($password, PASSWORD_DEFAULT); // pour cripter le mot de passe dans la base de donnée 
                
                //une requette sql pour inserer les inputs dans la base de donnée 
                $requet1 = "INSERT INTO `utilisateurs` (`login` ,`password`) VALUES ('$login','$hash')";

                $sql = mysqli_query($connexion , $requet1); // Vérification de la requette
                if ($sql){

                    $register = true;
                    header('Location: connexion.php');
                    session_destroy();
                }

                echo "hash => $hash";
                var_dump($sql);

            }else{
                $erreur = 'Mot passe incorrecte '; // message d'erreur pour les mots de passe qui ne sont pas identiques 
            }
        }else{
            $erreur= 'login existe déjà';
        }


    } else {
        $erreur='Veuillez remplir les champs';

    }

}



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title> Inscription </title>
</head>
<body>

    <?php $_GET['page'] = "inscription"; include 'header.php'; ?>

    <main>

        <form action="inscription.php" method="post"> <!-- ceation le login-->
        
            <h2 class="titre"> Inscription </h2>
    
            <label> Login </label>  <!-- creation la case de login -->
            <input type="text" name="login" id="login" placeholder=" Votre Login...." required><br> 
    
            <label> Mot de passe </label> <!-- creation la case de mot de passe -->
            <input type="password" name="password" id="password" placeholder=" Votre mot de passe ...." required><br>
    
            <label> Confirmation de mot de passe </label> 
            <input type="password" name="confirmation" id="confirmation" placeholder=" confirmation de mot de passe...."><br>
    
            <button type="submit" name="valider"> Connexion </button> 
            <?php if (!empty($erreur)){
                echo "<p>". $erreur . "</p>";
            } ?>
        </form>
    </main>

    <?php include('footer.php') ?>

    
</body>
</html>