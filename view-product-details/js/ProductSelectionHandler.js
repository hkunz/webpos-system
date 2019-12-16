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
		sfx_click.play();
		$('#search_item_input').val('');
		$('#product_id').text(item.item_id);
		$('#product_barcode').text(item.barcode ? item.barcode : "None");
		$('#product_description').text(Utils.resolveHtmlEntities(item.description));
		$('#unit').text(item.unit);
		$('#product_category').text(item.category);
		$('#stock').text(item.stock);
		$('#brand_name').text(item.brand_name);
		$('#general_name').text(item.general_name);
		$('#content_count').text(item.count);
		$('#product_manufacturer').text(item.manufacturer);
		$('#unit_price').text(Utils.getCurrencySymbol() + item.unit_price);
		$('#sell_price').text(Utils.getCurrencySymbol() + item.sell_price);
		$('#stock').text(item.stock);
		$('#product_supplier').text(item.supplier);
	}
}
