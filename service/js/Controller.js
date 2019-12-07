"use strict"

class Controller {
	constructor() {
		let thiz = this;
		$(document).ready(function() {
			thiz.init();
		});
	}

	init() {
		let thiz = this;
		$('#use_currentdate_checkbox').change(function(e) {
			thiz.onCurrentDateCheckboxChange(e);
		});
		
	}

	onCurrentDateCheckboxChange(e) {
		let checked = $('#use_currentdate_checkbox').is(":checked");
		console.log("check: " + checked);
		$("#transaction_timestamp").prop("disabled", checked);
		if (checked) {
			document.getElementById('transaction_timestamp').value = '';
		} else {
			$("#transaction_timestamp").focus();
		}
	}
}
