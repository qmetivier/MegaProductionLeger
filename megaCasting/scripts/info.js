(function ($) {
	//Si l'interview et conseil recherché n'existe pas
	if (Info[0] == undefined) {
		container = document.getElementById("containerMain");
		container.innerHTML = Page404;
	}else{
		//Sinon on remplie la page avec ses infos 
		Info = Info[0];

		info = document.getElementById("info-titre");
		info.innerHTML += Info.Titre;
		info = document.getElementById("info-type");
		info.innerHTML += Info.libelle;
		info = document.getElementById("info-desc");
		info.innerHTML += Info.Contenue;
	}

})(jQuery);