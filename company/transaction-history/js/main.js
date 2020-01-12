"use strict";

const customer_input_handler = new CustomerSearchInputHandler("search_customer_input", "php/get-customer-search-results.php", true);
const transaction_history_handler = new TransactionHistoryHandler();
const controller = new Controller();
