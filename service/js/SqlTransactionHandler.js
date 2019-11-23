"use strict"

class SqlTransactionHandler {

	constructor() {
		this.transactionId = -1;
		this.phpGetNextTransactionId();
	}

	phpGetNextTransactionId() {
		let thiz = this;
		$.ajax({
			type: "POST",
			url: "php/get-next-transaction-id.php?_=" + new Date().getTime(),
			success: function(data) {
				thiz.onAjaxSuccess(data, thiz);
			}
		});
	}

	onAjaxSuccess(data, thiz) {
		thiz.transactionId = data;
		thiz.getNextTransactionId();
        }

	getNextTransactionId() {
		if (this.transactionId <= 0) {
			throw("invalid transaction id: " + this.transactionId);
		}
		return this.transactionId;
	}
}

