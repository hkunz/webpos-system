"use strict";

class Controller {
	constructor() {
		let thiz = this;
		$(document).ready(function(e) {
			thiz.onDocumentReady();
		});
		this.search_handler = new ProductSearchInputHandler();
		this.selection_handler = new ProductSelectionHandler();
	}

	onDocumentReady() {
		let thiz = this;
		$("#search_item_input").focus();
		$('#eventdispatcher').on(ProductSearchInputHandler.ENTER_PRODUCT_EVENT, function(e) {
			thiz.onEnterProduct(e.detail.quantity, e.detail.item);
		});
	}

	onEnterProduct(qty, item) {
		this.selection_handler.onProductSelection(item);
	}
}
