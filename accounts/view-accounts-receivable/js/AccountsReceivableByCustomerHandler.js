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
			thiz.customer = e.originalEvent.text.value;
			thiz.onCustomerSelection();
		});
		this.phpGetAccountsReceivable();
	}

	phpGetAccountsReceivable() {
		let thiz = this;
		$.ajax({
			type: "POST",
			url: "php/get-accounts-receivable-by-customer.php" + Utils.getRandomUrlVar(),
			data: {
				currency: Utils.getCurrencySymbol()
			},
			success: function(data) {
				thiz.onShowAccountsReceivable(JSON.parse(data));
			}
		});
	}

	onShowAccountsReceivable(json) {
		$('#table_container').html(json.content);
		// Change the selector if needed
		/*
		var $table = $('customer_table'), $bodyCells = $table.find('tbody tr:first').children(), colWidth;

		// Get the tbody columns width array
		colWidth = $bodyCells.map(function() {
    			return $(this).width();
		}).get();

		// Set the width of thead columns
		$table.find('thead tr').children().each(function(i, v) {
			$(v).width(colWidth[i]);
		});
		//*/
		$('#table_container').css('display','block');
	}
}
