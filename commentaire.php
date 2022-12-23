<?php 
  
require ('config.php');
require ('auth.php');


if (!$userConnected) {
header('Location: index.php');
}

$user_id = $_SESSION['id'];

date_default_timezone_set('Europe/Paris');
$date = date('Y-m-d H:i:s');

// echo "date = $date";

?>

<!DOCTYPE html>

<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title> Commentaire </title>
</head>
<body>

    <?php $_GET['page'] = "commentaire"; include('header.php') ?>
    
    <main>
        <?php 
         
        if(isset($_POST['commentaire'])){
            $commentaire = mysqli_real_escape_string($connexion,htmlspecialchars($_POST['commentaire']));

            $requete = "INSERT INTO `commentaires` (`commentaire`, `id_utilisateur`, `date`) VALUES ('$commentaire', '$user_id', '$date')";
            $exec_requete = $connexion->query($requete);

            header('Location: commentaire.php?reussit');
        }


        if(isset($_GET['reussit'])){
            echo "<p style='color:green'>Commentaire ajouté</p>";
        }

        mysqli_close($connexion); // fermer la connexion

        ?>   

        <form action="" method="post"> <!-- ceation le login-->
        
            <h2 class="titre"> Ajouter un commentaire </h2>
        
            <textarea type="text" name="commentaire" id="commentaire" required autofocus></textarea>
                
            <button name="valider"> Envoyer </button>   
        </form>
    </main>
   
   
   
    <?php // include('footer.php') ?>


</body>
</html>