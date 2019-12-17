"use strict";

class ProductSelectionHandler {
	constructor() {
		this.curr_item = null;
		this.new_unit_price = -1;
		this.new_sell_price = -1;
		this.curr_unit_price_asofdate = null;
		this.curr_sell_price_asofdate = null;

		let thiz = this;
		$(document).ready(function(e) {
			thiz.onDocumentReady();
		});
	}

	onDocumentReady() {
		let thiz = this;
		$("#search_item_input").on('awesomplete-selectcomplete', function(e) {
			thiz.curr_item = e.originalEvent.text.value;
			thiz.onProductSelection();
		});
		$('#unit_price_input').bind('input', function() {
			thiz.onCurrentPriceChange();
		});
		$('#sell_price_input').bind('input', function() {
			thiz.onCurrentPriceChange();
		});
		$('#update_price_button').click(function() {
			thiz.onUpdatePriceButtonClick();
		});
	}

	onCurrentPriceChange() {
		const unit_price_tx = $('#unit_price_input').val();
		const sell_price_tx = $('#sell_price_input').val();
		this.new_unit_price = Utils.getCurrencyValue(unit_price_tx);
		this.new_sell_price = Utils.getCurrencyValue(sell_price_tx);
		let button = document.getElementById('update_price_button');
		let enable = unit_price_tx != '' && sell_price_tx != '' && (this.isNewUnitPrice() || this.isNewSellPrice());
		button.disabled = !enable;
	}

	onProductSelection() {
		let thiz = this;
		Utils.play(sfx_click);
		$('#search_item_input').val('');
		$.ajax({
			type: "POST",
			url: "php/get-prices-history.php" + Utils.getRandomUrlVar(),
			data: {
				item_id: thiz.curr_item.item_id
			},
			success: function(data) {
				thiz.onShowPricesHistory(JSON.parse(data));
			}
		});
	}

	onUpdatePriceButtonClick() {
		Utils.play(sfx_commit_transaction);
		let thiz = this;
		let button = document.getElementById('update_price_button');
		button.disabled = true;

		$.ajax({
			type: "POST",
			url: "php/update-product-prices.php" + Utils.getRandomUrlVar(),
			data: {
				item_id: thiz.curr_item.item_id,
				new_unit_price: thiz.new_unit_price,
				unit_price_asofdate: thiz.isNewUnitPrice() ? '' : thiz.curr_unit_price_asofdate,
				new_sell_price: thiz.new_sell_price,
				sell_price_asofdate: thiz.isNewSellPrice() ? '' : thiz.curr_sell_price_asofdate
			},
			success: function(data) {
				thiz.onUpdatePricesComplete(JSON.parse(data));
			}
		});
	}

	onShowPricesHistory(json) {
		let item = this.curr_item;
		this.curr_unit_price_asofdate = json.curr_unit_price_asofdate;
		this.curr_sell_price_asofdate = json.curr_sell_price_asofdate;
		let sell_price = Number(item.sell_price).toFixed(2);
		let unit_price = Number(item.unit_price).toFixed(2);
		$('#table_container').html(json.table);
		$('#product_name_container').css('display','block');
		$('#product_code').text('Bar Code: ' + (item.barcode ? item.barcode : "None") + ' {ID-' + item.item_id + '}');
		$('#product_name').text('[' + item.unit + '] ' + Utils.resolveHtmlEntities(item.description));
		$('#sell_price_input').attr("placeholder", sell_price);
		$('#unit_price_input').attr("placeholder", unit_price);
		$('#sell_price_input').val(sell_price);
		$('#unit_price_input').val(unit_price);
	}

	onUpdatePricesComplete(json) {
		if (json.success) {
			this.curr_item.unit_price = json.new_unit_price;
			this.curr_item.sell_price = json.new_sell_price;
			alert("success: " + json.message);
		} else {
			alert("failed: " + json.message + ": " + json.query);
		}
		this.onProductSelection();
	}

	isNewUnitPrice() {
		return this.new_unit_price != this.curr_item.unit_price;
	}

	isNewSellPrice() {
		return this.new_sell_price != this.curr_item.sell_price;
	}
}
