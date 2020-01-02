"use strict";

class ProductSearchInputHandler extends ItemSearchInputHandler {
	constructor() {
		super();
		this.quantity = 0;
		this.barcode = null;
	}

	onDocumentReady() {
		super.onDocumentReady();
		let thiz = this;
		document.getElementById(this.name).addEventListener('paste', function(e) {
			thiz.onTextPaste(e);
		});
	}

	onItemSearchInputChange(e) {
		let thiz = this;
		let search = this.getInputElementVal();
		if (e.keyCode === 13) {
			thiz.onEnterPress();
		} else {
			super.onItemSearchInputChange(e);
		}
	}

	onEnterPress() {
		let value = this.getInputElementVal();
		let index = value.indexOf("*");
		this.clearInputElement();
		if (index > -1) {
			let qty = parseInt(value.substring(0, index));
			if (isNaN(qty)) {
				return;
			}
			this.quantity = qty;
			this.barcode = value.substring(index + 1);
		} else {
			this.quantity = 1;
			this.barcode = value;
		}
		this.ajax(this.barcode);
	}

	onAjaxSuccess(data) {
		let qty = this.quantity;
		this.quantity = 0;
		if (qty > 0) {
			let i = this.getFirstItem(data);
			if (i == null || i.code !== this.barcode) {
				return;
			}
			ItemAmountInputPopupHandler.dispatchInputComplete(i.code, i.unit, i.item_id, i.description, qty, i.sell_price);
		} else {
			super.onAjaxSuccess(data);
		}
	}

	onTextPaste(e) {
		e.stopPropagation();
		e.preventDefault();
		let clipboard = e.clipboardData || window.clipboardData;
		let paste = clipboard.getData('Text');
		let enter = (paste.indexOf('\n') > 0);
		let text = paste.replace(/[\n\r]/g, '');
		this.getInputElement().val(text);
		if (enter) {
			this.onEnterPress();
		}
	}
}
