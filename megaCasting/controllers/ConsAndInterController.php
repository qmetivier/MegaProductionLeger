<?php 
session_start();
include '../services/requeteSql.php';
include '../services/function.php';

// Si le formulaire d'authentification est remplie 
if (isset($_GET["auth-name"])){
	$email = htmlentities($_GET["auth-name"]);
	$password = htmlentities($_GET["auth-pass"]);
	requeteSql::connexion($email, $password);
	header("Location: consAndInterController.php");
}

$scripts = "";

// recupere le contenue de la vue
$page = file_get_contents('../views/ConsAndInter.php');

// recupère le template du header et le place dans la vue
$header = file_get_contents('../template/header.html');
$page = str_replace("||HEADER||", $header, $page);

// recupère le template du footer et le place dans la vue
$footer = file_get_contents('../template/footer.html');
$page = str_replace("||FOOTER||", $footer, $page);

//affiche le template d'authentification
$page = functions::authTemplate($page);

// récupere toutes les interviews et conseils de la bdd
$listInfos = requeteSql::getAllInfos();
// transfert les interviews et conseils du php au js
$scripts .= functions::SendVar('Infos', $listInfos);

// On place toutes les function js, appeler avant, sur la vue
$page = str_replace("||SCRIPTS||", $scripts, $page);

// on affiche la vue
echo $page;

 ?>