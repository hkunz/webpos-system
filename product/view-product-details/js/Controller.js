"use strict";

class Controller {
	constructor() {
		let thiz = this;
		$(document).ready(function(e) {
			thiz.onDocumentReady();
		});
		this.selection_handler = new ProductSelectionHandler();
		this.search_handler = new ProductSearchInputHandler();
	}

	onDocumentReady() {
		let thiz = this;
		$("#search_item_input").focus();
		document.getElementById('eventdispatcher').addEventListener(ProductSearchInputHandler.ENTER_PRODUCT_EVENT, function(e) {
			thiz.onEnterProduct(e.detail.item, e.detail.quantity);
		});
	}

	onEnterProduct(item, qty) {
		this.selection_handler.onProductSelection(item);
	}
}
