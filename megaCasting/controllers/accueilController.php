<?php 
session_start();
include '../services/requeteSql.php';
include '../services/function.php';

// Si le formulaire d'authentification est remplie 
if (isset($_GET["auth-name"])){
	$email = htmlentities($_GET["auth-name"]);
	$password = htmlentities($_GET["auth-pass"]);
	requeteSql::connexion($email, $password);
	header("Location: accueilController.php");
}

$scripts = "";

// recupere le contenue de la vue
$page = file_get_contents('../views/accueil.php');

// recupère le template du header et le place dans la vue
$header = file_get_contents('../template/header.html');
$page = str_replace("||HEADER||", $header, $page);

// recupère le template du footer et le place dans la vue
$footer = file_get_contents('../template/footer.html');
$page = str_replace("||FOOTER||", $footer, $page);

//affiche le template d'authentification
$page = functions::authTemplate($page);


$listTypesEmplois = requeteSql::getAllTypeEmplois();
// transfert les types d'emplois du php au js
$scripts .= functions::SendVar('TypesContrats', $listTypesEmplois);

$listTypesMetiers = requeteSql::getAllTypesMetiers();
// transfert les types de metiers du php au js
$scripts .= functions::SendVar('TypesMetiers', $listTypesMetiers);

// Si la recherche est utiliser
if (isset($_GET["search"])) {
	$param[] = null;
	foreach ($_GET as $key => $value) {
		//Pour la bar de recherche
		if ($key == "search") {
			$search = str_replace("'", "&#39", $value);
		}else{
			
			//Pour les checkboxs
			$type = null;
			if ($value == "on" || $value == "true") {
				foreach ($listTypesMetiers as $nom => $a) {
					// si c'est une type de metier
					if (utf8_encode($a["Nom"]) == $key) {
						$type = "DomaineMetier.Nom";
						break;
					}
				}
				if ($type == null) {
					foreach ($listTypesEmplois as $nom => $a) {
						// si c'est un type d'emploi
						if (utf8_encode($a["Nom"]) == $key) {
							$type = "TypeContrat.Nom";
							break;
						}
					}
				}
				// si il appartenait bien a un type
				if ($type != null){
					$param[$type][$key] = true;
					$param[0] = true;
				}
			}
		}
	}
	// récupère les offres en fonction des paramètre de recherche
	$listOffres = requeteSql::getSearchOffres(htmlspecialchars($search), $param);
}else{
	// Sinon récupere toute les offres de la bdd
	$listOffres = requeteSql::getAlloffres();
}

// transfert les offres du php au js
$scripts .= functions::SendVar('Offres', $listOffres);

//Charge en js le template de l'erreur 404
$Page404 = file_get_contents('../template/error404.html');
$scripts .= functions::SendVar('Page404', $Page404);

// On place toutes les function js, appeler avant, sur la vue
$page = str_replace("||SCRIPTS||", $scripts, $page);

// on affiche la vue
echo $page;

 ?>