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
});
