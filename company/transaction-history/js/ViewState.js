"use strict";

class ViewState {
	static get INVALID() {
		return -1;
	}

	static get INIT() {
		return 0;
	}

	static get TRANSACTIONS_LIST() {
		return 1;
	}

	static get TRANSACTIONS_LIST_BY_CUSTOMER() {
		return 2;
	}

	static get TRANSACTIONS_DETAILS_LIST() {
		return 3;
	}

	static get TRANSACTION_PAY() {
		return 4;
	}

	static getStateValue(view) {
		if (view === "view_transactions") {
			return ViewState.TRANSACTIONS_LIST;
		} if (view === "view_transactions_by_customer") {
			return ViewState.TRANSACTIONS_LIST_BY_CUSTOMER;
		} if (view === "view_transactions_details") {
			return ViewState.TRANSACTIONS_DETAILS_LIST;
		}
		throw("invalid state for view: '" + view + "'");
		return ViewState.INVALID;
	}
}
