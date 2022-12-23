<?php

require 'config.php';
require 'auth.php';

// définir une variable de `fuseau horaire
$timezone = 'Europe/Paris';
// Définissez le fuseau horaire par défaut des fonctions `date ()` et `time ()`
date_default_timezone_set($timezone);


// s'il n'y a pas de variable `week` dans notre recherche d'URL
if (!isset($_GET['week'])) {
    // Obtenez le numéro de semaine en cours
    $week_number = date('W'); // Renvoie par exemple.51
    // redirige vers la même page de planning avec le numéro de semaine
    header("Location: planning.php?week=$week_number");
    exit(); // <- nous sortons maintenant pour éviter les comportements indésirables
}

// Utilisez le numéro de semaine de GET
$week_number = $_GET['week']; // Renvoie par exemple.51
// Obtenez à nouveau le numéro de semaine actuel / aujourd'hui
$today_week_number = date('W');

// Calculez le numéro de la semaine précédente
$prev_week_number = $week_number - 1;
// Calculez le numéro de la semaine suivante
$next_week_number = $week_number + 1;

// Tout d'abord, définissons notre variable `year_number`
$year_number = date('Y'); // Renvoie par exemple. 2022

// Maintenant, créez un nouvel objet DateTime comme "dateObj" 
$dateObj = new DateTime(); // <- cela par défaut "maintenant"
// Réglez le fuseau horaire de notre $ dateoBj à «Europe / Paris»
$dateObj->setTimezone(new DateTimeZone($timezone));

// prendre la date du premier jour ou lundi de la semaine de l'année, en utilisant la méthode `setISODate ()`
$dateObj->setISODate($year_number, $week_number);

// Obtenez la date de début de $dateObj dans le format de type 'YYYY-MM-DD'
$start_date = $dateObj->format('Y-m-d');
// ajouter 6 jours à notre objet date (c'est-à-dire $ dateobj), en utilisant la méthode `modifier ()`
$dateObj->modify('+6 days');

// Obtenez la date de fin de $dateoBj dans le format de type 'YYYY-MM-DD'
$end_date = $dateObj->format('Y-m-d');

// Definir le nom de la variable de date d'aujourd'hui comme `today_date` :)
$today_date = date('Y-m-d');

// Créer un intervalle de 1 jour
$interval = DateInterval::createFromDateString('+1 day');
// utilise l'intervalle pour générer une liste de dates,
// et nommez la liste des dates du début à la fin comme «daterange»
$daterange = new DatePeriod(date_create($start_date), $interval, date_create($end_date . '+1 day'));

// Créer un tableau de dates réels
$real_dates = []; // retourne par exemple. ['2022-12-19', '2022-12-20', '2022-12-21', ...
// Créer un tableau de dates courtes 
$short_dates = []; // Renvoie par exemple. ['19', '20', '23', ...

$days_fr = ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'];

// pour chaque date actuelle dans `daterange`
foreach ($daterange as $date) {
    // Ajouter des dates avec le format de type 'YYYY-mm-dd à la liste des `real_dates'
    $real_dates[] = $date->format('Y-m-d');
    // ajouter des dates courtes avec à la liste des `real_dates`
    // $ short_dates [] = $ date-> format ('. d');
    $short_dates[] = $date->format('. d');
}

// Récuperer toutes les réservations de notre base de données entre 'start_date' et 'end_date' ...

$start_datetime = $start_date . " 00:00:00"; // Renvoie par exemple. '2022-12-19 00:00:00'
$end_datetime = $end_date . " 00:00:00"; // Renvoie par exemple. '2022-12-25 00:00:00'

$query = "SELECT `reservations`.id, `titre`, `description`, `debut`, `fin`, `id_utilisateur`, `login` FROM `reservations` 
INNER JOIN  `utilisateurs`
ON reservations.id_utilisateur = utilisateurs.id
WHERE debut >= '$start_datetime' AND fin <= '$end_datetime'";
// $ query = "SELECT * FROM 'Reservations`";
$resultat = mysqli_query($connexion, $query);

