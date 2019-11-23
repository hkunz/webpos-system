"use strict"

class GrandTotalViewHandler {
	constructor() {
		let thiz = this;
		this.sub_total = 0;
		this.grand_total = 0;
		this.cash = 0;
		this.discount = 0;
		$(document).ready(function() {
			$(document).on("change, mouseup, keyup", "#" + thiz.getCashInputIdName(), function(e) {
                        	thiz.onChangeMouseUpKeyUp(thiz);
                	});
                	$(document).on("click", "#" + thiz.getCashInputIdName(), function(e) {
                        	thiz.onChangeMouseUpKeyUp(thiz);
                	});
			$('#' + thiz.getCommitButtonIdName()).click(function(e) {
				thiz.onCommitButtonClick(thiz);
			});
		});
	}

	update(list) {
		const len = list.length;
		let total = 0;
		for (let i = 0; i < len; ++i) {
			let item = list[i];
			total = total + (item.amount * item.price);
		}
		$('#sub_total_value').text(GrandTotalViewHandler.getAmountText(total));
		this.sub_total = total;
		this.discount = 0; //TODO: implement discount
		this.grand_total = total - this.discount;
		$('#discount_value').text(GrandTotalViewHandler.getAmountText(this.discount));
		$('#grand_total_value').text(GrandTotalViewHandler.getAmountText(this.grand_total));
		const nothingToPay = (this.grand_total == 0);
		let cashInput = document.getElementById(this.getCashInputIdName());
		cashInput.disabled = nothingToPay;
		if (nothingToPay) {
			cashInput.value = '';
			$('#' + this.getCashChangeIdName()).text('');
		}
		this.updateChangeAmount(this);
	}

	onChangeMouseUpKeyUp(thiz) {
		thiz.updateChangeAmount(thiz);
	}

	updateChangeAmount(thiz) {
		let cashInput = document.getElementById(this.getCashInputIdName());
		let cash = cashInput.value == '' ? 0 : Number(cashInput.value);
		let change = cash - this.grand_total;
		let changeText = '₱ ' + GrandTotalViewHandler.getAmountText(change);
		let ready = change > 0;
		thiz.cash = cash;
		$('#' + this.getCashChangeIdName()).text(ready ? changeText : '');
		let paymentReady = (changeText != '');
		let commitButton = document.getElementById(this.getCommitButtonIdName());                                                                                                   commitButton.disabled = !ready;
	}

	onCommitButtonClick(thiz) {
		sfx_commit_transaction.currentTime = 0;
		sfx_commit_transaction.play();

		const e = new CustomEvent(EVENT_COMMIT_TRANSACTION, {
                        detail: {
				sub_total:thiz.sub_total,
				discount:thiz.discount,
                                grand_total:thiz.grand_total,
				cash:thiz.cash
                        }
                });
                document.getElementById("eventdispatcher").dispatchEvent(e);
	}


	static getAmountText(value) {
		return value.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
	}

	getCashInputIdName() {
		return 'cash_input';
	}

	getCashChangeIdName() {
		return 'cash_change';
	}

	getCommitButtonIdName() {
		return 'commit_transaction_button';
	}
}
