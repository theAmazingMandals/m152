<?php
session_start();
$servername = "localhost";
$username = "m152";
$password = "Super";
$dbname = "m152";
$idMedia = filter_input(INPUT_GET, "id", FILTER_SANITIZE_STRING);

try {
    $dbConnect = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $dbConnect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    die("Impossible de se connecter à la base : " . $e->getMessage());
}

try {
    $dbConnect->beginTransaction();
    $sql = $dbConnect->prepare("SELECT nomMedia from medias where idMedias = '".$idMedia."'");   
    $sql->execute();
    $allFiles = $sql->fetchAll();
    foreach ($allFiles as $key => $files) {   
        
        unlink("medias/" . $files['nomMedia']);
    }
    
    
    $sql = "DELETE from medias where idMedias = '".$idMedia."'";
    
    $dbConnect->exec($sql);

    

    $dbConnect->commit();
    
} catch (Exception $e) {
$dbConnect->rollback();
throw $e;
}

header('Location: Modifier.php?id=' . $_SESSION['idPost']);
exit;
?>