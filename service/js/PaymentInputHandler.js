"use strict";

class PaymentInputHandler {
	constructor() {
		let thiz = this;
		$(document).ready(function(e) {
			thiz.onDocumentReady();
		});
	}

	onDocumentReady() {
		let thiz = this;
		$('#cash_input').attr('placeholder', Utils.getCurrencySymbol());
		$('#payment_method').change(function(e) {
			thiz.onPaymentTypeChange(e);
		});
		$('#instr_accname').bind('input', function(e) {
			thiz.onInputChange();
		});
		$('#instr_accnumber').bind('input', function(e) {
			thiz.onInputChange();
		});
		$('#instr_expiration').bind('input', function(e) {
			thiz.onInputChange();
		});
		this.reset();
		this.phpGetPaymentOptions();
	}

	reset() {
		$('#payment_method').prop("selectedIndex", 0);
		$('#instr_accname').val('');
		$('#instr_accnumber').val('');
		$('#instr_expiration').val('');
		$('#instr_remarks').val('');
		this.setEnabled(false);
		this.onPaymentTypeChange();
	}

	get remarks() {
		let r = $('#instr_remarks').val().trim();
		return r == '' ? null : r;
	}

	get account_number() {
		let r = $('#instr_accnumber').val().trim();
		return (r == '' || this.isCash()) ? null : r;
	}

	get account_name() {
		let r = $('#instr_accname').val().trim();
		return (r == '' || this.isCash()) ? null : r;
	}

	get instrument_expiration() {
		let r = $('#instr_expiration').val();
		return (r == '' || this.isCash()) ? null : r;
	}

	setEnabled(en) {
	}

	onPaymentTypeChange() {
		let typeElem = $("#payment_method option:selected");
		let type = typeElem.text();
		if (this.selected_unit == type) {
			return;
		}
		this.setPaymentType(type);
		this.setLabels();
		this.onInputChange();
	}

	setPaymentType(type) {
		this.selected_type = type;
		$('#payment_content_cash').css('display', this.isCash() ? 'block' : 'none');
		$('#payment_content_instr').css('display', this.isCash() ? 'none' : 'block');
	}

	get selected_payment_method() {
		return this.selected_type;
	}

	isCash() {
		return this.selected_type === 'CASH';
	}

	populatePaymentDetails(json) {
		let accname = $('#instr_accname').val();
		let accnumber = $('#instr_accnumber').val();
		let expiration = $('#instr_expiration').val();
		let remarks = $('#instr_remarks').val();
		json['payment_method'] = this.selected_type;
		if (!this.isCash()) {
			if (accname != '') json['account_name'] = accname;
			if (accnumber != '') json['instrument_number'] = accnumber;
			if (expiration != '') json['instrument_expiration'] = expiration;
		}
		if (remarks != '') json['remarks'] = remarks;
	}

	setLabels() {
		let t = this.selected_type;
		if (t == 'MASTERCARD' || t == 'VISA') {
			$('#instr_accnumber').attr('placeholder', 'Account Number ...');
		} else if (t == 'CHECK') {
			$('#instr_accnumber').attr('placeholder', 'Check Number ...');
		}
	}

	phpGetPaymentOptions() {
		let thiz = this;
		$.ajax({
			type: "POST",
			url: "/" + Utils.getRootName() + "/php/get-general-options-list.php" + Utils.getRandomUrlVar(), 
			data: {
				table: 'payment_methods',
				field: 'payment_method',
				order: 'payment_method_id'
			},
			success: function(data) {
				thiz.onPaymentOptionsLoaded(data);
			}
		});
	}

	onPaymentOptionsLoaded(data) {
		var list = $('#payment_method');
		let values = data.split(',');
		values.forEach(function(item, index) {
			list.append(new Option(values[index], index));
		});
		list.prop("selectedIndex", 0);
		this.onPaymentTypeChange();
	}

	onInputChange() {
		let e = new Event(PaymentInputHandler.CHANGE);
		document.getElementById("eventdispatcher").dispatchEvent(e);
	}

	ready() {
		if (this.isCash()) {
			return true;
		}
		let accname = $('#instr_accname').val().trim();
		let accnumber = $('#instr_accnumber').val().trim();
		let expiration = $('#instr_expiration').val();
		return accname != '' && accnumber != '' && expiration != '' && DateUtils.isFutureDate(expiration);
	}

	static get CHANGE() {
		return 'on-payment-method-change';
	}
}
