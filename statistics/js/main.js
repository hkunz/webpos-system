"use strict";

const DATE_START = '2019-01-01 00:00:00';

function phpGetTotalRevenue(url, dateStart, dateEnd, callback, id) {
        $.ajax({
                type: "POST",
                url: url + Utils.getRandomUrlVar(),
		data: {
			dateStart: dateStart,
			dateEnd: dateEnd
		},
                success: function(data) {
			let value = Utils.getAmountPesoText(data ? data : 0);
                        console.log(url + ": " + value);
			callback(value, id);
                }
        });
}

function setLabel(value, id) {
	$(id).text(value);
}

$(document).ready(function() {
	const now = Utils.getTimestampNow();
	const currMonthStart = Utils.getTimestampCurrentMonthStart();
	const currMonthEnd = Utils.getTimestampCurrentMonthEnd();
	const prevMonthStart = Utils.getTimestampPreviousMonthStart();
	const prevMonthEnd = Utils.getTimestampPreviousMonthEnd();
	const prev2MonthStart = Utils.getTimestampPreviousMonthsStart(2);
	const prev2MonthEnd = Utils.getTimestampPreviousMonthsEnd(2);

	phpGetTotalRevenue("php/get-total-revenue.php", DATE_START, now, setLabel, '#revenue_total');
	phpGetTotalRevenue("php/get-total-revenue-prepaid-load.php", DATE_START, now, setLabel, '#revenue_total_prepaid');
	phpGetTotalRevenue("php/get-total-revenue-services.php", DATE_START, now, setLabel, '#revenue_total_services');
	phpGetTotalRevenue("php/get-total-revenue-products.php", DATE_START, now, setLabel, '#revenue_total_products');
	phpGetTotalRevenue("php/get-total-profit-prepaid-load.php", DATE_START, now, setLabel, '#profit_total_prepaid');
	phpGetTotalRevenue("php/get-total-profit-products.php", DATE_START, now, setLabel, '#profit_total_products');

        phpGetTotalRevenue("php/get-total-revenue.php", currMonthStart, currMonthEnd, setLabel, '#revenue_total_curr_month');
        phpGetTotalRevenue("php/get-total-revenue-prepaid-load.php", currMonthStart, currMonthEnd, setLabel, '#revenue_total_curr_month_prepaid');
        phpGetTotalRevenue("php/get-total-revenue-services.php", currMonthStart, currMonthEnd, setLabel, '#revenue_total_curr_month_services');
        phpGetTotalRevenue("php/get-total-revenue-products.php", currMonthStart, currMonthEnd, setLabel, '#revenue_total_curr_month_products');
	phpGetTotalRevenue("php/get-total-profit-prepaid-load.php", currMonthStart, currMonthEnd, setLabel, '#profit_curr_month_prepaid');
        phpGetTotalRevenue("php/get-total-profit-products.php", currMonthStart, currMonthEnd, setLabel, '#profit_curr_month_products');

	phpGetTotalRevenue("php/get-total-revenue.php", prevMonthStart, prevMonthEnd, setLabel, '#revenue_total_prev_month');
        phpGetTotalRevenue("php/get-total-revenue-prepaid-load.php", prevMonthStart, prevMonthEnd, setLabel, '#revenue_total_prev_month_prepaid');
        phpGetTotalRevenue("php/get-total-revenue-services.php", prevMonthStart, prevMonthEnd, setLabel, '#revenue_total_prev_month_services');
        phpGetTotalRevenue("php/get-total-revenue-products.php", prevMonthStart, prevMonthEnd, setLabel, '#revenue_total_prev_month_products');
	phpGetTotalRevenue("php/get-total-profit-prepaid-load.php", prevMonthStart, prevMonthEnd, setLabel, '#profit_prev_month_prepaid');
        phpGetTotalRevenue("php/get-total-profit-products.php", prevMonthStart, prevMonthEnd, setLabel, '#profit_prev_month_products');

	phpGetTotalRevenue("php/get-total-revenue.php", prev2MonthStart, prev2MonthEnd, setLabel, '#revenue_total_prev2_month');
        phpGetTotalRevenue("php/get-total-revenue-prepaid-load.php", prev2MonthStart, prev2MonthEnd, setLabel, '#revenue_total_prev2_month_prepaid');
        phpGetTotalRevenue("php/get-total-revenue-services.php", prev2MonthStart, prev2MonthEnd, setLabel, '#revenue_total_prev2_month_services');
        phpGetTotalRevenue("php/get-total-revenue-products.php", prev2MonthStart, prev2MonthEnd, setLabel, '#revenue_total_prev2_month_products');
	phpGetTotalRevenue("php/get-total-profit-prepaid-load.php", prev2MonthStart, prev2MonthEnd, setLabel, '#profit_prev2_month_prepaid');
        phpGetTotalRevenue("php/get-total-profit-products.php", prev2MonthStart, prev2MonthEnd, setLabel, '#profit_prev2_month_products');
});
