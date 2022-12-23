<?php 
$servername = 'localhost';
$username = 'root';
$password = '';
$database = 'reservationsalles';


// on établit la connexion 
$connexion = mysqli_connect($servername, $username, $password, $database);
            
//On vérifie la connexion
if(!$connexion) {
    die('Erreur : ' .mysqli_connect_error());
 }
 // echo 'Connexion réussie';
 // on ferme la connexion 
 //mysqli_close($connexion);


?>
