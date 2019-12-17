"use strict";

class ProductSelectionHandler {
	constructor() {
		let thiz = this;
		let curr_unit_price = null;
		let curr_sell_price = null;

		$(document).ready(function(e) {
			thiz.onDocumentReady();
		});
	}

	onDocumentReady() {
		let thiz = this;
		$("#search_item_input").on('awesomplete-selectcomplete', function(e) {
			thiz.onProductSelection(e.originalEvent.text.value);
		});
		$('#unit_price_input').bind('input', function() {
			thiz.onCurrentPriceChange();
		});
		$('#sell_price_input').bind('input', function() {
			thiz.onCurrentPriceChange();
		});
	}

	onCurrentPriceChange() {
		const unit_price_tx = $('#unit_price_input').val();
		const sell_price_tx = $('#sell_price_input').val();
		const unit_price = Utils.getCurrencyValue(unit_price_tx);
		const sell_price = Utils.getCurrencyValue(sell_price_tx);
		let button = document.getElementById('update_price_button');
		let enable = unit_price_tx != '' && sell_price_tx != '' && (unit_price != this.curr_unit_price || sell_price != this.curr_sell_price);
		button.disabled = !enable;
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
		this.curr_unit_price = item.unit_price;
		this.curr_sell_price = item.sell_price;
		$('#table_container').html(json.table);
		$('#product_name_container').css('display','block');
		$('#price_editor_container').css('display','block');
		$('#product_code').text('Bar Code: ' + (item.barcode ? item.barcode : "None"));
		$('#product_name').text(Utils.resolveHtmlEntities(item.description) + ' {ID-' + item.item_id + '}');
		$('#sell_price_input').attr("placeholder", this.curr_sell_price);
		$('#unit_price_input').attr("placeholder", this.curr_unit_price);
		$('#sell_price_input').val(item.sell_price);
		$('#unit_price_input').val(item.unit_price);
	}
}
