const item_amount_input_handler = new ItemAmountInputPopupHandler();
const item_search_input_handler = new ItemSearchInputHandler();
const item_selects_list_handler = new ItemSelectionListHandler();

$(document).ready(function() {
	$("#eventdispatcher").on(EVENT_ITEM_AMOUNT_POPUP_INPUT_COMPLETE, function(e) {
		item_selects_list_handler.addItem({
			itemId:e.detail.itemId,
			description:e.detail.description,
			amount:e.detail.amount,
			code:e.detail.code,
			unit:e.detail.unit,
			price:e.detail.price,
			supplier:e.detail.supplier,
			category:e.detail.category,
			brand_name:e.detail.brand_name
		});
	});
	$("#eventdispatcher").on(EVENT_COMMIT_TRANSACTION, function(e) {
		let sub_total = e.detail.sub_total;
		let discount = e.detail.discount;
		let grand_total = e.detail.grand_total;
		let cash = e.detail.cash;
		//alert("commit ==== " + sub_total + ": " + discount + ": " + grand_total + ": " + cash);
		let json = {};
                let items = item_selects_list_handler.simpleList;
                json['transaction_id'] = 6;
                json['customer'] = "harry";
                json['type'] = "SELL";
                json['items'] = items;
		alert("JSON == " + JSON.stringify(json));	
                //*/
                //{"transaction_id":"',5,'","customer":"chuyte","type","SELL","items":[{},{}]}'
	});
});
