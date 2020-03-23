<?php
/*
Auteur : Christian Russo
Classe : I.FA-P3A
Date : 2ème semestre année terminale 2019-2020
Projet : Facebook like en php pour le module m152
Version : 1.0
Description : Fichier supprimant un média séléctionné sur le disque et dans la bdd à partir de l'id choisi sur la page de modification de post
*/
//Commence la session
session_start();
//Nécessite le fichier de fonction
require_once('/DbFunctions.php');
//Déclaration des variables de connection
$servername = "localhost";
$username = "m152";
$password = "Super";
$dbname = "m152";
//Récupère l'id en get
$idMedia = filter_input(INPUT_GET, "id", FILTER_SANITIZE_STRING);
//Crée la connection
$dbConnect = createConnection($servername, $dbname, $username, $password);

try {
    //Commence la transaction
    $dbConnect->beginTransaction();
    //Récupère le média à partir de l'id média
    $media = getMediaById($dbConnect, $idMedia);

    foreach ($media as $key => $files) {   
        //Supprime le média sur le disque
        unlink("medias/" . $files['nomMedia']);
    }
    //Supprime le media dans la base
    deleteMediaById($dbConnect, $idMedia);
    
} catch (Exception $e) {
//Effectue un rollback
$dbConnect->rollback();
throw $e;
}
//Redirige vers la page de modification du post
header('Location: Modifier.php?id=' . $_SESSION['idPost']);
exit;
?>