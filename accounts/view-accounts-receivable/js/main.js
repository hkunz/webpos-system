"use strict";

const customer_input_handler = new CustomerSearchInputHandler("search_customer_input", "../../php/get-general-search-results.php");
const customer_selection_handler = new CustomerSelectionHandler();
const accounts_receivable_handler = new AccountsReceivableByCustomerHandler();
const controller = new Controller();

$(document).ready(function() {
	$("#search_customer_input").focus();
});
