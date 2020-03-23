<?php
/*
Auteur : Christian Russo
Classe : I.FA-P3A
Date : 2ème semestre année terminale 2019-2020
Projet : Facebook like en php pour le module m152
Version : 1.0
Description : Fichier proposant de modifier le post choisi dans la page d'accueil, possibilités de modifier le texte, supprimer ou ajouter un média.
*/
//Commence la session
session_start();
//Nécessite le fichier des fonctions
require_once('/DbFunctions.php');
//Déclaration des tableau des types de média
$extensionsImage = array('image/png', 'image/gif', 'image/jpg', 'image/jpeg', 'image/PNG', 'image/GIF', 'image/JPG', 'image/JPEG');
$extensionsSon = array('audio/mp3');
$extensionsVideo = array( 'video/avi', 'video/mp4');
//Déclaration des variables de connection
$servername = "localhost";
$username = "m152";
$password = "Super";
$dbname = "m152";
//Récupère l'idPost
$idPost = filter_input(INPUT_GET, "id", FILTER_SANITIZE_STRING);
//Récupère le nouveau commentaire
$newCommentaire = filter_input(INPUT_POST, "commentaire", FILTER_SANITIZE_STRING);
//Stocke idPost en session
$_SESSION['idPost'] = $idPost;

//Crée la connection
$dbConnect = createConnection($servername, $dbname, $username, $password);

if (isset($_POST)) {

	//Si il y a eu des modifications
	if ($newCommentaire != "" || $_FILES['media'] != null) {
		
	//Compte le nombre de média
	if ($_FILES['media']['name'][0] != "") {
		$countfiles = count($_FILES['media']['name']);
	}
	else {
		$countfiles = 0;
	}
	

	try {
		//Commence la transaction
		$dbConnect->beginTransaction();
		//Met à jour le commentaire du post
    	updatePostName($dbConnect, $idPost, $newCommentaire);
		//Si il y a un média à ajouter
		if ($countfiles > 0) {
			//Pour chaque média
			for ($i = 0; $i < $countfiles; $i++) {
				//Ajoute le média au post correspondant
				insertMediaByPost($dbConnect, $_FILES, $idPost, $i);
			}
		}
		//Applique les changements et redirige vers home.php
		$dbConnect->commit();
		header('Location: Home.php');
		exit;
		} catch (Exception $e) {
			$dbConnect->rollback();
			throw $e;
		} 
	}	  
}
	//récupère le post avec l'id pris en get
	$post = getPostById($dbConnect, $idPost);
	//Récupère tous les médias du $post
	$allMediaInPost = getAllMediaByIdPost($dbConnect, $idPost); 
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<meta charset="utf-8">
	<title>Facebook Theme Demo</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<link href="assets/css/bootstrap.css" rel="stylesheet">
	<!--[if lt IE 9]>
          <script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
	<link href="assets/css/facebook.css" rel="stylesheet">
</head>

