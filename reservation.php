<?php

require ('config.php');
require ('auth.php');


if(!isset($_GET['id'])){
    header('Location: index.php');
}   


$reservation_id = $_GET['id'];


$resultat = mysqli_query($connexion, "SELECT * FROM `reservations` INNER JOIN `utilisateurs` ON reservations.id_utilisateur = utilisateurs.id WHERE reservations.id = '$reservation_id'");

$res = mysqli_fetch_all($resultat, MYSQLI_ASSOC);

// $today = date('Y-m-d'); //return eg. '2022-12-17' 

// var_dump($res);
?><!DOCTYPE html>

<html lang="fr">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="style.css">
        <title> Reservation </title>
    </head>

    <body>

        <?php $_GET['page'] = "reservation"; include('header.php'); ?>

        <main>
            <h2 class="titre"> réservation </h2>
            
            <?php foreach ($res as $value) : ?>
                
                <div class="reservation">
                    <p> Resérvé par <?php echo $value['login'] ?> le <?php echo $value['titre'] ?></p>
                    <p> <?php echo($value['description']) ?> </p>
                    <p> <?php echo($value['debut']) ?> </p>
                    <p> <?php echo($value['fin']) ?> </p>
                </div>
                
            <?php endforeach; ?>
            
        </main>    
    
        <?php include('footer.php') ?>


    </body>
</html>