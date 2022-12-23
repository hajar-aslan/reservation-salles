<?php
// include('bdd.php');
// include('auth.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="header.css">
    <title> Header </title>
</head>
<body>
    <?php if ($userConnected) : ?>
    <nav>
        <a href="index.php" <?php echo $_GET['page'] == 'index' ? 'active' : '' ?>>Accueil</a>
        <a href="planning.php" <?php echo $_GET['page'] == 'planning' ? 'active' : '' ?>>Planning</a>
        <a href="reservation-form.php" <?php echo $_GET['page'] == 'reservation-form' ? 'active' : '' ?>>faire une réservation</a>
        <a href="profil.php" <?php echo $_GET['page'] == 'profil' ? 'active' : '' ?>>Profil</a>
        <a href="connexion.php?deconnecter">Deconnexion</a>
    </nav>

    <?php else: ?>
    <nav>
        <a href="index.php" <?php echo $_GET['page'] == 'index' ? 'active' : '' ?>>Accueil</a>
        <a href="planning.php" <?php echo $_GET['page'] == 'planning' ? 'active' : '' ?>>Planning</a>
        <a href="inscription.php" <?php echo $_GET['page'] == 'inscription' ? 'active' : '' ?>>Inscription</a>
        <a href="connexion.php" <?php echo $_GET['page'] == 'connexion' ? 'active' : '' ?>>Connexion</a>
    </nav>
    <?php endif; ?>
</body>
</html>