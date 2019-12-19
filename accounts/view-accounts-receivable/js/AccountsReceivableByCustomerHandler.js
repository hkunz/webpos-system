"use strict";

class AccountsReceivableByCustomerHandler {

	constructor() {
		this.customer = null;
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
		this.phpGetAccountsReceivable(null, ViewState.CUSTOMERS_LIST);
	}

	onCustomerSelection() {
		this.customer = $("#search_customer_input").val();
		$("#search_customer_input").val('');
		this.phpGetAccountsReceivable(this.customer, ViewState.TRANSACTIONS_LIST);
	}

	phpGetAccountsReceivable(customer, request_state) {
		let url = this.getRequestForState(request_state);
		let thiz = this;
		if (url == null) {
			return;
		}
		$.ajax({
			type: "POST",
			url: "php/" + url + Utils.getRandomUrlVar(),
			data: {
				customer: customer,
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
		}
	}

	onShowAccountsReceivable(json) {
		this.current_state = ViewState.getStateValue(json.view);
		Utils.play(sfx_display);
		//console.log("json: " + JSON.stringify(json));
		$('#table_container').html(json.content);
		$('#table_container').css('display','block');
		let table = document.getElementById('scroll_table');
		let thiz = this;
		this.table_handler.init(table, function(id) {
			thiz.onRowTableClick(id);
		});
	}

	onRowTableClick(value) {
		if (this.current_state == ViewState.CUSTOMERS_LIST) {
			this.phpGetAccountsReceivable(value, ViewState.TRANSACTIONS_LIST);
		}
	}
}
