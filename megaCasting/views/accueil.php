<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Acceuil</title>
	<link rel="stylesheet" type="text/css" href="../fontawesome/css/all.min.css">
	<link rel="stylesheet" type="text/css" href="../styles/index.css">
	<link rel="stylesheet" type="text/css" href="../styles/accueil.css">
</head>
<body>
	||HEADER||
	<div class="container">
		<img src="../others/megaCastingFond.jpg" class="img-fond">
		<div class="hr-black"></div>
		<div class="main main-left">
			<!-- Partie formulaire de recherche -->
			<div class="div-bar-search">
				<form class="bar-search" action="accueilController.php" method="get" divType="form">
					<input type="text" name="search" id="searchbar">
					<input type="submit" text="&#x1F50D;" class="btn-search">
					<div id="ContainerCheckBox">
						
					</div>
				</form>
			</div>
		</div>
		<div class="main main-right" id="ContainerOffres">
			<!-- Partie affichage des offres de castings -->
		</div>
	</div>
	||FOOTER||
	||SCRIPTS||
	<script type="text/javascript" src="../services/date.format.js"></script>
	<script type="text/javascript" src="../services/formulaire.js"></script>
	<script type="text/javascript" src="../services/connexion.js"></script>
	<script type="text/javascript" src="../scripts/accueil.js"></script>
</body>
</html>