"use strict";

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
			thiz.onDocumentReady();
		});
	}

	onDocumentReady() {
		let thiz = this;
		$('#' + this.getCashInputIdName()).bind('input', function(e) {
			thiz.onChangeMouseUpKeyUp();
		});
		$('#' + this.getServiceChargeIdName()).bind('input', function(e) {
			thiz.onChangeMouseUpKeyUp();
			thiz.update();
		});
		$("#customer").keyup(function(e) {
			thiz.update();
		});
		$('#' + thiz.getCommitButtonIdName()).click(function(e) {
			thiz.onCommitButtonClick();
		});
		$('#require_payment_checkbox').change(function(e) {
			thiz.onRequirePaymentCheckChange(e);
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
			total = total + (item.amount * item.sell_price);
		}
		$('#sub_total_value').text(GrandTotalViewHandler.getAmountText(total));
		this.sub_total = total;
		this.discount = 0; //TODO: implement discount
		this.service_charge = this.getServiceChargeAmount();
		this.grand_total = len > 0 ? total + this.service_charge - this.discount : 0;
		$('#discount_value').text(GrandTotalViewHandler.getAmountText(this.discount));
		$('#grand_total_value').text(GrandTotalViewHandler.getAmountText(this.grand_total));
		const nothingToPay = len <= 0; //(this.grand_total == 0);
		let cashInput = document.getElementById(this.getCashInputIdName());
		let serviceChInput = document.getElementById(this.getServiceChargeIdName());
		cashInput.disabled = nothingToPay;
		serviceChInput.disabled = nothingToPay;
		if (nothingToPay) {
			serviceChInput.value = '';
			cashInput.value = '';
			$('#' + this.getCashChangeIdName()).text('');
		}
		this.updateChangeAmount();
	}

	onChangeMouseUpKeyUp() {
		this.updateChangeAmount();
	}

	updateChangeAmount() {
		let cashInput = document.getElementById(this.getCashInputIdName());
		let cash = cashInput.value == '' ? 0 : Number(cashInput.value);
		let change = cash - this.grand_total;
		let changeText = Utils.getCurrencySymbol() + ' ' + GrandTotalViewHandler.getAmountText(change);
		let ready = this.isPaymentReady(this);
		this.cash = cash;
		$('#' + this.getCashChangeIdName()).text(ready ? changeText : '');
		//$('#cash_change_tr').css('display', ready ? 'table-row' : 'none');
		this.updateCommitButton();
	}

	getServiceChargeAmount() {
		let amount_text = $('#' + this.getServiceChargeIdName()).val();
		let amount = (amount_text == '' ? 0 : Number(amount_text));
		return amount;
	}

	updateCommitButton() {
		let customer = $('#customer').val().trim();
		let requirePay = this.isRequirePaymentChecked();
		let ready = this.isPaymentReady();
		let commitButton = document.getElementById(this.getCommitButtonIdName());
		let enableCommit = ready || (!requirePay && this.sub_total > 0 && customer != '');
		commitButton.disabled = !enableCommit;
	}

	isPaymentReady() {
		let cashInput = document.getElementById(this.getCashInputIdName());
		let cash = (cashInput.value == '' ? 0 : Number(cashInput.value));
		let change = cash - this.grand_total;
		let ready = (cashInput.value != '' && change >= 0);
		return ready;
	}

	onCommitButtonClick() {
		let commitButton = document.getElementById(this.getCommitButtonIdName());                                                                                                   commitButton.disabled = true;
		Utils.play(sfx_commit_transaction);

		const e = new CustomEvent(EVENT_COMMIT_TRANSACTION, {
			detail: {
				sub_total:this.sub_total,
				service_charge:this.service_charge,
				discount:this.discount,
                                grand_total:this.grand_total,
				cash:this.cash
                        }
                });
                document.getElementById("eventdispatcher").dispatchEvent(e);
	}

	onRequirePaymentCheckChange(e) {
		this.updateCommitButton();
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
