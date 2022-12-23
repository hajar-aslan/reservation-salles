<?php

include('config.php');
include('auth.php');

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title> Accueil - réservation de salles </title>
</head>
<body>

    <?php $_GET['page'] = "index"; include('header.php') ?>

    <main>
        <?php if ($userConnected) : ?>
        <h1>Bonjour, <?= $user['login'] ?></h1>

        <?php else: ?>

            <img class="emoji" src="hajar_emoji.jpg" alt="Emoji de Hajar" />
        <h1 class="title">Bienvenue à reservation-salles</h1>
        <p>Ce site vous permettra de réserver une salle pour un événement</p>
        <a href="connexion.php"><p class="subtitle">Connectez-vous pour acceder au site</p></a>
        <p>Ou</p>
        <a href="livre-or.php"> Visiter réservation de salles</a>
        
        <?php endif; ?>

    </main>

    <?php include('footer.php') ?>
</body>
</html>