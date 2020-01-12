"use strict";

class AccountsPayableHandler {
	constructor() {
		this.creditor = null;

		let thiz = this;
		$(document).ready(function(e) {
			thiz.onDocumentReady();
		});
	}

	onDocumentReady() {
		let thiz = this;
		$("#search_creditor_input").on('awesomplete-selectcomplete', function(e) {
			thiz.creditor = e.originalEvent.text.value;
			thiz.onCustomerSelection();
		});
		this.phpGetAccountsReceivable();
	}

	phpGetAccountsReceivable() {
		let thiz = this;
		$.ajax({
			type: "POST",
			url: "php/get-accounts-payable.php" + Utils.getRandomUrlVar(),
			data: {
			},
			success: function(data) {
				thiz.onShowAccountsReceivable(JSON.parse(data));
			}
		});
	}

	onShowAccountsReceivable(json) {
		$('#table_container').html(json.content);
		$('#table_container').css('display','block');
	}
}
