"use strict";

const creditor_input_handler = new CustomerSearchInputHandler("search_creditor_input", "../../php/get-general-search-results.php");
const creditor_selection_handler = new CreditorSelectionHandler();
const accounts_payable_handler = new AccountsPayableHandler();
const controller = new Controller();

$(document).ready(function() {
	$("#search_creditor_input").focus();
});
