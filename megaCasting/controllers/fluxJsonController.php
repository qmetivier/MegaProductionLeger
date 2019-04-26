<?php

include '../services/requeteSql.php';
include '../services/function.php';

//Verifie que la personne se connecte bien avec des info en Methode POST
if (!isset($_POST["identifiant"]) && !isset($_POST["password"])) echo "Error Request";
else{
	if (requeteSql::getPartenaire($_POST["identifiant"], $_POST["password"])) {
		//on récupère les offres de castings
		$content = requeteSql::getAlloffres();
		//On affiche sous forme Json la requete;
		echo( json_encode($content));
	}else
}


 ?>