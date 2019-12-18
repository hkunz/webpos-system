"use strict";

class CustomerSelectionHandler {
	constructor() {
		this.customer = null;

		let thiz = this;
		$(document).ready(function(e) {
			thiz.onDocumentReady();
		});
	}

	onDocumentReady() {
		let thiz = this;
	}
}
