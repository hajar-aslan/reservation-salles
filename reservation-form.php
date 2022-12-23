<?php   

require 'config.php';
require 'auth.php';

// si l'utilisateur n'est pas connecter
if (!$userConnected) {
    // redirige le vers la page d'accueil
    header('Location: index.php');
}

$register=false;
$erreur = '';

date_default_timezone_set('Europe/Paris');

$date_du_jour = date('Y-m-d');
$heure_du_jour = date('H');

// echo "date_du_jour => $date_du_jour";
// echo "heure_du_jour => $heure_du_jour";

if (isset($_POST['valider'])){
    function validate ($data){
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    $titre = validate($_POST['titre']);
    $debut = $_POST['debut'];
    $fin = $_POST['fin'];
    $date = $_POST['date'];
    $description = validate($_POST['description']);
    $id_utilisateur = $id;


    
    if ($titre != null && $debut != null && $fin != null && $date != null && $description != null){  // si tous les champs sont remplis
        
        $debut_date = "$date $debut:00:00";
        $fin_date = "$date $fin:00:00";
        $jour_date = "$date_du_jour $heure_du_jour:00:00";

        $jour_debut = date('D', strtotime($debut_date));

        // echo "<br> <br>---------------------------------- <br>";
        // echo "jour_debut => $jour_debut <br>";
        // echo "debut_date => $debut_date <br>";
        // echo "fin_date => $fin_date <br>";
        // echo "jour_date => $jour_date <br>";
        // echo "---------------------------------- <br>";
        // echo "debut_date >= jour_date ? ". json_encode($debut_date >= $jour_date) . "<br>";
        // echo "debut_date < fin_date ? ". json_encode($debut_date < $fin_date) . "<br>";



        if (($debut_date >= $jour_date) && ($debut_date < $fin_date)) {

            if ($jour_debut == 'Sat' || $jour_debut == 'Sun') {
                $erreur = "Vous ne pouvez pas crée une réservation les weekends";

            }else {
                $requete_search = "SELECT COUNT(*) FROM `reservations` WHERE '$debut_date' BETWEEN debut AND fin";
    
                $resultat = mysqli_query($connexion, $requete_search);
                $reservations = mysqli_fetch_assoc($resultat);
                $count = $reservations['COUNT(*)'];
                $reservations_existe_deja = $count != '0';
                
                
                // echo "debut_date => $debut_date <br>";
                // echo "fin => $fin <br>";
                // echo "reservation_existe_deja => " . json_encode($reservations_existe_deja) . "<br>";
                // echo "count => $count" . "<br>";
                // echo "reservations : ";
                // var_dump($reservations);
    
    
                // si la reservation n'existe pas deja
                if (!$reservations_existe_deja) {
                    // preparer notre requete
                    $stmt = $connexion->prepare("INSERT INTO `reservations` (`titre`, `description`, `debut`, `fin`, `id_utilisateur`) VALUES (?, ?, ?, ?, ?)");
                    // executer notre requete pour inserer une nouvelle reservation dans notre base de donnée 
                    $stmt->bind_param("sssss", $titre, $description, $debut_date, $fin_date, $id_utilisateur);
                    $stmt->execute();
                    // Rafraîchir la page avec l'argument reussit
                    header('Location: reservation-form.php?reussit');
                    die();
                    
                }else {
                    $erreur='Il y a deja une réservation entre ' . $debut . ' et ' . $fin;
                }


            }    
            

        }else {
            $erreur='Erreur de réservation: créneaux invalide';
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
    <title> Formulaire de réservation </title>
</head>
<body>

    <?php $_GET['page'] = "reservation-form"; include 'header.php'; ?>

    <main>

        <form action="reservation-form.php" method="post">
            <?php if (!empty($erreur)){ echo "<p class='erreur'>". $erreur . "</p>"; } ?>
            <?php if (isset($_GET['reussit'])){ echo "<p class='reussit'>Votre réservation a été enregistrée</p>"; } ?>
        
            <h2 class="titre"> Formulaire de réservation </h2>
            <h4>Utilisateur: <span class="login"><?= $login ?></span></h4>

            <label for="titre"> Titre: </label>  
            <input type="text" name="titre" id="titre" required><br> 
    
            <label for="debut"> Heure de début: </label> 
            <select name="debut" id="debut" required>
                <?php for ($h = 8; $h <= 18; $h++) : ?>
                <option value="<?= $h < 10 ? "0{$h}" : $h?>"><?= $h < 10 ? "0{$h}" : $h ?> h</option>
                <?php endfor; ?>
            </select>

            <label for="fin"> Heure de fin: </label> 
            <select name="fin" id="fin" required>
                <?php for ($h = 9; $h <= 19; $h++) : ?>
                <option value="<?= $h < 10 ? "0{$h}" : $h?>"><?= $h < 10 ? "0{$h}" : $h ?> h</option>
                <?php endfor; ?>
            </select> <br> <br>

            <label for="date"> Date: </label> 
            <input type="date" name="date" id="date" min="<?= $date_du_jour ?>" required><br>
    
            <label for="description"> Description: </label> 
            <textarea id="description" name="description" minlength="10" maxlength="250" required></textarea>
    
            <button type="submit" name="valider"> Soummettre ma réservation </button> 
            
        </form>
    </main>

    <?php include('footer.php') ?>

    
</body>
</html>