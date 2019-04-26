<?php 
session_start();
include '../services/requeteSql.php';
include '../services/function.php';

// Si le formulaire d'authentification est remplie 
if (isset($_GET["auth-name"])){
	$email = htmlentities($_GET["auth-name"]);
	$password = htmlentities($_GET["auth-pass"]);
	requeteSql::connexion($email, $password);
	$id = htmlentities($_GET["id"]);
	header("Location: infoController.php?id=$id");
}

// recupere le contenue de la vue
$page = file_get_contents('../views/info.php');

// recupère le template du header et le place dans la vue
$header = file_get_contents('../template/header.html');
$page = str_replace("||HEADER||", $header, $page);

// recupère le template du footer et le place dans la vue
$footer = file_get_contents('../template/footer.html');
$page = str_replace("||FOOTER||", $footer, $page);

//affiche le template d'authentification
$page = functions::authTemplate($page);

$scripts = "";

// récupere l'insterview et conseil rechercher de la bdd
$info = requeteSql::getSearchInfo($_GET["id"]);
// transfert l'interview et conseil du php au js
$scripts .= functions::SendVar('Info', $info);

//Charge en js le template de l'erreur 404
$Page404 = file_get_contents('../template/error404.html');
$scripts .= functions::SendVar('Page404', $Page404);

// On place toutes les function js, appeler avant, sur la vue
$page = str_replace("||SCRIPTS||", $scripts, $page);

// on affiche la vue
echo $page;

 ?>