"use strict";

class AccountsReceivableByCustomerHandler {

	constructor() {
		this.customer = null;
		this.grand_total = null;
		this.transaction_id = null;
		this.transaction_total = null;
		this.current_state = ViewState.INIT;
		this.table_handler = new TableRowHandler();
		this.current_data = null;
		this.pay_trx_handler = new PayTransactionHandler();

		let thiz = this;
		$(document).ready(function(e) {
			thiz.onDocumentReady();
		});
		$(window).on('load', function(e) {
			thiz.onWindowLoad();
		});
	}

	onWindowLoad() {
		//Uncaught (in promise) DOMException: play() failed because the user didn't interact with the document first.
		//Utils.play(sfx_display); //chrome://flags/#autoplay-policy
	}

	onDocumentReady() {
		let thiz = this;
		$("#search_customer_input").on('awesomplete-selectcomplete', function(e) {
			thiz.customer = e.originalEvent.text;
			thiz.onCustomerSelection();
		});
		$('#back_button').click(function(e) {
			thiz.onBackButtonClick();
		});
		$("#eventdispatcher").on('table-button-click', function(e) {
			thiz.onTableButtonClick(e.detail);
		});
		this.phpGetAccountsReceivable(null, ViewState.CUSTOMERS_LIST);
		this.setSearchFocus();
	}

	onCustomerSelection() {
		this.transaction_id = null;
		this.customer = $("#search_customer_input").val();
		$("#search_customer_input").val('');
		this.phpGetAccountsReceivable(this.customer, ViewState.TRANSACTIONS_LIST);
	}

	phpGetAccountsReceivable(value, request_state) {
		let url = this.getRequestForState(request_state);
		let thiz = this;
		if (url == null) {
			return;
		}
		$.ajax({
			type: "POST",
			url: "php/" + url + Utils.getRandomUrlVar(),
			data: {
				value: value,
				table_id: 'scroll_table'
			},
			success: function(data) {
				thiz.onShowAccountsReceivable(JSON.parse(data));
			}
		});
	}

	getRequestForState(state) {
		if (state == ViewState.CUSTOMERS_LIST) {
			return "get-accounts-receivable-by-customer.php";
		} if (state == ViewState.TRANSACTIONS_LIST) {
			return "get-accounts-receivable-by-transaction.php";
		} if (state == ViewState.TRANSACTIONS_DETAILS_LIST) {
			return "get-accounts-receivable-by-transaction-details.php";
		}
		return '';
	}

	onShowAccountsReceivable(json) {
		this.current_data = json.data;
		this.setCurrentState(ViewState.getStateValue(json.view));
		if (json.total_receivable) {
			this.grand_total = json.total_receivable;
		}
		//console.log("json: " + JSON.stringify(json));
		$('#table_container').html(json.content);
		$('#table_container').css('display','block');
		let table = document.getElementById('scroll_table');
		let thiz = this;
		this.table_handler.init(table, function(id) {
			let row = thiz.getRowByCellContent(id, json.data);
			thiz.onRowTableClick(id, row);
		});
		//this.setSearchFocus();
		this.updateHeader();
	}

	setCurrentState(value) {
		Utils.play(sfx_display);
		this.current_state = value;
	}

	setSearchFocus() {
		$("#search_customer_input").focus();
	}

	getRowByCellContent(id, rows) {
		let len = rows ? rows.length : 0;
		for (let i = 0; i < len; ++i) {
			let row = rows[i];
			for (let key in row) {
				if (row.hasOwnProperty(key) && row[key] === id) {
					return row;
				}
			}
		}
		return null;
	}

	onRowTableClick(value, row) {
		let s = this.current_state;
		if (s === ViewState.CUSTOMERS_LIST) {
			this.customer = value;
			this.transaction_id = null;
			this.phpGetAccountsReceivable(value, ViewState.TRANSACTIONS_LIST);
		} else if (s === ViewState.TRANSACTIONS_LIST) {
			this.transaction_id = Utils.getPaddedTransactionId(value);
			this.transaction_total = row["Receivable"];
			this.phpGetAccountsReceivable(value, ViewState.TRANSACTIONS_DETAILS_LIST);
		} else if (s === ViewState.TRANSACTIONS_DETAILS_LIST) {
			let c = Utils.getCurrencySymbol();
			let msg = row['Product Description'] + ' {ID-' + row['item_id'] + '} (' + c + row['Unit Price'] + " X " + row['Qty'] + ' = ' + c + row['Cost'] + ')';
			Utils.play(sfx_display);
			console.log(msg);
			PopupUtils.create(msg);
			//this.setSearchFocus();
		}
	}

	onTableButtonClick(detail) {
		let thiz = this;
		let s = this.current_state;
		if (s === ViewState.TRANSACTIONS_LIST) {
			this.setCurrentState(ViewState.TRANSACTION_PAY);
			//this.setSearchFocus();
			let transaction_id = Number(detail.first_cellvalue);
			$('#table_container').html('');
			$('#table_container').load('html/pay-transaction-div.html', function(e) {
				thiz.onPayTransactionViewLoad(detail);
			});
		}
	}

	onPayTransactionViewLoad(detail) {
		let thiz = this;
		this.transaction_id = detail.first_cellvalue;
		let row = this.getRowByCellContent(this.transaction_id, this.current_data);
		this.transaction_total = row["Receivable"];
		let curr_payment = row["Payment"];
		$('#payment_input').attr('max', this.transaction_total);
		$('#payment_input').attr('placeholder', curr_payment);
		$('#payment_input').val(curr_payment);
		$('#payment_input').bind('input', function(e) {
			let pay = Number($('#payment_input').val());
			let rec = Number(thiz.transaction_total);
			let cur = Number(curr_payment);
			let enable = pay > curr_payment && pay <= rec;
			document.getElementById('update_payment_button').disabled = !enable;
		});
		this.updateHeader();
		$('#transaction_id').text($('#transaction_label').text());
	}

	updateHeader() {
		let c = this.customer;
		let t = this.transaction_id;
		let total = this.grand_total ? Utils.getAmountCurrencyText(this.grand_total) : null;
		$('#customer_label').text(c ? c : '');
		$('#customer_total').text(total ? total : '');
		$('#transaction_label').html('TRX-' + t + ' <b style="color:#33FF33">' + Utils.getAmountCurrencyText(this.transaction_total) + "</b>");
		$('#back_button').css('display', c ? 'inline' : 'none');
		$('#customer_total_div').css('display', total == null ? 'none' : 'inline');
		$('#customer_div').css('display', c ? 'inline' : 'none');
		$('#transaction_td').css('display', t ? 'inline' : 'none');
	}

	onBackButtonClick() {
		let s = this.current_state;
		if (s === ViewState.TRANSACTIONS_DETAILS_LIST || s === ViewState.TRANSACTION_PAY) {
			this.transaction_id = null;
			this.phpGetAccountsReceivable(this.customer, ViewState.TRANSACTIONS_LIST);
		} else if (s === ViewState.TRANSACTIONS_LIST) {
			this.customer = null;
			this.phpGetAccountsReceivable(null, ViewState.CUSTOMERS_LIST);
		} else {
			console.log("unhandled state: " + s);
		}
	}
}
