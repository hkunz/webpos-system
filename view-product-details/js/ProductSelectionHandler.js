"use strict";

class ProductSelectionHandler {
	constructor() {
		let thiz = this;
		this.description = null;
		this.itemId = 0;
		this.code = null;
		this.unit = null;
		this.category = null;
		this.brand_name = null;
		this.supplier = null;
		this.general_name = null;
		this.sell_price = 0;
		this.unit_price = 0;
		this.stock = 0;

		$(document).ready(function() {
			$("#search_item_input").on('awesomplete-selectcomplete', function(e) {
				sfx_click.play();
				$('#search_item_input').val('');
				const json = e.originalEvent.text.value;
				const item = json;
				thiz.code = item.code;
				thiz.unit = item.unit;
				thiz.itemId = item.item_id;
				thiz.description = item.description;
				thiz.unit_price = item.unit_price;
				thiz.sell_price = item.sell_price;
				thiz.brand_name = item.brand_name;
				thiz.supplier = item.supplier;
				thiz.general_name = item.general_name;
				thiz.category = item.category;
				thiz.stock = item.stock;
				$('#product_id').text(item.item_id);
				$('#product_barcode').text(item.barcode);
				$('#product_description').text($('<textarea />').html(item.description).text());
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
				let id_text = ' <span style="color:#999999">[ID-' + thiz.itemId + ']</span>';
				//document.getElementById('popup_item_price').innerHTML = "₱" + thiz.price;
				thiz.onProductSelection();
			});
		});
	}

	onProductSelection() {
	}
}
