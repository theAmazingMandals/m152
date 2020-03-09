<?php
session_start();
require_once('/DbFunctions.php');
$servername = "localhost";
$username = "m152";
$password = "Super";
$dbname = "m152";
$idMedia = filter_input(INPUT_GET, "id", FILTER_SANITIZE_STRING);

$dbConnect = createConnection($servername, $dbname, $username, $password);

try {
    $dbConnect->beginTransaction();
    
    $media = getMediaById($dbConnect, $idMedia);

    foreach ($media as $key => $files) {   
        
        unlink("medias/" . $files['nomMedia']);
    }
    
    deleteMediaById($dbConnect, $idMedia);
    
} catch (Exception $e) {
$dbConnect->rollback();
throw $e;
}

header('Location: Modifier.php?id=' . $_SESSION['idPost']);
exit;
?>