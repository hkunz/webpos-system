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
		const DATE_START = '2019-01-01 00:00:00';

		let thiz = this;
		let date_input = document.getElementById('date_picker');
		date_input.valueAsDate = new Date();

		date_input.onchange = function(e) {
			thiz.onDateChange(this.value);
		}

		const now = Utils.getTimestampNow();
		const todayStart = Utils.getTimestampTodayStart();
		const currMonthStart = Utils.getTimestampCurrentMonthStart();
		const currMonthEnd = Utils.getTimestampCurrentMonthEnd();
		const prevMonthStart = Utils.getTimestampPreviousMonthStart();
		const prevMonthEnd = Utils.getTimestampPreviousMonthEnd();
		const prev2MonthStart = Utils.getTimestampPreviousMonthsStart(2);
		const prev2MonthEnd = Utils.getTimestampPreviousMonthsEnd(2);

		this.populateRevenueCustomDay(todayStart, now);
		this.populateRevenues(DATE_START, now, '#revenue_total', '#revenue_total_prepaid', '#revenue_total_products', '#revenue_total_services', '#profit_total_prepaid', '#profit_total_products', '');
		this.populateRevenues(currMonthStart, currMonthEnd, '#revenue_total_curr_month', '#revenue_total_curr_month_prepaid', '#revenue_total_curr_month_products', '#revenue_total_curr_month_services', '#profit_curr_month_prepaid', '#profit_curr_month_products', '');
		this.populateRevenues(prevMonthStart, prevMonthEnd, '#revenue_total_prev_month', '#revenue_total_prev_month_prepaid', '#revenue_total_prev_month_products', '#revenue_total_prev_month_services', '#profit_prev_month_prepaid', '#profit_prev_month_products', '');
		this.populateRevenues(prev2MonthStart, prev2MonthEnd, '#revenue_total_prev2_month', '#revenue_total_prev2_month_prepaid', '#revenue_total_prev2_month_products', '#revenue_total_prev2_month_services', '#profit_prev2_month_prepaid', '#profit_prev2_month_products', '');
	}

	populateRevenueCustomDay(start, end) {
		this.populateRevenues(start, end, '#revenue_today', '#revenue_today_prepaid', '#revenue_today_products', '#revenue_today_services', '#profit_today_prepaid', '#profit_today_products', '');
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
		let s = d.getFullYear() + '-' + (d.getMonth() + 1) + '-' + d.getDate() + ' ';
		let same = today.getFullYear() == d.getFullYear() && today.getMonth() == d.getMonth() && today.getDate() == d.getDate();
		this.populateRevenueCustomDay(s + '00:00:00', s + '23:59:59');
		$('#revenue_today_label').text(same ? "Today's Revenue:" : "Revenue on:");
	}
}
