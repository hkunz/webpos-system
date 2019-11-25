"use strict";

class ItemAmountInputPopupHandler {
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
		this.price = 0;

		$(document).ready(function() {
			$("#search_item_input").on('awesomplete-selectcomplete', function(e) {
				//let v = this.value;
				const value = e.originalEvent.text.value;
				const item = JSON.parse(value);
				thiz.code = item.code;
				thiz.unit = item.unit;
				thiz.itemId = item.itemId;
				thiz.description = item.description;
				thiz.price = item.price;
				thiz.brand_name = item.brand_name;
				thiz.supplier = item.supplier;
				thiz.general_name = item.general_name;
				thiz.category = item.category;
				let id_text = ' <span style="color:#999999">[ID-' + thiz.itemId + ']</span>';
				document.getElementById('popup_item_code').innerHTML = "[" + thiz.code + "]";
				document.getElementById('popup_item_description').innerHTML = $('<textarea />').html(thiz.description).text() + id_text;
				document.getElementById('popup_item_category').innerHTML = thiz.category;
				document.getElementById('popup_item_price').innerHTML = "₱" + thiz.price;
				document.getElementById('item_amount_unit').innerHTML = item.unit;
				thiz.showPopup(true);
			});

			$("#popup_amount_input").on('keydown', (function(e) {
				if (e.keyCode == 13 && thiz.item_amount_popup_visible == true) {
					e.stopImmediatePropagation();
					thiz.onPopupEnter();
					return false;
				}	
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

			thiz.item_amount_popup_visible = false;
		});
	}

	onPopupEnter() {
		const e = new CustomEvent(EVENT_ITEM_AMOUNT_POPUP_INPUT_COMPLETE, {
			detail: {
				code:this.code,
				unit:this.unit,
				itemId:this.itemId,
				description:this.description,
				amount:Number(document.getElementById('popup_amount_input').value),
				price:this.price,
			}
		});
		this.showPopup(false);
		document.getElementById("eventdispatcher").dispatchEvent(e);
	}

	showPopup(show) {
		if (show) {
			sfx_popup.play();
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
