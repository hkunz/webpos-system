"use strict";

class Controller {

	constructor() {
		this.item_amount_input_handler = new ItemAmountInputPopupHandler();
		this.item_search_input_handler = new ProductSearchInputHandler();
		this.item_selects_list_handler = null;
		this.payment_input_handler = new PaymentInputHandler();	
		this.grand_total_handler = new GrandTotalViewHandler(this.payment_input_handler);
		try {
			this.item_selects_list_handler = new ItemSelectionListHandlerMobile(this.grand_total_handler);
		} catch(e) {
			this.item_selects_list_handler = new ItemSelectionListHandler(this.grand_total_handler);
		}
		this.customer_search_handler = new CustomerSearchInputHandler();
		this.sql_transaction_handler = new SqlTransactionHandler();

		let thiz = this;
		$(document).ready(function() {
			thiz.onDocumentReady();
		});
	}

	updateNextTransactionId() {
		const id = Utils.getPaddedTransactionId(this.sql_transaction_handler.getNextTransactionId());
		$('#transaction_id').text(id);
		$('#transaction_container').css('opacity', 1.0);
	}

	commitTransaction(json) {
		let thiz = this;
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
				thiz.prepareNextTransaction();
			}
		});
	}

	prepareNextTransaction() {
		this.sql_transaction_handler.phpGetNextTransactionId();
		this.reset();
	}

	reset() {
		document.getElementById('transaction_timestamp').value = '';
		document.getElementById('customer').value = '';
		document.getElementById('transaction_type').value = 'sale';
		this.payment_input_handler.reset();
		$('#timestamp_input').css('display','none');
		$('#use_currentdate_checkbox').prop('checked', true);
		$('#require_payment_checkbox').prop('checked', true);
		$("#transaction_timestamp").prop('disabled', true);
		$('#change_label').text('');
		this.item_selects_list_handler.reset();
	}

	onDocumentReady() {
		let thiz = this;
                $('#use_currentdate_checkbox').change(function(e) {
                        thiz.onCurrentDateCheckboxChange(e);
                });

		$("#eventdispatcher").on(EVENT_ITEM_AMOUNT_POPUP_INPUT_COMPLETE, function(e) {
			thiz.item_selects_list_handler.addItem({
				itemId:e.detail.itemId,
				description:e.detail.description,
				amount:e.detail.amount,
				code:e.detail.code,
				unit:e.detail.unit,
				sell_price:e.detail.sell_price,
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
			let items = thiz.item_selects_list_handler.simpleList;
			if (timestamp == '') {
				timestamp = DateUtils.getTimestamp(Date.now());
			}
			o['transaction_id'] = thiz.sql_transaction_handler.getNextTransactionId();
			o['customer'] = document.getElementById('customer').value;
			o['type'] = type;
			o['items'] = items;
			o['timestamp'] = timestamp;
			o['sub_total'] = sub_total;
			o['service_charge'] = service_charge;
			o['discount'] = discount;
			o['payment'] = cash;
			o['grand_total'] = grand_total;

			if (cash < grand_total || !thiz.payment_input_handler.isCash()) {
				thiz.payment_input_handler.populatePaymentDetails(o);
			}

			let json = JSON.stringify(o);
			console.log("commit transaction: " + json);
			if (type == "SALE" || type == "RESTOCK" || type == "LOSS" || type == "RETURN") {
				thiz.commitTransaction(json);
			}
		});

		$("#eventdispatcher").on(EVENT_NEXT_TRANSACTION_ID_LOADED, function(e) {
			thiz.updateNextTransactionId();
		});

		$('#eventdispatcher').on(ProductSearchInputHandler.ENTER_PRODUCT_EVENT, function(e) {
			thiz.onEnterProduct(e.detail.item, e.detail.quantity);
		});

		$("#search_item_input").focus();
	}

	onEnterProduct(i, qty) {
		ItemAmountInputPopupHandler.dispatchInputComplete(i.code, i.unit, i.item_id, i.description, qty, i.sell_price);
	}

	onCurrentDateCheckboxChange(e) {
		let checked = $('#use_currentdate_checkbox').is(":checked");
		console.log("check: " + checked);
		$("#transaction_timestamp").prop("disabled", checked);
		$('#timestamp_input').css('display', checked ? 'none' : 'block');
		if (checked) {
			document.getElementById('transaction_timestamp').value = '';
		} else {
			$("#transaction_timestamp").focus();
		}
	}
}
