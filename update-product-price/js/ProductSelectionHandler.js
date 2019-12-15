"use strict";

class ProductSelectionHandler {
	constructor() {
		let thiz = this;
		$(document).ready(function(e) {
			thiz.onDocumentReady();
		});
	}

	onDocumentReady() {
		let thiz = this;
		$("#search_item_input").on('awesomplete-selectcomplete', function(e) {
			thiz.onProductSelection(e.originalEvent.text.value);
		});
	}

	onProductSelection(item) {
		alert("this == " + this + " == " + item.item_id);
		sfx_click.play();
		$('#search_item_input').val('');
	}
}
