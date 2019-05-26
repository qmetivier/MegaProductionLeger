(function ($) {
	function showListInfos(self, packs){
	// pour chaque pack on génère une div que l'on rajoute sur la page
		for (var i = 0; i < packs.length; i++) {
			self.appendChild(new PacksContainer(packs[i]).container);
		}
	}
	// forme de la div ajouter a la page
	function PacksContainer(pack){
		var div = document.createElement('div');
		div.className = 'pack-div';

		var titre = document.createElement('span');
		titre.className = 'pack-titre';
		titre.innerHTML = pack.libelle;

		var description = document.createElement('div');
		description.className = 'pack-desc';
		description.innerHTML = pack.NbrPost + " Castings";

		var prix = document.createElement('div');
		prix.className = 'pack-price';
		prix.innerHTML = "Prix: " + pack.Prix + " €";

		var clearboth = document.createElement('div');
		clearboth.className = 'clearboth';

		div.appendChild(titre);
		div.appendChild(description);
		div.appendChild(prix);
		div.appendChild(clearboth);

		this.__defineGetter__('container', () => div);
	}

	var container = document.getElementById("ContainerPacks");

	if (Packs[0] == undefined) {
		container.innerHTML = PageEmpty;
	}else{
		//Sinon on les affichent
		showListInfos(container, Packs);
	}
})(jQuery);