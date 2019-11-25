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

function updateNextTransactionId() {
	const id = sql_transaction_handler.getNextTransactionId();
	$('#transaction_id').text(id);
}

function commitTransaction(thiz, json) {
	$.ajax({
		type: "POST",
		url: "php/commit-sale-transaction.php?_=" + new Date().getTime(),
		data: {
			json: json
		},
		success: function(data) {
			console.log("commit-sale-transaction success: " + data);
			const json = JSON.parse(data);
			const id = json.transaction_id;
			alert((json.success == 1 ? "Transaction '" + id + "' Complete" : "Transaction '" + id + "' Error"));
			thiz.prepareNextTransaction(thiz);
		}
	});
}

function prepareNextTransaction(thiz) {
	sql_transaction_handler.phpGetNextTransactionId();
	reset();
}

function reset() {
	document.getElementById('transaction_timestamp').value = '';
	document.getElementById('customer').value = '';
	document.getElementById('transaction_type').value = 'sale';
	$('#require_payment_checkbox').prop('checked', true);
	item_selects_list_handler.reset();
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
		let service_charge = e.detail.service_charge;
		let discount = e.detail.discount;
		let grand_total = e.detail.grand_total;
		let cash = e.detail.cash;
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
		o['sub_total'] = sub_total;
		o['service_charge'] = service_charge;
		o['discount'] = discount;
		o['payment'] = cash;
		o['grand_total'] = grand_total;
		let json = JSON.stringify(o);
		console.log("commit transaction: " + json);
		if (type == "SALE" || type == "RESTOCK") {
			commitTransaction(thiz, json);
		}
	});

	$("#eventdispatcher").on(EVENT_NEXT_TRANSACTION_ID_LOADED, function(e) {
		updateNextTransactionId();
	});

	updateNextTransactionId();
});
