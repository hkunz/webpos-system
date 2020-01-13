"use strict";

class TransactionHistoryHandler {

	constructor() {
		this.customer = null;
		this.grand_total = null;
		this.transaction_id = null;
		this.transaction_total = null;
		this.current_state = ViewState.INIT;
		this.table_handler = new TableRowHandler();
		this.current_data = null;

		let thiz = this;
		$(document).ready(function(e) {
			thiz.onDocumentReady();
		});
		$(window).on('load', function(e) {
			thiz.onWindowLoad();
		});
	}

	onWindowLoad() {
		//Uncaught (in promise) DOMException: play() failed because the user didn't interact with the document first.
		//Utils.play(sfx_display); //chrome://flags/#autoplay-policy
	}

	onDocumentReady() {
		let thiz = this;
		$("#search_customer_input").on('awesomplete-selectcomplete', function(e) {
			thiz.customer = e.originalEvent.text;
			thiz.onCustomerSelection();
		});
		$('#back_button').click(function(e) {
			thiz.onBackButtonClick();
		});
		this.phpGetTransactionRecords('', ViewState.TRANSACTIONS_LIST);
		this.setSearchFocus();
	}

	onCustomerSelection() {
		this.transaction_id = null;
		this.customer = $("#search_customer_input").val();
		$("#search_customer_input").val('');
		this.phpGetTransactionRecords(this.customer, ViewState.TRANSACTIONS_LIST);
	}

	phpGetTransactionRecords(value, request_state) {
		let url = this.getRequestForState(request_state);
		let thiz = this;
		if (url == null) {
			return;
		}
		$.ajax({
			type: "POST",
			url: "php/" + url + Utils.getRandomUrlVar(),
			data: {
				value: value,
				table_id: 'scroll_table'
			},
			success: function(data) {
				thiz.onShowRecords(JSON.parse(data));
			}
		});
	}

	getRequestForState(state) {
		if (state == ViewState.TRANSACTIONS_LIST || state == ViewState.TRANSACTIONS_LIST_BY_CUSTOMER) {
			return "get-transaction-records.php";
		} if (state == ViewState.TRANSACTIONS_DETAILS_LIST) {
			return "get-transaction-details.php";
		}
		return 'unknown' + state;
	}

	onShowRecords(json) {
		let prevstate = this.current_state;
		this.current_data = json.data;
		this.setCurrentState(ViewState.getStateValue(json.view));
		let s = this.current_state;
		if (json.total_receivable) {
			this.grand_total = json.total_receivable;
		}
		//console.log("json: " + JSON.stringify(json));
		$('#table_container').html(json.content);
		$('#table_container').css('display','block');
		let table = document.getElementById('scroll_table');
		let thiz = this;
		this.table_handler.init(table, function(id) {
			let row = TableRowHandler.getRowByCellContent(id, json.data);
			thiz.onRowTableClick(id, row);
		});
		this.updateHeader();
		if (s == ViewState.TRANSACTIONS_DETAILS_LIST && prevstate == ViewState.TRANSACTIONS_LIST) {
			this.customer = null;
		}
	}

	setCurrentState(value) {
		Utils.play(sfx_display);
		this.current_state = value;
	}

	setSearchFocus() {
		$("#search_customer_input").focus();
	}

	onRowTableClick(value, row) {
		let s = this.current_state;
		//console.log("value: " + value);
		if (s === ViewState.TRANSACTIONS_LIST || s === ViewState.TRANSACTIONS_LIST_BY_CUSTOMER) {
			this.customer = row['Transactor'];
			this.transaction_id = Utils.getPaddedTransactionId(value);
			this.phpGetTransactionRecords(value, ViewState.TRANSACTIONS_DETAILS_LIST);
		} else if (s === ViewState.TRANSACTIONS_DETAILS_LIST) {
			let c = Utils.getCurrencySymbol();
			let msg = row['Product Description'] + ' {ID-' + row['item_id'] + '} (' + c + row['Unit Price'] + " X " + row['Qty'] + ' = ' + c + row['Cost'] + ')';
			Utils.play(sfx_display);
			console.log(msg);
			PopupUtils.create(msg);
			//this.setSearchFocus();
		}
	}

	updateHeader() {
		let c = this.customer;
		let t = this.transaction_id;
		let s = this.current_state
		let total = this.grand_total ? Utils.getAmountCurrencyText(this.grand_total) : null;
		$('#customer_label').text(c ? c : '');
		$('#customer_total').text(total ? total : '');
		$('#transaction_label').html('TRX-' + t);
		$('#back_button').css('display', s !== ViewState.TRANSACTIONS_LIST ? 'inline' : 'none');
		$('#customer_total_div').css('display', total == null ? 'none' : 'inline');
		$('#customer_div').css('display', c ? 'inline' : 'none');
		$('#transaction_td').css('display', t ? 'inline' : 'none');
	}

	onBackButtonClick() {
		let s = this.current_state;
		if (s === ViewState.TRANSACTIONS_DETAILS_LIST) {
			this.transaction_id = null;
			this.phpGetTransactionRecords(this.customer, this.customer ? ViewState.TRANSACTIONS_LIST_BY_CUSTOMER : ViewState.TRANSACTIONS_LIST);
		} else if (ViewState.TRANSACTIONS_LIST_BY_CUSTOMER) {
			this.customer = null;
			this.phpGetTransactionRecords(null, ViewState.TRANSACTIONS_LIST);
		} else {
			console.log("unhandled state: " + s);
		}
	}
}
