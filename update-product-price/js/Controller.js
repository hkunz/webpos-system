"use strict"

class Controller {
	constructor() {
		let thiz = this;
		$(document).ready(function(e) {
			thiz.onDocumentReady();
		});
	}

	onDocumentReady() {
		let thiz = this;
		$("#search_item_input").focus();
	}
}
