"use strict";

class ItemSelectionListHandlerMobile extends ItemSelectionListHandler {
	constructor(grand_total_view_handler) {
		super(grand_total_view_handler);
	}

	getListRowItemInnerHtml(item) {
                const description = Utils.resolveHtmlEntities(item.description);
                const len = this.list.length;
                const id = item.itemId;
                return '<div style="width:100%;overflow-x:hidden;' + this.getRowItemDivBackgroundColor(len + 1) + ';"><table cellspacing="0" cellpadding="0"><tr><td><div id="' + this.getRowItemNumberBoxIdName(id) + '" class="item-number-box" style="background-color:' + this.getRowItemNumberBoxColor(len) + ';margin-right:2px;"><label id="list_number" class="list-number" style="font-size:14px;margin-top:2px;">' + len + '</label></div></td><td><input id="' + this.getRowItemAmountInputIdName(id)  + '" class="amount-input-small item-amount-input-no-border" style="margin-top:0px;width:40px;height:25px;font-size:13px;" value="' + item.amount + '" type="number" min="1" max="999"/></td><td><label class="description" style="padding-left:3px;white-space:wrap;font-size:16px;overflow:visible;" id="line1">' + '[' + item.unit + '] ' + description  + ' [' + item.code + ' — ' + Utils.getCurrencySymbol() + item.sell_price + '/' + item.unit + '] </label></td><td valign="top" style="padding-top:3px;"><label id="' + this.getItemPriceIdName(id)  + '" style="width:100%;color:#ffff55;font-size:16px;font-weight:bold;padding-right:15px;padding-left:15px;">' + this.getTotalPrice(item) + '</label></td><td><button id="' + this.getItemRemoveButtonIdName(id) + '" class="delete-button" style="display:block;float:right;">x</button></td></table></div>';
	}
}
