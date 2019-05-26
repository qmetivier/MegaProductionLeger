(function ($) {

	function showListOffres(self, offres){
	// pour chaque offre on génère une div que l'on rajoute sur la page
	for (var i = 0; i < offres.length; i++) {
		self.appendChild(new OffreContainer(offres[i]).container);
	}
	}
	// forme de la div ajouter a la page
	function OffreContainer(offre){
		var div = document.createElement('div');
		div.className = 'offre-div';

		var titre = document.createElement('span');
		titre.className = 'offre-titre';
		titre.innerHTML = offre.Titre;

		var date = document.createElement('span');
		date.className = 'offre-date';
		date.innerHTML = "Date de début :" + dateFormat(offre["dt_debut_contrat"].date, "dd mmmm yyyy");

		var description = document.createElement('div');
		description.className = 'offre-desc';
		description.innerHTML = offre.desc.substr(0, 250);

		var prof = document.createElement('span');
		prof.className = 'offre-pro';
		prof.innerHTML = "Posté par: " + offre.Nom;

		var link = document.createElement('a');
		link.className = 'offre-link';
		link.innerHTML = 'Afficher Détails';
		link.setAttribute("href", "offreCastingController.php?id=" + offre[0]);

		var clearboth = document.createElement('div');
		clearboth.className = 'clearboth';

		div.appendChild(titre);
		div.appendChild(date);
		div.appendChild(description);
		div.appendChild(prof);
		div.appendChild(link);
		div.appendChild(clearboth);

		this.__defineGetter__('container', () => div);
	}




	function showListCheckbox(self, info){
	// pour chaque element 'Type' on génère une div que l'on rajoute dans la partie de recherche
	for (var i = 0; i < info.length; i++) {
		self.appendChild(new CheckboxContainer(info[i]).container);
	}
	}
	// forme de la div ajouter a la page
	function CheckboxContainer(info){
		var div = document.createElement('div');
		div.className = 'checkbox-div';

		var label = document.createElement('label');
		label.className = 'checkbox-titre';
		label.setAttribute("for", info.Id + info.Nom);
		label.innerHTML = info.Nom;

		var checkbox = document.createElement('input');
		checkbox.setAttribute("id", info.Id + info.Nom);
		checkbox.className = 'checkbox-input';
		checkbox.setAttribute("name", info.Nom);
		checkbox.setAttribute("type", "checkbox");

		div.appendChild(label);
		div.appendChild(checkbox);

		this.__defineGetter__('container', () => div);
	}



	var container = document.getElementById("ContainerOffres");
	// Si il n'y a pas d'offre de casting
	if (Offres[0] == undefined) {
		container.innerHTML = PageEmpty;
	}else{
		//Sinon on les affichent
		showListOffres(container, Offres);
	}

	var container = document.getElementById("ContainerCheckBox");
	var div = document.createElement("div");
	div.innerHTML = "Type de contrats :";
	container.appendChild(div);
	//On ajoute la liste de checkbox de type de contrats
	showListCheckbox(container, TypesContrats);
	var div = document.createElement("div");
	div.innerHTML = "Type de metiers :";
	container.appendChild(div);
	//On ajoute la liste de checkbox de type de metiers
	showListCheckbox(container, TypesMetiers);

})(jQuery);