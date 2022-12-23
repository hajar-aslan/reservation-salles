<?php 
  
  require ('config.php');
  require ('auth.php');


  if (!$userConnected) {
    header('Location: index.php');
  }
  
  $user_id = $_SESSION['id'];

  $user_result = mysqli_query($connexion, "SELECT `login`, `password` FROM `utilisateurs` WHERE id = '$user_id'");

  $user = mysqli_fetch_assoc($user_result);


 // echo json_decode($user_id);

  $user_login = $user['login'];
  $user_password = $user['password'];

  // var_dump($user);

?>

<!DOCTYPE html>

<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title> Profil </title>
</head>
<body>

    <?php $_GET['page'] = "profil"; include('header.php') ?>
    
    <main>
        <?php
            if(isset($_GET['erreur'])){
                $err = $_GET['erreur'];
                if($err==1){
                    echo "<p style='color:red'>Utilisateur déjà créé, ou login déjà pris</p>";
                }
                if($err==2){
                    echo "<p style='color:red'>Mots de passe différents</p>";
                }
                if($err==3){
                    echo "<p style='color:red'>Veuillez remplir tous les champs</p>";
                }
            }
        ?>
        <?php
        if(isset($_POST['login']) && isset($_POST['password']) && isset($_POST['confirmation'])){
            $login = mysqli_real_escape_string($connexion,htmlspecialchars($_POST['login']));
            $password = mysqli_real_escape_string($connexion,htmlspecialchars($_POST['password']));
            $confirmation = mysqli_real_escape_string($connexion,htmlspecialchars($_POST['confirmation']));

            if($login !== "" && $password !== "" && $confirmation !== ""){
                if($password == $confirmation){
                    $requete = "SELECT count(*) FROM utilisateurs where login = '".$login."'";
                    $exec_requete = $connexion -> query($requete);
                    $reponse      = mysqli_fetch_array($exec_requete);
                    $count = $reponse['count(*)'];

                    if($count==0 || $login == $user_login){
                        $password = password_hash($password, PASSWORD_DEFAULT);
                        $requete = "UPDATE `utilisateurs` SET `login` = '$login', `password` = '$password' WHERE id = '$user_id'";
                        $exec_requete = $connexion -> query($requete);
                        echo "<p style='color:green'>Profil modifié</p>";

                        // header('Location: connexion.php');
                    }
                    else{
                        header('Location: profil.php?erreur=1'); // utilisateur déjà existant
                    }
                }
                else{
                    header('Location: profil.php?erreur=2'); // mot de passe différent
                }
            }
            else{
                header('Location: profil.php?erreur=3'); // utilisateur ou mot de passe vide
            }
        }

        mysqli_close($connexion); // fermer la connexion

        ?>

        <form action="" method="post"> <!-- ceation le login-->
        
            <h2 class="titre"> Modifier Profil </h2>
        
            <label> Login </label>  <!-- creation la case de login -->
            <input type="text" name="login" id="login" placeholder=" Votre Login...." value="<?php echo $user_login ?>" <?php echo $user_login == "admin" ? 'disabled' : '' ?>><br> 
        
            <label> Mot de passe </label> <!-- creation la case de mot de passe -->
            <input type="password" name="password" id="password" placeholder=" Votre mot de passe ...."><br>
        
            <label> Confirmation de mot de passe </label> 
            <input type="password" name="confirmation" id="confirmation" placeholder=" confirmation de mot de passe...."><br>
        
            <button name="valider"> Modifier Profil </button>   
        </form>
    </main>
   
   
   
    <?php include('footer.php') ?>


</body>
</html>