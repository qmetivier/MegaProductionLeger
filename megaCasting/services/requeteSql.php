<?php
/**
* 
*/
class requeteSQL
{


//Connexion a la bdd
public static function connexionBdd(){
		$serverName = "HP-PC";
		$connectionInfo = array( "Database" => "Megacasting", "UID" => "sa", "pwd" => "SQL2014");

		/* Connect using Windows Authentication. */  
		$conn = sqlsrv_connect( $serverName, $connectionInfo);  
		if( $conn === false )  
		{  
     			echo "Unable to connect. ". get_current_user() . "</br>";  
     			die(print_r( sqlsrv_errors(), true));  
		}  
		$bdd = sqlsrv_connect( $serverName, $connectionInfo);
		return $bdd;
}

// Ferme la connrexion a la bdd
public static function closeBdd($bdd){
	sqlsrv_close($bdd);
}

// Permet de connecter le compte renseigner sur le site
public static function connexion($email, $password){
	$bdd =  requeteSQL::connexionBdd();
	$success = false;
	$password = md5($password);
	$sql = "select Id from professionnel where Email = '$email' and mdp = '$password'";
	$stmt = sqlsrv_query( $bdd, $sql);
	$stmt = sqlsrv_fetch_array($stmt);
	if ($stmt != null) {
		$_SESSION["loginId"] = $stmt[0]["Id"];
		$success = true;
	}else{
		$success = false;
	}
	requeteSQL::closeBdd($bdd);
	return $success;
}

// Deconnecte l'utilisateur connecter sur le site
public static function Deconnexion(){
	session_start();
	session_destroy();

}

// Retourne le profil d'un utilisateur
// Si Id non renseigner, retourne le profil de l'utilisateur connecté
public static function getProfil($id = null){
	if ($id == null) $id = $_SESSION["loginId"];
	$bdd =  requeteSQL::connexionBdd();
	$sql = "select Nom,Email,Telephone,Url,Fax,rue,Ville,CodePostal,Pays from professionnel where Id=$id";
	$stmt = sqlsrv_query( $bdd, $sql);
	$stmt = sqlsrv_fetch_array($stmt);
	
	requeteSQL::closeBdd($bdd);
	return $stmt;
}

// Retourne toute les offres valides
public static function getAlloffres(){
	$bdd =  requeteSQL::connexionBdd();
	$sql = "select  OffreCasting.Id,Titre,[desc],dt_debut_contrat,Nom from OffreCasting inner join professionnel on OffreCasting.Id_Professionel = professionnel.Id where GETDATE() < DATEADD(day, dure_dif, dt_debut_publi)";
	$stmt = sqlsrv_query( $bdd, $sql);
	$response = [];

	while (($temp = sqlsrv_fetch_array($stmt)) != null){
		array_push($response, $temp);
	}

	requeteSQL::closeBdd($bdd);
	return $response;
}

// Retourne toute les offres Valides d'un professionnel
public static function getOffresPro($id){
	$bdd =  requeteSQL::connexionBdd();
	$sql = "select Titre,[desc],dt_debut_contrat from OffreCasting where Id_Professionel=$id and GETDATE() < DATEADD(day, dure_dif, dt_debut_publi)";
	$stmt = sqlsrv_query( $bdd, $sql);
	$response = [];

	while (($temp = sqlsrv_fetch_array($stmt)) != null){
		array_push($response, $temp);
	}

	requeteSQL::closeBdd($bdd);
	return $response;
}

// Retourne l'offre valide correspondante
public static function getOffre($id){
	$bdd =  requeteSQL::connexionBdd();
	$sql = "select Titre,[desc],dt_debut_contrat,Id_Professionel, Nom,Email,Telephone,Url,Fax,Rue,Ville,CodePostal,Pays from OffreCasting inner join professionnel on OffreCasting.Id_Professionel = professionnel.Id where OffreCasting.Id = $id and GETDATE() < DATEADD(day, dure_dif, dt_debut_publi)";
	$stmt = sqlsrv_query($bdd, $sql);
	$response = [];

	while (($temp = sqlsrv_fetch_array($stmt)) != null){
		array_push($response, $temp);
	}

	requeteSQL::closeBdd($bdd);
	return $response;
}


// Retourne les offres qui répondent aux critères de recherche
public static function getSearchOffres($search, $parametre = null){
	$bdd =  requeteSQL::connexionBdd();
	// Recherche si des checkbox sont cochées
	if ($parametre[0] != null) {
			$sql = "DECLARE @IdentifiantCasting INT;

					DECLARE curseur CURSOR FOR 
					select OffreCasting.Id from OffreCasting
					inner join Metier_OffreCasting on OffreCasting.Id = Metier_OffreCasting.[Id_OffreCasting]
					inner join Metier on Metier_OffreCasting.Id_Metier = Metier.Id
					inner join DomaineMetier on Metier.Id_DomaineMetier = DomaineMetier.Id
					inner join TypeContrat on Metier_OffreCasting.Id_TypeContrat = TypeContrat.Id
					where OffreCasting.Titre LIKE ('%$search%') OR OffreCasting.[desc] LIKE '%$search%'
					group by TypeContrat.Nom, OffreCasting.Id, DomaineMetier.Nom HAVING
					";
			foreach ($parametre as $type => $array) {
				if (gettype($array) == 'array') {
					foreach ($array as $nom => $valeur) {
						if ($valeur) {
							$sql .= " $type LIKE '$nom' AND";
						}
					}
				}
			}
			// Supprime le dernier AND dans la requete sql
			$sql = substr($sql, 0, -3);
			$sql .= "
					OPEN curseur;

					FETCH NEXT FROM curseur INTO @IdentifiantCasting;
					WHILE @@FETCH_STATUS = 0
					BEGIN
						select OffreCasting.Id, Titre, [desc], dt_debut_contrat, Nom from OffreCasting inner join professionnel on OffreCasting.Id_Professionel = professionnel.Id where OffreCasting.Id = @IdentifiantCasting AND GETDATE() < DATEADD(day, dure_dif, dt_debut_publi)
						FETCH NEXT FROM curseur INTO @IdentifiantCasting;
					END
					CLOSE curseur;
					DEALLOCATE curseur";
	}else{
		// Si seulement la bar de recherche est remplie
		$sql = "select OffreCasting.Id, Titre, [desc], dt_debut_contrat, Nom from OffreCasting inner join professionnel on OffreCasting.Id_Professionel = professionnel.Id where GETDATE() < DATEADD(day, dure_dif, dt_debut_publi) AND(OffreCasting.Titre LIKE ('%$search%') OR OffreCasting.[desc] LIKE '%$search%')";
	}
	$stmt = sqlsrv_query($bdd, $sql);
	$response = [];
	do {
		while (($temp = sqlsrv_fetch_array($stmt)) != null){
			$valid = true;
			// verification qu'il n'y a pas de redondance dans la recherche
			foreach ($response as $table) {
				if ($temp == $table) {
					$valid = false;
				}
			}
			if ($valid) array_push($response, $temp);
		}
	} while (sqlsrv_next_result($stmt));
	requeteSQL::closeBdd($bdd);
	return $response;
}

// Retourne tout les types d'emplois
public static function getAllTypeEmplois(){
	$bdd =  requeteSQL::connexionBdd();
	$sql = "select * from TypeContrat";
	$stmt = sqlsrv_query( $bdd, $sql);
	$response = [];

	while (($temp = sqlsrv_fetch_array($stmt)) != null){
		array_push($response, $temp);
	}
	requeteSQL::closeBdd($bdd);
	return $response;
}

// Retourne tout les types de métiers
public static function getAllTypesMetiers(){
	$bdd =  requeteSQL::connexionBdd();
	$sql = "select * from DomaineMetier";
	$stmt = sqlsrv_query( $bdd, $sql);
	$response = [];

	while (($temp = sqlsrv_fetch_array($stmt)) != null){
		array_push($response, $temp);
	}
	requeteSQL::closeBdd($bdd);
	return $response;
}

// Retourne toutes les interviews et conseils
public static function getAllInfos(){
	$bdd =  requeteSQL::connexionBdd();
	$sql = "select * from ContenuEditorial inner join TypeContenu on ContenuEditorial.Id_TypeContenu = TypeContenu.Id";
	$stmt = sqlsrv_query( $bdd, $sql);
	$response = [];

	while (($temp = sqlsrv_fetch_array($stmt)) != null){
		array_push($response, $temp);
	}
	requeteSQL::closeBdd($bdd);
	return $response;
}

// Retourne l'interview et conseil recherché
public static function getSearchInfo($id){
	$bdd =  requeteSQL::connexionBdd();
	$sql = "select * from ContenuEditorial inner join TypeContenu on ContenuEditorial.Id_TypeContenu = TypeContenu.Id where ContenuEditorial.Id = $id";
	$stmt = sqlsrv_query( $bdd, $sql);
	$response = [];

	while (($temp = sqlsrv_fetch_array($stmt)) != null){
		array_push($response, $temp);
	}
	requeteSQL::closeBdd($bdd);
	return $response;
}

// Retourne tout les packs de Casting
public static function getAllPacks(){
	$bdd =  requeteSQL::connexionBdd();
	$sql = "select * from PackCasting inner join PrixPack on PackCasting.Id = PrixPack.Id_PackCasting where dt_fin is null";
	$stmt = sqlsrv_query( $bdd, $sql);
	$response = [];

	while (($temp = sqlsrv_fetch_array($stmt)) != null){
		array_push($response, $temp);
	}
	requeteSQL::closeBdd($bdd);
	return $response;
}

public static function getPartenaire($username, $password){
	$bdd =  requeteSQL::connexionBdd();
	$success = false;
	$password = md5($password);
	$sql = "select Email from partenaire where Email = '$username' and mdp = '$password'";
	$stmt = sqlsrv_query( $bdd, $sql);
	$stmt = sqlsrv_fetch_array($stmt);
	if ($stmt != null)$success = true;
	else $success = false;
	requeteSQL::closeBdd($bdd);
	return $success;
}

}

?>