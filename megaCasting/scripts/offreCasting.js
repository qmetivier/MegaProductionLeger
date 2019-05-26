(function ($) {
	//Si l'offre recherché n'existe pas
	if (Offre[0] == undefined) {
		container = document.getElementById("containerMain");
		container.innerHTML = Page404;
	}else{
		//Sinon on remplie la page avec ses infos
		Offre = Offre[0];

		info = document.getElementById("offre-titre");
		info.innerHTML += Offre.Titre;
		info = document.getElementById("offre-date");
		info.innerHTML += dateFormat(Offre["dt_debut_contrat"].date, "dd mmmm yyyy");
		info = document.getElementById("offre-desc");
		info.innerHTML += Offre.desc;

		info = document.getElementById("pro-img");
		info.innerHTML = "<i class='fas fa-user-alt'>";

		info = document.getElementById("pro-nom");
		var div = document.createElement("a");
		var id = Offre["Id_Professionel"];
		div.setAttribute("href", "profilController.php?id=" + id);
		div.innerHTML += Offre.Nom;
		info.appendChild(div);

		info = document.getElementById("pro-tel");
		info.innerHTML += Offre.Telephone;
		info = document.getElementById("pro-email");
		info.innerHTML += Offre.Email;
		info = document.getElementById("pro-fax");
		info.innerHTML += Offre.Fax;
		info = document.getElementById("pro-url");
		var div = document.createElement("a");
		div.setAttribute("href", Offre.Url);
		div.innerHTML += Offre.Url;
		info.appendChild(div);
	}
	
})(jQuery);