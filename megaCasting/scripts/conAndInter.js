(function ($) {
	function showListInfos(self, infos){
	// pour chaque interview et conseil on génère une div que l'on rajoute sur la page
		for (var i = 0; i < infos.length; i++) {
			self.appendChild(new InfosContainer(infos[i]).container);
		}
	}
	// forme de la div ajouter a la page
	function InfosContainer(info){
		var div = document.createElement('div');
		div.className = 'info-div';

		var titre = document.createElement('span');
		titre.className = 'info-titre';
		titre.innerHTML = info.Titre;

		var description = document.createElement('div');
		description.className = 'info-desc';
		description.innerHTML = info.Contenue.substr(0, 150);

		var link = document.createElement('a');
		link.className = 'info-link';
		link.innerHTML = 'Afficher Détails';
		link.setAttribute("href", "InfoController.php?id=" + info[0]);

		var clearboth = document.createElement('div');
		clearboth.className = 'clearboth';

		div.appendChild(titre);
		div.appendChild(description);
		div.appendChild(link);
		div.appendChild(clearboth);

		this.__defineGetter__('container', () => div);
	}

	var container = document.getElementById("ContainerInfos");

	if (Infos[0] == undefined) {
		container.innerHTML = PageEmpty;
	}else{
		//Sinon on les affichent
		showListInfos(container, Infos);
	}

})(jQuery);