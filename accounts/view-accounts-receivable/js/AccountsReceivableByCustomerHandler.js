"use strict";

class AccountsReceivableByCustomerHandler {
	constructor() {
		this.customer = null;

		let thiz = this;
		$(document).ready(function(e) {
			thiz.onDocumentReady();
		});
	}

	onDocumentReady() {
		let thiz = this;
		$("#search_customer_input").on('awesomplete-selectcomplete', function(e) {
			thiz.customer = e.originalEvent.text;
			thiz.onCustomerSelection();
		});
		this.phpGetAccountsReceivable(null);
	}

	onCustomerSelection() {
		this.customer = $("#search_customer_input").val();
		$("#search_customer_input").val('');
		this.phpGetAccountsReceivable(this.customer);
	}

	phpGetAccountsReceivable(customer) {
		let thiz = this;
		let url = customer ? "get-accounts-receivable-by-transaction.php" : "get-accounts-receivable-by-customer.php";
		$.ajax({
			type: "POST",
			url: "php/" + url + Utils.getRandomUrlVar(),
			data: {
				currency: Utils.getCurrencySymbol(),
				customer: thiz.customer
			},
			success: function(data) {
				thiz.onShowAccountsReceivable(JSON.parse(data));
			}
		});
	}

	onShowAccountsReceivable(json) {
		//console.log("json: " + JSON.stringify(json));
		$('#table_container').html(json.content);
		$('#table_container').css('display','block');
	}
}
