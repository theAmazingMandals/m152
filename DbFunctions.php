<?php
function createConnection($servername, $dbname, $username, $password) {
    try {
		$dbConnect = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
		$dbConnect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	} catch (Exception $e) {
		die("Impossible de se connecter à la base : " . $e->getMessage());
	}

	return $dbConnect;
}

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
function getAllPostInDb($connection) {
	$sql = $connection->prepare("SELECT idPosts, commentaire, creationDate, modificationDate FROM posts ORDER BY creationDate DESC");
	$sql->execute();
	$posts = $sql->fetchAll();

	return $posts;
}
function getAllMediaInDb($connection) {
	$sql = $connection->prepare("SELECT medias.idPosts, medias.nomMedia, medias.typeMedia  FROM posts, medias WHERE medias.idPosts = posts.idPosts");
	$sql->execute();
	$media = $sql->fetchAll();

	return $media;
}
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
function updatePostName($connection, $idPost, $newCommentaire) {
	$sql = $connection->prepare("UPDATE posts set commentaire = '".$newCommentaire."' where idPosts = '".$idPost."'");   
	$sql->execute();

}
function getPostById($connection, $idPost) {
	$sql = $connection->prepare("SELECT commentaire from posts where idPosts = '".$idPost."'");   
    $sql->execute();
	$fetch = $sql->fetch();
	
	return $fetch;
}
function getAllMediaByIdPost($connection, $idPost) {
	$sql = $connection->prepare("SELECT idMedias, nomMedia, typeMedia from medias where idPosts = '".$idPost."'");   
    $sql->execute();
	$fetchall = $sql->fetchAll();
	
	return $fetchall;
}
function getMediaById($connection, $idMedia) {
	$sql = $connection->prepare("SELECT nomMedia from medias where idMedias = '".$idMedia."'");   
    $sql->execute();
	$media = $sql->fetchAll();
	
	return $media;
}
function deleteMediaById($connection, $idMedia) {

	$sql = "DELETE from medias where idMedias = '".$idMedia."'";   
    $connection->exec($sql);
	$connection->commit();
	
}

?>