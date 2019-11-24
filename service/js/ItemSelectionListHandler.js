"use strict";

class ItemSelectionListHandler {
	constructor() {
		$(document).on("change, mouseup, keyup", "#quantity", this.onChangeMouseUpKeyUp);
		this.grand_total_handler = new GrandTotalViewHandler();
		this.list = [];
	}

	reset() {
		sfx_delete.play();
		this.list = [];
		let div = document.getElementById(this.getItemsListIdName());
		div.innerHTML = '';
		this.updateTotalPrice(this);
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
			this.updatePrice(this, item.itemId);
			isnew = false;
		}
		if (isnew) {
			this.addListRowItem(item);
		}
		sfx_click.play();
		this.debug_dump();
	}

	debug_dump() {
		for (let i = 0; i < this.list.length; ++i) {
                        let item = this.list[i];
			console.log(" item[" + i + "] => " +
				item.itemId + ": " +
				item.unit + ": " +
				item.amount + ": " +
				item.price + ": " +
				item.code
			);
		}
	}

	addListRowItem(item) {
		let thiz = this;
		this.list.push(item);
		const id = item.itemId;
		const description = $('<textarea />').html(item.description).text();
		const len = this.list.length;
		let newdiv = document.createElement('div');

		newdiv.setAttribute('id', this.getItemIdName(id));
		newdiv.className = "divtr";
		newdiv.innerHTML = '<div style="width:100%;"><div id="' + this.getRowItemNumberBoxIdName(id) + '" class="item-number-box" style="background-color:' + this.getRowItemNumberBoxColor(len) + '"><label id="list_number" class="list-number">' + len + '</label></div><input id="' + this.getRowItemAmountInputIdName(id)  + '" class="amount-input-small item-amount-input-no-border" style="margin-top:4px;" value="' + item.amount + '" type="number" min="1" max="999"/><div class="descriptions"><label class="description" id="line1">' + '[' + item.code + '] ' + description  + '</label><label id="line2" class="sub-description">' + '[' + item.unit + '] ' + '[₱' + item.price + '] ' + description + '</label></div><div class="divtd" style="float:right;vertical-align:middle;"><label id="' + this.getItemPriceIdName(id)  + '" class="item-price" style="display:block;float:left;">' + this.getTotalPrice(item) + '</label><button id="' + this.getItemRemoveButtonIdName(id) + '" class="delete-button" style="display:block;float:right;">x</button></div></div>'
		document.getElementById(this.getItemsListIdName()).appendChild(newdiv);
		let button = document.getElementById(this.getItemRemoveButtonIdName(id));
		button.addEventListener('click', function(e) {
			thiz.onRemoveListRowItemClick(thiz, id);
		});
		$(document).on("change, mouseup, keyup", "#" + this.getRowItemAmountInputIdName(id), function(e) {
			thiz.onChangeMouseUpKeyUp(thiz, id);
		});
		$(document).on("click", "#" + this.getRowItemAmountInputIdName(id), function(e) {
                        thiz.onChangeMouseUpKeyUp(thiz, id);
                });
		thiz.updateItemsList();
		thiz.updatePrice(this, id);
	}		

	onChangeMouseUpKeyUp(thiz, itemId) {
		let input = $('#' + thiz.getRowItemAmountInputIdName(itemId));
		let value = input.val();
		if (value == '') value = 1;
		thiz.updatePrice(thiz, itemId);
	}

	updatePrice(thiz, itemId) {
		let input = $('#' + thiz.getRowItemAmountInputIdName(itemId));                                                                                                              let value = input.val();
		if (value == '') value = 1;
		let item = thiz.getItemById(itemId);
		item.amount = Number(value);
		let label = $('#' + thiz.getItemPriceIdName(itemId));
		label.text(thiz.getTotalPrice(item));
		thiz.updateTotalPrice(thiz);
	}

	updateTotalPrice(thiz) {
		thiz.grand_total_handler.update(thiz.list);
	}

	getItemRemoveButtonIdName(itemId) {
		return 'item_remove_button_' + itemId;
	}

	onRemoveListRowItemClick(thiz, itemId) {
		sfx_delete.play();
		const len = thiz.list.length;
		for (let i = 0; i < len; ++i) {
			let item = thiz.list[i];
			if (item.itemId != itemId) {
				continue;
			}
			const removed = thiz.list.splice(i,1);
			console.log("removed ==== " + removed[0] + ": " + removed[0].itemId);
			//alert("removed === " + removed[0] + ": " + removed[0].itemId);
			break;
		
		}
		thiz.removeListRowItem(itemId);
		thiz.updateItemsList();
		thiz.updateTotalPrice(thiz);
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
		$('#' + thiz.getItemsListIdName() + ' > div').each(function () {
			let item = thiz.list[i];
			let div = document.getElementById(thiz.getRowItemNumberBoxIdName(item.itemId));
			div.style["background-color"] = thiz.getRowItemNumberBoxColor(i);
			this.style["background-color"] = thiz.getRowItemDivBackgroundColor(++i);
			div.firstChild.innerHTML = i;
		});
	}

	get simpleList() {
		var items = [];
		for (let i = 0; i < this.list.length; ++i) {
			let itm = this.list[i];
			items.push({"itemId":itm.itemId, "amount":itm.amount});
		}
		return items;
	}

	getTotalPrice(item) {
		return '₱' + (item.amount * item.price).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
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
