"use strict";

class CustomerSelectionHandler {
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
			thiz.customer = e.originalEvent.text.value;
			thiz.onCustomerSelection();
		});
	}

	onCustomerSelection() {
		let thiz = this;
		Utils.play(sfx_click);
		$('#search_customer_input').val('');
		$.ajax({
			type: "POST",
			url: "php/get-accounts-receivable-by-customer.php" + Utils.getRandomUrlVar(),
			data: {
				currency: Utils.getCurrencySymbol(),
				customer: thiz.customer
			},
			success: function(data) {
				thiz.onShowCustomerTransactions(JSON.parse(data));
			}
		});
	}

	onShowCustomerTransactions(json) {
		alert("TODO: " + JSON.stringify(json));
	}
}
