<?php
/*
Auteur : Christian Russo
Classe : I.FA-P3A
Date : 2ème semestre année terminale 2019-2020
Projet : Facebook like en php pour le module m152
Version : 1.0
Description : Fichier contenant toutes les fonctions nécessaires à la connection à la bdd et au bon fonctionnement du programme
*/
//Crée la connection vers la bdd
function createConnection($servername, $dbname, $username, $password) {
    try {
		$dbConnect = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
		$dbConnect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	} catch (Exception $e) {
		die("Impossible de se connecter à la base : " . $e->getMessage());
	}

	return $dbConnect;
}
//Insère un nouveau post
function insertNewPost($connection, $commentaire) {

	$lastId = 0;
	
		$sql = "INSERT INTO posts (commentaire)
		VALUES ('$commentaire')";
		$connection->exec($sql);

		if ($lastId == "") {
			$lastId = $connection->lastInsertId();
		}
		$connection->commit();

		return $lastId;
}
//Insère un média à partir d'un idPost
function insertMediaByPost($connection, $data, $lastId, $numberInList) {

	$dossier = './medias/';
	$extensions = array('.png', '.gif', '.jpg', '.jpeg', '.PNG', '.GIF', '.JPG', '.JPEG', '.mp3', '.avi', '.mp4');

	$filename =  $lastId . "_" . $data['media']['name'][$numberInList];
			$extension = strrchr($data['media']['name'][$numberInList], '.');
			$mediaType = $data['media']['type'][$numberInList];
			if (in_array($extension, $extensions)) //Si l'extension n'est pas dans le tableau
	
				move_uploaded_file($data['media']['tmp_name'][$numberInList], $dossier . $filename);
			
			$sql = "INSERT INTO medias (typeMedia, nomMedia, idPosts)
				VALUES ('$mediaType', '$filename', '$lastId')";
	
			$connection->exec($sql);
}
//Récupère tous les posts dans la bdd
function getAllPostInDb($connection) {
	$sql = $connection->prepare("SELECT idPosts, commentaire, creationDate, modificationDate FROM posts ORDER BY creationDate DESC");
	$sql->execute();
	$posts = $sql->fetchAll();

	return $posts;
}
//Récupère tous les médias dans la bdd
function getAllMediaInDb($connection) {
	$sql = $connection->prepare("SELECT medias.idPosts, medias.nomMedia, medias.typeMedia  FROM posts, medias WHERE medias.idPosts = posts.idPosts");
	$sql->execute();
	$media = $sql->fetchAll();

	return $media;
}
//Supprime un post
function deletePost($connection, $idPost) {

	$sql = $connection->prepare("SELECT nomMedia from medias where idPosts = '".$idPost."'");   
    $sql->execute();
    $allFiles = $sql->fetchAll();
    foreach ($allFiles as $key => $files) {   
		unlink("medias/" . $files['nomMedia']);
	}
	$sql = "DELETE from posts where idPosts = '".$idPost."'";
	$connection->exec($sql);
    $connection->commit();
}
//Met à jour le commentaire d'un post
function updatePostName($connection, $idPost, $newCommentaire) {
	$sql = $connection->prepare("UPDATE posts set commentaire = '".$newCommentaire."' where idPosts = '".$idPost."'");   
	$sql->execute();

}
//Récupère un post à partir d'un id
function getPostById($connection, $idPost) {
	$sql = $connection->prepare("SELECT commentaire from posts where idPosts = '".$idPost."'");   
    $sql->execute();
	$fetch = $sql->fetch();
	
	return $fetch;
}
//Récupère tous les médias d'un post
function getAllMediaByIdPost($connection, $idPost) {
	$sql = $connection->prepare("SELECT idMedias, nomMedia, typeMedia from medias where idPosts = '".$idPost."'");   
    $sql->execute();
	$fetchall = $sql->fetchAll();
	
	return $fetchall;
}
//Récupère un média à partir d'un id
function getMediaById($connection, $idMedia) {
	$sql = $connection->prepare("SELECT nomMedia from medias where idMedias = '".$idMedia."'");   
    $sql->execute();
	$media = $sql->fetchAll();
	
	return $media;
}
//Supprime un média à partir d'un id
function deleteMediaById($connection, $idMedia) {

	$sql = "DELETE from medias where idMedias = '".$idMedia."'";   
    $connection->exec($sql);
	$connection->commit();
	
}

?>