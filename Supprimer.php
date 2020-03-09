<?php
require_once('/DbFunctions.php');

$servername = "localhost";
$username = "m152";
$password = "Super";
$dbname = "m152";
$idPost = filter_input(INPUT_GET, "id", FILTER_SANITIZE_STRING);

$dbConnect = createConnection($servername, $dbname, $username, $password);

try {
    $dbConnect->beginTransaction();   
    deletePost($dbConnect, $idPost);

} catch (Exception $e) {
$dbConnect->rollback();
throw $e;
}

header('Location: Home.php');
exit;
?>