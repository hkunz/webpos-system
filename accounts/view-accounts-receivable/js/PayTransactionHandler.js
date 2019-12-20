"use strict";

class PayTransactionHandler {
	constructor() {
		
	}

	update(payment, total) {
		$('#payment_input').attr('placeholder', payment);
		$('#payment_input').attr('max', Math.ceil(total);
		$('#payment_input').val(payment);
		$('#total_amount_label').text();
	}
}
