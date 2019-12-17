"use strict"

class SqlTransactionHandler {

	constructor() {
		let thiz = this;
		this.transactionId = -1;
		$(document).ready(function(e) {
			thiz.onDocumentReady();
		});
	}

	onDocumentReady() {
		this.phpGetNextTransactionId();
	}

	phpGetNextTransactionId() {
		let thiz = this;
		$.ajax({
			type: "POST",
			url: "php/get-next-transaction-id.php?_=" + new Date().getTime(),
			success: function(data) {
				thiz.onAjaxSuccess(data);
			}
		});
	}

	onAjaxSuccess(data) {
		this.transactionId = data;
		const transactionId = this.getNextTransactionId();
		const e = new CustomEvent(EVENT_NEXT_TRANSACTION_ID_LOADED, {
                        detail: {
                                transactionId:transactionId
                        }
                });
                document.getElementById("eventdispatcher").dispatchEvent(e);
        }

	getNextTransactionId() {
		if (this.transactionId <= 0) {
			throw("invalid transaction id: " + this.transactionId);
		}
		return this.transactionId;
	}
}