$reservations = mysqli_fetch_all($resultat, MYSQLI_ASSOC);


/**
 * Vérifie si la date donnée est un samedi ou un dimanche.
 *
 * @param string $ date - Exemple: '2022-12-12'
 * @return bool - revient vrai si la date est «SAT» ou «Soleil»
 */
function weekend($date) {
    // Obtenons le nom du jour
    $day = date('D', strtotime($date)); // Renvoie par exemple.'Lun'

    return ($day == 'Sat' || $day == 'Sun');
}


/**
 * Recupère la réservation avec la date donnée.
 *
 * @param string $ DateTime - Exemple: '2022-12-12 10:00:00'
 * @param array $ réservations - La liste des réservations
 * 
 * @return array - La réservation spécifique en tant que tableau associatif (par exemple. ['Titre' => "Petit dej", 'Login' => "Katia" ...])
 */
function recupReservationParDate($datetime, $reservations) {
    // créer une variable de résultat
    $result = [];

    // pour chaque réservation de la liste des «réservations»...
    foreach ($reservations as $reservation) {
        // ...Si notre datetime est grand ou égal à «débuts» de la réservation et moins ou égale à «fin»...
        if ($datetime >= $reservation['debut'] && $datetime <= $reservation['fin']) {
            // ...ajouter la réservation à la liste de résultats
            $result[] = $reservation;

            // arrêtez immédiatement la boucle foreach
            break;
        }
    }

    // retourner le résultat
    return $result;
}


mysqli_close($connexion); // fermer la connexion


?>

<!DOCTYPE html>

<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="planning.css">

    <title> Planning </title>
</head>

<body>

    <?php $_GET['page'] = "planning"; include('header.php'); ?>
    
    <main>
        <div class="controls">
            <a href="?week=<?= $prev_week_number ?>"><button>La semaine précédente</button></a>
            <a href="?week=<?= $today_week_number ?>"><button>Aujourd'hui</button></a>
            <a href="?week=<?= $next_week_number ?>"><button>La semaine prochaine</button></a>
        </div>

        <table class="calender">

            <thead>
                <tr>
                    <td>Horraires</td>

                    <?php foreach($short_dates as $index => $day):?>
                    <td <?= ($real_dates[$index] == $today_date) ? 'active' : '' ?> ><?= $days_fr[$index] . $day ?></td>
                    <?php endforeach; ?>
                </tr>
            </thead>

            <tbody>

                <?php for($h = 8; $h < 19; $h++): ?>
                <tr>
                    <td><?= $h . "h - " . ($h + 1) . "h" ?></td>
        
                    <?php for ($j = 0; $j < 7; $j++):?>



                        <?php if (!weekend($real_dates[$j])) : ?>

                            <td>      

                                <?php

                                $hour = ($h < 10) ? "0$h" : $h; // returns eg. '09' if $h is 9

                                $date = $real_dates[$j];
                                $time = $hour .":00:00";

                                
                                $datetime = $date . " " . $time;

                                // Obtenez la réservation actuelle avec ce «datetime»
                                $found_reservation = recupReservationParDate($datetime, $reservations);
                                
                                ?>


                                <?php if ($found_reservation) : ?>
                                
                                    <a href="reservation.php?id=<?= $found_reservation[0]['id'] ?>" title="<?= $datetime ?>">
                                        <span class="login"><?= $found_reservation[0]['login'] ?></span>
                                        <span class="titre"><?= $found_reservation[0]['titre'] ?></span>
                                    </a>

                                <?php elseif ($userConnected) : ?>
                                    <a href="reservation-form.php?date=<?= $date ?>&time=<?= $time ?>" class="empty-res" title="<?= $datetime ?>">
                                        <p>Réserver</p>
                                    </a>
                                <?php endif; ?>

                            </td>

                            
                        <?php else : ?>
                            
                        <td disabled>reservation non disponible</td>
                        
                        <?php endif; ?>
                                
            
                            
                    <?php endfor; ?>
        
                </tr>
                <?php endfor; ?>

            </tbody>
            
        </table>

     

    </main>    

    <?php include('footer.php') ?>

</body>
</html>