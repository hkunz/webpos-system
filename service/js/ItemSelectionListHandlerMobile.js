"use strict";

class ItemSelectionListHandlerMobile extends ItemSelectionListHandler {
	constructor() {
		super();
	}

	addListRowItem(item) {
		this.list.push(item);
		const id = item.itemId;
		const description = Utils.resolveHtmlEntities(item.description);
		const len = this.list.length;
		let newdiv = document.createElement('div');

		newdiv.setAttribute('id', this.getItemIdName(id));
		newdiv.className = "divtr";
		newdiv.innerHTML = '<div style="width:100%;overflow-x:hidden;' + this.getRowItemDivBackgroundColor(len + 1) + ';"><table cellspacing="0" cellpadding="0"><tr><td><div id="' + this.getRowItemNumberBoxIdName(id) + '" class="item-number-box" style="background-color:' + this.getRowItemNumberBoxColor(len) + ';margin-right:2px;"><label id="list_number" class="list-number" style="font-size:14px;margin-top:2px;">' + len + '</label></div></td><td><input id="' + this.getRowItemAmountInputIdName(id)  + '" class="amount-input-small item-amount-input-no-border" style="margin-top:0px;width:40px;height:25px;font-size:13px;" value="' + item.amount + '" type="number" min="1" max="999"/></td><td><div class="descriptions"><label class="description" style="white-space:wrap;font-size:16px;" id="line1">' + '[' + item.code + '] ' + description  + '</label></div></table></td></td></div>'
		//<div class="divtd" style="float:right;vertical-align:middle;"><label id="' + this.getItemPriceIdName(id)  + '" class="item-price" style="display:block;float:left;">' + this.getTotalPrice(item) + '</label></div>
		//<button id="' + this.getItemRemoveButtonIdName(id) + '" class="delete-button" style="display:block;float:right;">x</button>
		//<label id="line2" class="sub-description">(₱' + item.sell_price + '/' + item.unit + ') ' + description + '</label>
		let thiz = this;
		document.getElementById(this.getItemsListIdName()).appendChild(newdiv);
		/*let button = document.getElementById(this.getItemRemoveButtonIdName(id));
		button.addEventListener('click', function(e) {
			thiz.onRemoveListRowItemClick(id);
		});*/
		$('#' + this.getRowItemAmountInputIdName(id)).bind('input', function(e) {
			thiz.onChangeMouseUpKeyUp(id);
		});
		this.updateItemsList();
		this.updatePrice(id);
	}
}
