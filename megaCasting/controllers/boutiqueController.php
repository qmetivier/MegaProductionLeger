<?php 
session_start();
include '../services/requeteSql.php';
include '../services/function.php';

// Si le formulaire d'authentification est remplie 
if (isset($_GET["auth-name"])){
	$email = htmlentities($_GET["auth-name"]);
	$password = htmlentities($_GET["auth-pass"]);
	requeteSql::connexion($email, $password);
	header("Location: boutiqueController.php");	
}

$scripts = "";

// recupere le contenue de la vue
$page = file_get_contents('../views/boutique.php');

// recupère le template du header et le place dans la vue
$header = file_get_contents('../template/header.html');
$page = str_replace("||HEADER||", $header, $page);

// recupère le template du footer et le place dans la vue
$footer = file_get_contents('../template/footer.html');
$page = str_replace("||FOOTER||", $footer, $page);

//affiche le template d'authentification
$page = functions::authTemplate($page);

// récupere toute les packs de la bdd
$listPacks = requeteSql::getAllPacks();
// transfert les packs du php au js
$scripts .= functions::SendVar('Packs', $listPacks);

if ($listPacks == null) {
	
	//Charge en js le template de l'erreur Empty
	$PageEmpty = file_get_contents('../template/errorEmpty.html');
	$scripts .= functions::SendVar('PageEmpty', $PageEmpty);

}
// On place toutes les function js, appeler avant, sur la vue
$page = str_replace("||SCRIPTS||", $scripts, $page);

// on affiche la vue
echo $page;

 ?>