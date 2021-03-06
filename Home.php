<?php
/*
Auteur : Christian Russo
Classe : I.FA-P3A
Date : 2ème semestre année terminale 2019-2020
Projet : Facebook like en php pour le module m152
Version : 1.0
Description : Page Home qui sert de page d'accueil et affiche tous les posts se trouvant dans la bdd
*/
//Nécessite le fichier de fonctions
require_once('/DbFunctions.php');
//Déclaration des variables de connection
$servername = "localhost";
$username = "m152";
$password = "Super";
$dbname = "m152";
//Déclaration des tableaux de type de médias
$extensionsImage = array('image/png', 'image/gif', 'image/jpg', 'image/jpeg', 'image/PNG', 'image/GIF', 'image/JPG', 'image/JPEG');
$extensionsSon = array('audio/mp3');
$extensionsVideo = array( 'video/avi', 'video/mp4');

//Créer la connection
$dbConnect = createConnection($servername, $dbname, $username, $password);
//Récupère tous les posts de la dbb
$allPosts = getAllPostInDb($dbConnect);
//Récupère tous les média de la db
$allMedias = getAllMediaInDb($dbConnect);
//Ferme la connection
$dbConnect = null;

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
									<a href="#"><i class="glyphicon glyphicon-home"></i> Home</a>
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
											<h1>WELCOME</h1>
										</div>	

									</div>
									
									<?php
									//Pour chaque post que l'on a récupérer
									foreach ($allPosts as $key => $data) {
										//On récupère son id, son commentaire et ses dates de création/modification
										$idPosts = $data['idPosts'];
										$commentaire = $data['commentaire'];
										$creationDate = $data['creationDate'];
										$modificationDate = $data['modificationDate'];
										//On affiche les balises avec le commentaire et la date de création
										echo "<div class=\"panel panel-default\">
										
										<h2 style=\"margin-left: 10px;\">$commentaire | $creationDate</h2>";
										//Pour tous les média que l'on a récupérer
										foreach ($allMedias as $key => $data) {
											//On récupère son nom, son type et sa clé étrangère
											$nomMedia = $data['nomMedia'];
											$typeMedia = $data['typeMedia'];
											$FKidPosts = $data['idPosts'];
											//Si l'id du post actuel est égal à la clé étrangère affiche le média selon son type
											if ($idPosts == $FKidPosts) {
												if (in_array($typeMedia, $extensionsImage)) {
													echo "<img style=\"margin-left: 10px;\" width=\"400\" height=\"400\" src=\"medias/$nomMedia\" alt=\"post\" ><br>";
												}
												elseif (in_array($typeMedia, $extensionsVideo)){
													echo "<video style=\"margin-left: 10px;\" width=\"400\" height=\"400\" loop=\"true\" controls autoplay>
													<source src=\"medias/$nomMedia\" type=\"$typeMedia\">			  
												  </video><br>";
												}
												elseif (in_array($typeMedia, $extensionsSon)) {
													echo "<audio style=\"margin-left: 10px;\" controls>
													<source src=\"medias/$nomMedia\" type=\"$typeMedia\">			  
												  </audio><br>";
												}
												
											}
										}
										
										//Affiche les boutons qui font lien sur la modification et la suppression du post
										echo "<div class=\"panel-body\">
										<a href=\"Supprimer.php?id=$idPosts\"><img src=\"./assets/img/supprimer.png\" alt=\"Supprimer\"/></a>
										<a href=\"Modifier.php?id=$idPosts\"><img src=\"./assets/img/modifier.png\" alt=\"Modifier\"/></a>
										
										</div>
									  </div>";
									}

									
									?>
									
								</div>
							</div>
							<!--/row-->



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

	<script type="text/javascript" src="assets/js/jquery.js"></script>
	<script type="text/javascript" src="assets/js/bootstrap.js"></script>
	<script type="text/javascript">
		$(document).ready(function() {
			$('[data-toggle=offcanvas]').click(function() {
				$(this).toggleClass('visible-xs text-center');
				$(this).find('i').toggleClass('glyphicon-chevron-right glyphicon-chevron-left');
				$('.row-offcanvas').toggleClass('active');
				$('#lg-menu').toggleClass('hidden-xs').toggleClass('visible-xs');
				$('#xs-menu').toggleClass('visible-xs').toggleClass('hidden-xs');
				$('#btnShow').toggle();
			});
		});
	</script>
</body>

</html>