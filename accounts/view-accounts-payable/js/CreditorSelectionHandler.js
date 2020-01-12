"use strict";

class CreditorSelectionHandler {
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
	}

	onCustomerSelection() {
		let thiz = this;
		Utils.play(sfx_click);
		$('#search_creditor_input').val('');
		$.ajax({
			type: "POST",
			url: "php/get-accounts-payable.php" + Utils.getRandomUrlVar(),
			data: {
				creditor: thiz.creditor
			},
			success: function(data) {
				thiz.onShowCreditorTransactions(JSON.parse(data));
			}
		});
	}

	onShowCreditorTransactions(json) {
		alert("TODO: " + JSON.stringify(json));
	}
}
