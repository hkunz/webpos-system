"use strict"

class GrandTotalViewHandler {
	constructor() {
		let thiz = this;
		this.list = null;
		this.sub_total = 0;
		this.grand_total = 0;
		this.cash = 0;
		this.discount = 0;
		this.service_charge = 0;
		$(document).ready(function() {
			$(document).on("change, mouseup, keyup", "#" + thiz.getCashInputIdName(), function(e) {
				thiz.onChangeMouseUpKeyUp(thiz);
			});
			$(document).on("click", "#" + thiz.getCashInputIdName(), function(e) {
				thiz.onChangeMouseUpKeyUp(thiz);
			});
			$(document).on("change, mouseup, keyup", "#" + thiz.getServiceChargeIdName(), function(e) {
				thiz.onChangeMouseUpKeyUp(thiz);
				thiz.update();
                        });
			$(document).on("click", "#" + thiz.getServiceChargeIdName(), function(e) {
				thiz.onChangeMouseUpKeyUp(thiz);
				thiz.update();
                        });
			$('#' + thiz.getCommitButtonIdName()).click(function(e) {
				thiz.onCommitButtonClick(thiz);
			});
			$('#require_payment_checkbox').change(function(e) {
				thiz.onRequirePaymentCheckChange(e, thiz);
			});
		});
	}

	setList(list) {
		this.list = list;
	}

	update() {
		const len = this.list.length;
		let total = 0;
		for (let i = 0; i < len; ++i) {
			let item = this.list[i];
			total = total + (item.amount * item.price);
		}
		$('#sub_total_value').text(GrandTotalViewHandler.getAmountText(total));
		this.sub_total = total;
		this.discount = 0; //TODO: implement discount
		this.service_charge = this.getServiceChargeAmount(this);
		this.grand_total = total + this.service_charge - this.discount;
		$('#discount_value').text(GrandTotalViewHandler.getAmountText(this.discount));
		$('#grand_total_value').text(GrandTotalViewHandler.getAmountText(this.grand_total));
		const nothingToPay = (this.grand_total == 0);
		let cashInput = document.getElementById(this.getCashInputIdName());
		let serviceChInput = document.getElementById(this.getServiceChargeIdName());
		cashInput.disabled = nothingToPay;
		serviceChInput.disabled = nothingToPay;
		if (nothingToPay) {
			serviceChInput.value = '';
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
		let ready = thiz.isPaymentReady(thiz);
		thiz.cash = cash;
		$('#' + this.getCashChangeIdName()).text(ready ? changeText : '');
		thiz.updateCommitButton(thiz);
	}

	getServiceChargeAmount(thiz) {
		let amount_text = $('#' + thiz.getServiceChargeIdName()).val();
		let amount = (amount_text == '' ? 0 : Number(amount_text));
		return amount;
	}

	updateCommitButton(thiz) {
		let requirePay = thiz.isRequirePaymentChecked();
		let ready = thiz.isPaymentReady(thiz);
		let commitButton = document.getElementById(thiz.getCommitButtonIdName());
		let enableCommit = ready || (!requirePay && thiz.sub_total > 0);
		commitButton.disabled = !enableCommit;
	}

	isPaymentReady(thiz) {
		let cashInput = document.getElementById(this.getCashInputIdName());
		let cash = (cashInput.value == '' ? 0 : Number(cashInput.value));
		let change = cash - this.grand_total;
		let ready = (cashInput.value != '' && change >= 0);
		return ready;
	}

	onCommitButtonClick(thiz) {
		let commitButton = document.getElementById(this.getCommitButtonIdName());                                                                                                   commitButton.disabled = true;
		sfx_commit_transaction.currentTime = 0;
		sfx_commit_transaction.play();

		const e = new CustomEvent(EVENT_COMMIT_TRANSACTION, {
                        detail: {
				sub_total:thiz.sub_total,
				service_charge:thiz.service_charge,
				discount:thiz.discount,
                                grand_total:thiz.grand_total,
				cash:thiz.cash
                        }
                });
                document.getElementById("eventdispatcher").dispatchEvent(e);
	}

	onRequirePaymentCheckChange(e, thiz) {
		thiz.updateCommitButton(thiz);
	}

	isRequirePaymentChecked() {
		return $('#require_payment_checkbox').is(":checked");
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

	getServiceChargeIdName() {
		return 'service_charge_input';
	}
}
