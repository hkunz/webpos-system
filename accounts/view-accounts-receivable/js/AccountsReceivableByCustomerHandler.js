"use strict";

class AccountsReceivableByCustomerHandler {
	constructor() {
		this.customer = null;
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
		Utils.play(sfx_display); //chrome://flags/#autoplay-policy
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
		Utils.play(sfx_display);
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
				customer: customer,
				table_id: 'customer_table'
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
		let table = document.getElementById('customer_table');
		let thiz = this;
		this.table_handler.init(table, function(id) {
			thiz.onRowTableClick(id);
		});
	}

	onRowTableClick(id) {
		this.phpGetAccountsReceivable(id);
	}
}
