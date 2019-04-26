//  On récupère les éléments qui possèdent un arttribut avec une valeur rechercher
function getElementsByAttribute(name, val) {
	return document.querySelectorAll(`[${name}="${val}"]`);
}

//Element supporter par la gestion  des formulaires
function listElementForm(){
	var list = ["input", "select", "textarea"] 
	return list;
} 

// Si le champs possède deja une div error la retourne
function getErrorForm(divFieldForm){
	var errorExist;
	var listError = getElementsByAttribute("error", "empty");
	for (var i = 0; i < listError.length; i++) {
		if (divFieldForm == listError[i].parentNode) errorExist = listError[i];
	}
	return errorExist;
}

// Initialise les formulaires de la page selon des règles afin de pouvoir gérer les vérifications
function gestionErrorForm(){

	var forms = getElementsByAttribute("divType", "form");
	for (var x = 0; x < forms.length; x++) {
		var formAct = forms[x];
		formAct.setAttribute("idForm", x);
		var listElement = listElementForm();
		for (var i = 0; i < listElement.length; i++) {
			var inputs = formAct.getElementsByTagName(listElement[i]);
			if (listElement[i] != "textarea") setEnterFunct(inputs);
			for (var y = 0; y < inputs.length; y++) {
				var inputAct = inputs[y];
				setOldValue(inputAct.getAttribute("name"));
				if (inputAct.getAttribute("type") == "submit") {
					var value = inputAct.getAttribute("text");
					inputAct.setAttribute("type", "button");
					inputAct.setAttribute("idForm", x);
					inputAct.setAttribute("id", "submit" + x);
					inputAct.value = value;
					inputAct.addEventListener("click", function(event){
						VerifFormIsRight(event.target);
					});
				}
			}
		}
	}

}


// Verifie que les champs obligatoire du formulaire renseigner sont renseigner
//Complete l'url avec les saisies et lance le formulaire si il est valide
function VerifFormIsRight(target){
	var idForm = target.getAttribute("idForm");
	var formAct = getElementsByAttribute("idForm", idForm)[0];
	var action = formAct.getAttribute("action");
	if (value = action.match(`([a-zA-Z0-9\u0080-\u00ff]*)=([a-zA-Z0-9\u0080-\u00ff]*)`)) {
		link = action + "&";
	}else{
		link = action + "?";
	}
	var formValide = true;
	
	var listElement = listElementForm();
	for (var i = 0; i < listElement.length; i++) {

		var inputs = formAct.getElementsByTagName(listElement[i]);
		for (var y = 0; y < inputs.length; y++) {
			var inputAct = inputs[y];
			var name = inputAct.name;
			var value = inputAct.value;
			if (inputAct.getAttribute("id") != "submit" + idForm){ 
				if (inputAct.getAttribute("type") == 'checkbox')value = inputAct.checked;
				
				if (inputAct.hasAttribute("required")){

					if (value == "" || value == null) {
						if (!getErrorForm(inputAct.parentNode)) {
							var error = document.createElement("span");
							error.setAttribute("error", "empty");
							error.className += "error";
							error.innerHTML = "Champ Obligatoire";
							inputAct.parentNode.appendChild(error);
						}
						formValide = false;
					}else{
						if (error = getErrorForm(inputAct.parentNode)) {
							inputAct.parentNode.removeChild(error);
						}
					}
				}
				link += name + "=" + value + "&";
			}
		}
	}
	link = link.substring(0, link.length -1);
	if (formValide) document.location.href = link;
}

//place sur l'input, un event de validation du formulaire avec la touche entrer
function setEnterFunct(listInputs){
	for (var i = 0; i < listInputs.length; i++) {
		listInputs[i].addEventListener('keypress', function (event) {
			if (event.key === 'Enter') {
				var formAct = event.target;
				while(formAct.getAttribute("idForm") == null){
					formAct = formAct.parentNode;
				}
				VerifFormIsRight(formAct);
			}
		});
	}
}

// Recuperation dans l'url des info get et pre-remplie les champs correspondants
function setOldValue(name){
	url = document.location.href;
	url = decodeURI(url);
	if (value = url.match(`${name}=([a-zA-Z0-9\u0080-\u00ff\'+]*)`)) {
		value = value[1];
		value = value.replace(/\+/g, " ");
		var element = getElementsByAttribute("name", name);
		if (element[0].getAttribute("type") == "password"){}
		else if(element[0].tagName == "TEXTAREA"){
				element[0].innerHTML = value;
			}else if(element[0].getAttribute("type") == "checkbox"){
				if (value == "on" || value == "true"){
					element[0].checked = true;
				}
		}else{
			element[0].setAttribute("value", value);
		}
	}
}

// Lance la fonction quand la page a fini de charger
document.addEventListener("DOMContentLoaded", function(e) { gestionErrorForm(); });