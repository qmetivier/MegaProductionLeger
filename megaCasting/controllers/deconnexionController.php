<?php 

include '../services/requeteSql.php';

//Déconnecte l'utilisateur
requeteSql::Deconnexion();

//on redirige l'utilisateur vers la page d'accueil
header("Location: accueilController.php");

 ?>