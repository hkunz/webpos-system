"use strict";

class PopupUtils {
	//from jquery-modal
	static show(div_name) {
		$('#' + div_name).appendTo('body').modal();
	}

	static create(message) {
		let div = document.createElement('div');
		div.setAttribute('id', 'temp_popup');
		div.className = "modal";
		div.innerHTML = "<label class='standard-label'>" + Utils.resolveHtmlEntities(message) + "</label>";
		document.body.appendChild(div);
		$('#temp_popup').modal();
	}
}

