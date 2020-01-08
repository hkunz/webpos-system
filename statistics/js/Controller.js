"use strict";

class Controller {
	constructor() {
		let thiz = this;
		$(document).ready(function(e) {
			thiz.onDocumentReady();
		});
	}

	phpGetTotalRevenue(url, dateStart, dateEnd, callback, id) {
		$.ajax({
			type: "POST",
			url: url + Utils.getRandomUrlVar(),
				data: {
				dateStart: dateStart,
				dateEnd: dateEnd
			},
			success: function(data) {
				let value = Utils.getAmountCurrencyText(data ? data : 0);
				console.log(url + ": " + value);
				callback(value, id);
			}
		});
	}

	setLabel(value, id) {
		$(id).text(value);
	}

	onDocumentReady() {
		const DATE_START = '2019-01-01';
		const DATE_TIME_START = DATE_START + ' 00:00:00';

		let thiz = this;
		let date_input = document.getElementById('date_picker');
		let date_input_start = document.getElementById('date_picker_start');
		let date_input_end = document.getElementById('date_picker_end');
		date_input.valueAsDate = new Date();
		date_input_start.valueAsDate = new Date(DATE_START);
		date_input_end.valueAsDate = new Date();

		date_input.onchange = function(e) {
			thiz.onDateChange(this.value);
		};

		date_input_start.onchange = function(e) {
			thiz.onRevenueDateChange();
		};

		date_input_end.onchange = function(e) {
			thiz.onRevenueDateChange();
		};

		const now = DateUtils.getTimestampNow();
		const currMonthStart = DateUtils.getTimestampCurrentMonthStart();
		const currMonthEnd = DateUtils.getTimestampCurrentMonthEnd();
		const prevMonthStart = DateUtils.getTimestampPreviousMonthStart();
		const prevMonthEnd = DateUtils.getTimestampPreviousMonthEnd();
		const prev2MonthStart = DateUtils.getTimestampPreviousMonthsStart(2);
		const prev2MonthEnd = DateUtils.getTimestampPreviousMonthsEnd(2);

		this.populateRevenueCustomDay(new Date());
		this.populateRevenueCustomRange(DATE_TIME_START, now);
		this.populateRevenues(currMonthStart, currMonthEnd, '#revenue_total_curr_month', '#revenue_total_curr_month_prepaid', '#revenue_total_curr_month_products', '#revenue_total_curr_month_services', '#profit_curr_month_prepaid', '#profit_curr_month_products', '');
		this.populateRevenues(prevMonthStart, prevMonthEnd, '#revenue_total_prev_month', '#revenue_total_prev_month_prepaid', '#revenue_total_prev_month_products', '#revenue_total_prev_month_services', '#profit_prev_month_prepaid', '#profit_prev_month_products', '');
		this.populateRevenues(prev2MonthStart, prev2MonthEnd, '#revenue_total_prev2_month', '#revenue_total_prev2_month_prepaid', '#revenue_total_prev2_month_products', '#revenue_total_prev2_month_services', '#profit_prev2_month_prepaid', '#profit_prev2_month_products', '');
	}

	populateRevenueCustomDay(date) {
		let start = DateUtils.getDateStringStart(date);
		let end = DateUtils.getDateStringEnd(date);
		this.populateRevenues(start, end, '#revenue_today', '#revenue_today_prepaid', '#revenue_today_products', '#revenue_today_services', '#profit_today_prepaid', '#profit_today_products', '');
	}

	populateRevenueCustomRange(start, end) {
		this.populateRevenues(start, end, '#revenue_total', '#revenue_total_prepaid', '#revenue_total_products', '#revenue_total_services', '#profit_total_prepaid', '#profit_total_products', '');
	}

	populateRevenues(start, end, rTotalId, rPrepaidId, rProductsId, rServicesId, pPrepaidId, pProductsId, pServicesId) {
		this.phpGetTotalRevenue("php/get-total-revenue.php", start, end, this.setLabel, rTotalId);
		this.phpGetTotalRevenue("php/get-total-revenue-prepaid-load.php", start, end, this.setLabel, rPrepaidId);
		this.phpGetTotalRevenue("php/get-total-revenue-products.php", start, end, this.setLabel, rProductsId);
		this.phpGetTotalRevenue("php/get-total-revenue-services.php", start, end, this.setLabel, rServicesId);
		this.phpGetTotalRevenue("php/get-total-profit-prepaid-load.php", start, end, this.setLabel, pPrepaidId);
		this.phpGetTotalRevenue("php/get-total-profit-products.php", start, end, this.setLabel, pProductsId);
		//this.phpGetTotalRevenue("php/get-total-profit-services.php", start, end, this.setLabel, pServicesId);
	}

	onDateChange(value) {
		let today = new Date();
		let d = new Date(value);
		let same = today.getFullYear() == d.getFullYear() && today.getMonth() == d.getMonth() && today.getDate() == d.getDate();
		this.populateRevenueCustomDay(d);
		$('#revenue_today_label').text(same ? "Today's Revenue:" : "Revenue on:");
	}

	onRevenueDateChange() {
		let today = new Date();
                let from = new Date(document.getElementById('date_picker_start').value);
		let to = new Date(document.getElementById('date_picker_end').value);
                let sfr = DateUtils.getDateStringStart(from);
		let sto = DateUtils.getDateStringEnd(to);
		this.populateRevenueCustomRange(sfr, sto);
	}
}
