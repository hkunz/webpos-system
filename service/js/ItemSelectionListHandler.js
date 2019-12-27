"use strict";

class ItemSelectionListHandler {
	constructor() {
		this.list = [];
		this.grand_total_handler = new GrandTotalViewHandler();
		this.grand_total_handler.setList(this.list);
		let thiz = this;
	}

	reset() {
		Utils.play(sfx_delete);
		this.list = [];
		this.grand_total_handler.setList(this.list);
		let div = document.getElementById(this.getItemsListIdName());
		div.innerHTML = '';
		this.updateTotalPrice();
		this.updateItemsList();
	}

	addItem(item) {
		let itm = null;
		let len = this.list.length;
		let isnew = true;
		//alert("add item: " + itemId + ": " + description + ": " + amount);
		for (let i = 0; i < len; ++i) {
			itm = this.list[i];
			if (itm.itemId != item.itemId) {
				continue;
			}
			let input = $('#' + this.getRowItemAmountInputIdName(item.itemId));
			itm.amount = Number(input.val()) + item.amount;
			input.val(itm.amount);
			this.updatePrice(item.itemId);
			isnew = false;
		}
		if (isnew) {
			this.addListRowItem(item);
		}
		Utils.play(sfx_click);
		this.debug_dump();
	}

	debug_dump() {
		for (let i = 0; i < this.list.length; ++i) {
                        let item = this.list[i];
			console.log(" item[" + i + "] => " +
				item.itemId + ": " +
				item.unit + ": " +
				item.amount + ": " +
				item.sell_price + ": " +
				item.code
			);
		}
	}

	addListRowItem(item) {
		this.list.push(item);
		let thiz = this;
		const id = item.itemId;
		let newdiv = document.createElement('div');
		newdiv.setAttribute('id', this.getItemIdName(id));
		newdiv.className = "divtr";
		newdiv.innerHTML = this.getListRowItemInnerHtml(item);
		document.getElementById(this.getItemsListIdName()).appendChild(newdiv);
		let button = document.getElementById(this.getItemRemoveButtonIdName(id));
		button.addEventListener('click', function(e) {
			thiz.onRemoveListRowItemClick(id);
		});
		let amtId = '#' + this.getRowItemAmountInputIdName(id);
		$(amtId).focusin(function(e) {
			this.select();
		});
		$(amtId).bind('input', function(e) {
			thiz.onChangeMouseUpKeyUp(id);
		});
		this.updateItemsList();
		this.updatePrice(id);
	}

	getListRowItemInnerHtml(item) {
		const description = Utils.resolveHtmlEntities(item.description);
		const len = this.list.length;
		const id = item.itemId;
		return '<div style="width:100%;border-top: 0px dotted ' + this.getRowItemDivBackgroundColor(len + 1) + ';"><div id="' + this.getRowItemNumberBoxIdName(id) + '" class="item-number-box" style="background-color:' + this.getRowItemNumberBoxColor(len) + '"><label id="list_number" class="list-number">' + len + '</label></div><input id="' + this.getRowItemAmountInputIdName(id)  + '" class="amount-input-small item-amount-input-no-border" value="' + item.amount + '" type="number" min="1" max="999"/><div class="descriptions"><label class="description" id="line1">' + '[' + item.code + '] ' + description  + '</label><label id="line2" class="sub-description">(₱' + item.sell_price + '/' + item.unit + ') ' + description + '</label></div><div class="divtd" style="float:right;vertical-align:middle;"><label id="' + this.getItemPriceIdName(id)  + '" class="item-price" style="display:block;float:left;">' + this.getTotalPrice(item) + '</label><button id="' + this.getItemRemoveButtonIdName(id) + '" class="delete-button" style="display:block;float:right;">x</button></div></div>';
	}

	onChangeMouseUpKeyUp(itemId) {
		let input = $('#' + this.getRowItemAmountInputIdName(itemId));
		let value = input.val();
		if (value == '') value = 1;
		this.updatePrice(itemId);
	}

	updatePrice(itemId) {
		let input = $('#' + this.getRowItemAmountInputIdName(itemId));
		let value = input.val();
		if (value == '') value = 1;
		let item = this.getItemById(itemId);
		item.amount = Number(value);
		let label = $('#' + this.getItemPriceIdName(itemId));
		label.text(this.getTotalPrice(item));
		this.updateTotalPrice();
	}

	updateTotalPrice() {
		this.grand_total_handler.update(this.list);
	}

	getItemRemoveButtonIdName(itemId) {
		return 'item_remove_button_' + itemId;
	}

	onRemoveListRowItemClick(itemId) {
		Utils.play(sfx_delete);
		const len = this.list.length;
		for (let i = 0; i < len; ++i) {
			let item = this.list[i];
			if (item.itemId != itemId) {
				continue;
			}
			const removed = this.list.splice(i,1);
			console.log("removed ==== " + removed[0] + ": " + removed[0].itemId);
			//alert("removed === " + removed[0] + ": " + removed[0].itemId);
			break;
		
		}
		this.removeListRowItem(itemId);
		this.updateItemsList();
		this.updateTotalPrice();
		$('#search_item_input').focus();
	}

	getItemById(itemId) {
		const len = this.list.length;
                for (let i = 0; i < len; ++i) {
                        let item = this.list[i];
			if (item.itemId == itemId) {
				return item;
			}
		}
		return null;
	}

	removeListRowItem(itemId) {
		let div = this.getListRowItemDiv(itemId);
		div.remove();
		return true;
	}

	updateItemsList() {
		let thiz = this;
		let i = 0;
		$('#' + this.getItemsListIdName() + ' > div').each(function () {
			let item = thiz.list[i];
			let div = document.getElementById(thiz.getRowItemNumberBoxIdName(item.itemId));
			div.style["background-color"] = thiz.getRowItemNumberBoxColor(i);
			this.style["background-color"] = thiz.getRowItemDivBackgroundColor(++i);
			div.firstChild.innerHTML = i;
		});
		$('#items_selection_div').css('padding-bottom', this.list.length > 0 ? 0 : 32);
	}

	get simpleList() {
		var items = [];
		for (let i = 0; i < this.list.length; ++i) {
			let itm = this.list[i];
			if (itm.amount > 0) {
				items.push({"itemId":itm.itemId, "amount":itm.amount});
			}
		}
		return items;
	}

	getTotalPrice(item) {
		return '₱' + (item.amount * item.sell_price).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
	}

	getListRowItemDiv(itemId) {
		return document.getElementById(this.getItemIdName(itemId))
	}

	getItemIdName(itemId) {
		return 'item_' + itemId;
	}

	getRowItemNumberBoxIdName(itemId) {
		return 'item_number_box_' + itemId;
	}

	getRowItemNumberBoxColor(row) {
		return (row%2==0 ? '#9ae17b' : '#6bba62');		
	}

	getRowItemAmountInputIdName(itemId) {
		return 'item_amount_input_' + itemId;
	}

	getRowItemDivBackgroundColor(row) {
		return (row%2==0 ? '#444444' : '#555555');
	}

	getItemsListIdName() {
		return 'items_list';
	}

	getItemPriceIdName(itemId) {
		return 'item_price_' + itemId;
	}
}
