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
		let thiz = this;
		sfx_click.play();
		$('#search_item_input').val('');
		$.ajax({
			type: "POST",
			url: "php/get-prices-history.php" + Utils.getRandomUrlVar(),
			data: {
				item_id: item.item_id
			},
			success: function(data) {
				thiz.onShowPricesHistory(JSON.parse(data), item);
			}
		});
	}

	onShowPricesHistory(json, item) {
		$('#table_container').html(json.table);
		$('#product_code').text('Bar Code: ' + (item.barcode ? item.barcode : "None"));
		$('#product_name').text(Utils.resolveHtmlEntities(item.description) + ' {ID-' + item.item_id + '}');
	}
}