<body>

	<div class="wrapper">
		<div class="box">
			<div class="row row-offcanvas row-offcanvas-left">



				<!-- main right col -->
				<div class="column col-sm-10 col-xs-11" id="main">

					<!-- top nav -->
					<div class="navbar navbar-blue navbar-static-top">
						<div class="navbar-header">
							<button class="navbar-toggle" type="button" data-toggle="collapse" data-target=".navbar-collapse">
								<span class="sr-only">Toggle</span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
							</button>
							<a href="./Home.php" class="navbar-brand logo">b</a>
						</div>
						<nav class="collapse navbar-collapse" role="navigation">
							<form class="navbar-form navbar-left">
								<div class="input-group input-group-sm" style="max-width:360px;">
									<input class="form-control" placeholder="Search" name="srch-term" id="srch-term" type="text">
									<div class="input-group-btn">
										<button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>
									</div>
								</div>
							</form>
							<ul class="nav navbar-nav">
								<li>
									<a href="./Home.php"><i class="glyphicon glyphicon-home"></i> Home</a>
								</li>
								<li>
									<a href="./Posts.php" role="button" data-toggle="modal"><i class="glyphicon glyphicon-plus"></i> Post</a>
								</li>

							</ul>
							
						</nav>
					</div>
					<!-- /top nav -->

					<div class="padding">
						<div class="full col-sm-9">

							<!-- content -->
							<div class="row">

								<!-- main col left -->
								<div class="col-sm-5">

									<div class="panel panel-default">
										<div class="panel-thumbnail"><img src="assets/img/bg_4.jpg" class="img-responsive"></div>
										<div class="panel-body">
											<p class="lead">Christian Russo</p>
											<p>45 Followers, 13 Posts</p>

											<p>
												<img src="assets/img/uFp_tsTJboUY7kue5XAsGAs28.png" height="28px" width="28px">
											</p>
										</div>
									</div>



								</div>

								<!-- main col right -->
								<div class="col-sm-7">
									<div class="panel panel-default">

										<div class="panel-body">
											<h1>Modifier</h1>
										</div>
									</div>
									<div class="well">
										<form class="form-horizontal" method="post" action="Modifier.php?id=<?php echo $_SESSION['idPost'] ?>" enctype="multipart/form-data" role="form">
											<h4>Veuillez choisir</h4>
											<div class="form-group" style="padding:14px;">
												<textarea class="form-control" placeholder="<?php echo $post['commentaire'];?>" name="commentaire"></textarea>
                                            </div>
											<?php 
											//Pour chaque média du post affiche une image si son type est une image, un audio si c'est un type audio et une vidéo si son type est vidéo
                                            foreach ($allMediaInPost as $key => $files) {   
                                                if (in_array($files['typeMedia'], $extensionsImage)) {
													echo "<img style=\"margin-left: 10px;\" width=\"400\" height=\"400\" src=\"medias/" . $files['nomMedia'] . "\" alt=\"post\" >
													<a href=\"SupprimerMedia.php?id=" . $files['idMedias'] . "\"><img src=\"./assets/img/supprimer.png\" alt=\"Supprimer\"/></a><br>";
												
												}
												elseif (in_array($files['typeMedia'], $extensionsVideo)){
													echo "<video style=\"margin-left: 10px;\" width=\"400\" height=\"400\" loop=\"true\" controls autoplay>
													<source src=\"medias/" . $files['nomMedia'] . "\" type=\"" . $files['typeMedia'] . "\"></video>
													<a href=\"SupprimerMedia.php?id=" . $files['idMedias'] . "\"><img src	=\"./assets/img/supprimer.png\" alt=\"Supprimer\"/></a><br>";
												}
												elseif (in_array($files['typeMedia'], $extensionsSon)) {
													echo "<audio style=\"margin-left: 10px;\" controls>
													<source src=\"medias/" . $files['nomMedia'] . "\" type=\"" . $files['typeMedia']. "\"></audio>
													<a href=\"SupprimerMedia.php?id=" . $files['idMedias'] . "\"><img src=\"./assets/img/supprimer.png\" alt=\"Supprimer\"/></a><br>";
												}
                                            }
                                            ?>
											<button class="btn btn-primary pull-right" type="submit">Modifier</button>

											<div class="image-upload">
												<label for="file-input">
													<img src="./assets/img/upload.png" />
												</label>

												<input id="file-input" accept="image/*, video/*, audio/*" name="media[]" type="file" multiple="multiple"  />
											</div>




										</form>
									</div>

									
								</div>

							</div>

							<div class="row" id="footer">
								<div class="col-sm-6">

								</div>
								<div class="col-sm-6">
									<p>
										<a href="#" class="pull-right">©Copyright 2020</a>
									</p>
								</div>
							</div>

							<hr>

							<h4 class="text-center">
								
								<p>©Copyright Christian Russo</p>
							</h4>

							<hr>


						</div><!-- /col-9 -->
					</div><!-- /padding -->
				</div>
				<!-- /main -->

			</div>
		</div>
	</div>


	<!--post modal-->
	<div id="postModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">�</button>
					Update Status
				</div>
				<div class="modal-body">
					<form class="form center-block">
						<div class="form-group">
							<textarea class="form-control input-lg" autofocus="" placeholder="What do you want to share?"></textarea>
						</div>
					</form>
				</div>
				<div class="modal-footer">
					<div>
						<button class="btn btn-primary btn-sm" data-dismiss="modal" aria-hidden="true">Post</button>
						<ul class="pull-left list-inline">
							<li><a href=""><i class="glyphicon glyphicon-upload"></i></a></li>
							<li><a href=""><i class="glyphicon glyphicon-camera"></i></a></li>
							<li><a href=""><i class="glyphicon glyphicon-map-marker"></i></a></li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>


</body>

</html>