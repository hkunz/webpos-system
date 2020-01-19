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
		this.phpGetPaymentOptions();
	}

	reset() {
		$('#payment_method').prop("selectedIndex", 0);
		$('#instr_accname').val('');
		$('#instr_accnumber').val('');
		$('#instr_expiration').val('');
		$('#instr_remarks').val('');
		this.onPaymentTypeChange();
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
		let iscash = type === 'CASH';
		$('#payment_content_cash').css('display', iscash ? 'block' : 'none');
		$('#payment_content_instr').css('display', iscash ? 'none' : 'block');
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
		let t = this.selected_type;
		if (t == 'CASH') {
			return true;
		}
		let accname = $('#instr_accname').val().trim();
		let accnumber = $('#instr_accnumber').val().trim();
		let expiration = $('#instr_expiration').val();
		return accname != '' && accnumber != '' && expiration != '';
	}

	static get CHANGE() {
		return 'on-payment-method-change';
	}
}
