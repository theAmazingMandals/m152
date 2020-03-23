<?php
/*
Auteur : Christian Russo
Classe : I.FA-P3A
Date : 2ème semestre année terminale 2019-2020
Projet : Facebook like en php pour le module m152
Version : 1.0
Description : Fichier supprimant un post choisi à partir de la page d'accueil
*/
//Nécessite le fichier des fonctions
require_once('/DbFunctions.php');
//Déclaration des variables de connections à la bdd et de récupération de l'id
$servername = "localhost";
$username = "m152";
$password = "Super";
$dbname = "m152";
$idPost = filter_input(INPUT_GET, "id", FILTER_SANITIZE_STRING);
//Créer la connection
$dbConnect = createConnection($servername, $dbname, $username, $password);

try {
    //Comment la transaction
    $dbConnect->beginTransaction();  
    //Appelle le fonction supprimant le post 
    deletePost($dbConnect, $idPost);

} catch (Exception $e) {
    //Produit un rollback si cela ne fonctionne pas
$dbConnect->rollback();
throw $e;
}
//Redirige sur home.php
header('Location: Home.php');
exit;
?>