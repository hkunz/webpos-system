"use strict";

class AccountsReceivableByCustomerHandler {

	constructor() {
		this.customer = null;
		this.grand_total = null;
		this.transaction_id = null;
		this.current_state = ViewState.INIT;
		this.table_handler = new TableRowHandler();

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
		this.phpGetAccountsReceivable(null, ViewState.CUSTOMERS_LIST);
	}

	onCustomerSelection() {
		this.transaction_id = null;
		this.updateHeader();
		this.customer = $("#search_customer_input").val();
		$("#search_customer_input").val('');
		this.phpGetAccountsReceivable(this.customer, ViewState.TRANSACTIONS_LIST);
	}

	phpGetAccountsReceivable(value, request_state) {
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
				thiz.onShowAccountsReceivable(JSON.parse(data));
			}
		});
	}

	getRequestForState(state) {
		if (state == ViewState.CUSTOMERS_LIST) {
			return "get-accounts-receivable-by-customer.php";
		} if (state == ViewState.TRANSACTIONS_LIST) {
			return "get-accounts-receivable-by-transaction.php";
		} if (state == ViewState.TRANSACTIONS_DETAILS_LIST) {
			return "get-accounts-receivable-by-transaction-details.php";
		}
		return '';
	}

	onShowAccountsReceivable(json) {
		this.current_state = ViewState.getStateValue(json.view);
		if (json.total_receivable) {
			this.grand_total = json.total_receivable;
		}
		Utils.play(sfx_display);
		//console.log("json: " + JSON.stringify(json));
		$('#table_container').html(json.content);
		$('#table_container').css('display','block');
		let table = document.getElementById('scroll_table');
		let thiz = this;
		this.table_handler.init(table, function(id) {
			let row = thiz.getRowByCellContent(id, json.data);
			thiz.onRowTableClick(id, row);
		});
		$("#search_customer_input").focus();
		this.updateHeader();
	}

	getRowByCellContent(id, rows) {
		let len = rows ? rows.length : 0;
		for (let i = 0; i < len; ++i) {
			let row = rows[i];
			for (let key in row) {
				if (row.hasOwnProperty(key) && row[key] === id) {
					return row;
				}
			}
		}
		return null;
	}

	onRowTableClick(value, row) {
		let s = this.current_state;
		if (s === ViewState.CUSTOMERS_LIST) {
			this.customer = value;
			this.transaction_id = null;
			this.phpGetAccountsReceivable(value, ViewState.TRANSACTIONS_LIST);
		} else if (s === ViewState.TRANSACTIONS_LIST) {
			this.transaction_id = value;
			this.phpGetAccountsReceivable(value, ViewState.TRANSACTIONS_DETAILS_LIST);
		} else if (s === ViewState.TRANSACTIONS_DETAILS_LIST) {
			console.log(row['item_id'] + ": " + row['Product Description']);
		}
		this.updateHeader();
	}

	updateHeader() {
		let c = this.customer;
		let t = this.transaction_id;
		let total = this.grand_total ? Utils.getAmountCurrencyText(this.grand_total) : null;
		$('#customer_label').text(c ? c : '');
		$('#customer_total').text(total ? total : '');
		$('#transaction_label').text('TRX-' + t);
		$('#back_button').css('display', c ? 'inline' : 'none');
		$('#colon_label').css('display', c ? 'inline' : 'none');
		$('#customer_div').css('display', c ? 'inline' : 'none');
		$('#transaction_td').css('display', t ? 'inline' : 'none');
	}

	onBackButtonClick() {
		let s = this.current_state;
		if (s === ViewState.TRANSACTIONS_DETAILS_LIST) {
			this.transaction_id = null;
			this.phpGetAccountsReceivable(this.customer, ViewState.TRANSACTIONS_LIST);
		} else if (s === ViewState.TRANSACTIONS_LIST) {
			this.customer = null;
			this.phpGetAccountsReceivable(null, ViewState.CUSTOMERS_LIST);
		}
		this.updateHeader();
	}
}
