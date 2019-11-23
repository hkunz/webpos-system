const item_amount_input_handler = new ItemAmountInputPopupHandler();
const item_search_input_handler = new ItemSearchInputHandler();
const item_selects_list_handler = new ItemSelectionListHandler();
const sql_transaction_handler = new SqlTransactionHandler();

let thiz = this;

function getTimestamp(date) {
	const d = new Date(date);
	const month = '' + (d.getMonth() + 1);
	const day = '' + d.getDate();
	const year = d.getFullYear();
	const h = addZero(d.getHours());
	const m = addZero(d.getMinutes());
	const s = addZero(d.getSeconds());
	const time = h + ":" + m + ":" + s;
	return year + '-' + month + '-' + day + ' ' + (h + ":" + m + ":" + s);
}

function addZero(i) {
	if (i < 10) i = "0" + i;
  	return i;
}


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
		let typeElement = document.getElementById('transaction_type');
		let type = typeElement.options[typeElement.selectedIndex].text;
		let o = {};
		let timestamp = document.getElementById('transaction_timestamp').value.replace('T',' ');
                let items = item_selects_list_handler.simpleList;
		if (timestamp == '') {
			timestamp = getTimestamp(Date.now());
		}
                o['transaction_id'] = sql_transaction_handler.getNextTransactionId();
                o['customer'] = document.getElementById('customer').value;
                o['type'] = type;
                o['items'] = items;
		o['timestamp'] = timestamp;
		let json = JSON.stringify(o);
		alert("value == " + json);
	});
});
