
// si le bouton de connexion existe
if (document.getElementById("btn-panel-login") != undefined) {
	// Si on click sur le bouton de connexion, on ouvre le formulaire de connexion
	document.getElementById("btn-panel-login").parentElement.addEventListener("click", function(){
		var backpanel = document.getElementById("back-panel-login");
		var panel = document.getElementById("panel-login");
		if (backpanel.getAttribute("display") == "none") {
			backpanel.setAttribute("display", "yes");
			panel.setAttribute("show","");
		}
	});
	// Si la personne clique autre par que sur dans le formulaire, on le ferme 
	document.getElementById("back-panel-login").parentElement.addEventListener("click", function(event){
		var backpanel = document.getElementById("back-panel-login");
		var panel = document.getElementById("panel-login");
		if (event.target.getAttribute("id") == "back-panel-login") {
			backpanel.setAttribute("display", "none");
			panel.removeAttribute("show");
		}
	});
}

