"use strict";

class ItemAmountInputPopupHandler {
	constructor() {
		this.description = null;
		this.itemId = 0;
		this.code = null;
		this.unit = null;
		this.category = null;
		this.brand_name = null;
		this.supplier = null;
		this.general_name = null;
		this.price = 0;
		this.stock = 0;

		let thiz = this;
		$(document).ready(function() {
			thiz.onDocumentReady();
		});
	}

	onDocumentReady() {
		let thiz = this;
		$("#search_item_input").on('awesomplete-selectcomplete', function(e) {
			thiz.onAwesompleteSelectionComplete(e.originalEvent.text.value);
		});
		Utils.disableCutCopyPaste('#popup_amount_input');
		$("#popup_amount_input").on('keydown', (function(e) {
			return thiz.onPopupKeydown(e);
		}));
		$("#amount_popup_box").click(function(e) {
			e.stopImmediatePropagation();
		});
		$('.hover_bkgr_fricc').click(function(e) {
			e.stopImmediatePropagation();
			thiz.showPopup(false);
		});
		$('.popupCloseButton').click(function(e) {
			e.stopImmediatePropagation();
			thiz.showPopup(false);
		});
		this.item_amount_popup_visible = false;
	}

	onAwesompleteSelectionComplete(json) {
		const item = json;
		this.code = item.code;
		this.unit = item.unit;
		this.itemId = item.item_id;
		this.description = item.description;
		this.sell_price = item.sell_price;
		this.brand_name = item.brand_name;
		this.supplier = item.supplier;
		this.general_name = item.general_name;
		this.category = item.category;
		this.stock = item.stock;
		let id_text = ' <span style="color:#999999">[ID-' + this.itemId + ']</span>';
		document.getElementById('popup_item_code').innerHTML = "[" + this.code + "]";
		document.getElementById('popup_item_description').innerHTML = Utils.resolveHtmlEntities(this.description) + id_text;
		document.getElementById('popup_item_category').innerHTML = this.category;
		document.getElementById('popup_item_stock').innerHTML = (this.category.toLowerCase() == 'service' ? '' : '[X' + this.stock + ']');
		document.getElementById('popup_item_price').innerHTML = "₱" + this.sell_price;
		document.getElementById('item_amount_unit').innerHTML = item.unit;
		this.showPopup(true);
	}

	onPopupKeydown(e) {
		if (e.keyCode == 13 && this.item_amount_popup_visible == true) {
			e.stopImmediatePropagation();
			this.onPopupEnter();
			return false;
		}
		if (e.keyCode == 27 && this.item_amount_popup_visible == true) {
			e.stopImmediatePropagation();
			this.showPopup(false);
			return false;
		}
		return true;
	}

	onPopupEnter() {
		let amount = Number(document.getElementById('popup_amount_input').value);
		const e = new CustomEvent(EVENT_ITEM_AMOUNT_POPUP_INPUT_COMPLETE, {
			detail: {
				code:this.code,
				unit:this.unit,
				itemId:this.itemId,
				description:this.description,
				amount:(amount > 0 ? amount : 1),
				sell_price:this.sell_price,
			}
		});
		this.showPopup(false);
		document.getElementById("eventdispatcher").dispatchEvent(e);
	}

	showPopup(show) {
		if (show) {
			Utils.play(sfx_popup);
			let input = document.getElementById('popup_amount_input');
			$('.hover_bkgr_fricc').show();
                	input.value = '1';
                	input.focus();
                	input.select();
		} else {
			$('.hover_bkgr_fricc').hide();
                	$("#search_item_input").val("");
                	$("#search_item_input").focus();
		}
		this.item_amount_popup_visible = show;
	}
}
