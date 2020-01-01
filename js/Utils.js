"use strict";

class Utils {
	static getRootName() {
		return "klebbys";
	}

	static getTimestamp(date) {
		const d = new Date(date);
		const month = '' + (d.getMonth() + 1);
		const day = '' + d.getDate();
		const year = d.getFullYear();
		const h = Utils.lpad(d.getHours(), '0', 2);
		const m = Utils.lpad(d.getMinutes(), '0', 2);
		const s = Utils.lpad(d.getSeconds(), '0', 2);
		const time = h + ":" + m + ":" + s;
		return year + '-' + month + '-' + day + ' ' + (h + ":" + m + ":" + s);
	}

	static lpad(text, pad, length) {
		let str = '';
		for (let i = 0; i < length; ++i) {
			str += pad;
		}
		return (str + text).slice(-length);
	}

	static getAmountCurrencyText(value) {
		return Utils.getCurrencySymbol() + Utils.getAmountText(value);
	}

	static getStoreHeading() {
		return "Klebby's Supplies &amp; Computer Services";
	}

	static getStoreSubHeading() {
		return "Kunz, Inc.";
	}

	// returns 'yyyy-MM-dd hh:mm:ss' formatted string
	static getTimestampNowUTC() {
		let m = new Date();
		return m.getUTCFullYear() +"-"+ (m.getUTCMonth()+1) +"-"+ m.getUTCDate() + " " + m.getUTCHours() + ":" + m.getUTCMinutes() + ":" + m.getUTCSeconds();
	}

	static getTimestampNow() {
		let m = new Date();
		return m.getFullYear() +"-"+ (m.getMonth()+1) +"-"+ m.getDate() + " " + m.getHours() + ":" + m.getMinutes() + ":" + m.getSeconds();
	}

	static getTimestampTodayStart() {
		let m = new Date();
		return m.getFullYear() +"-"+ (m.getMonth()+1) +"-"+ m.getDate() + " 00:00:00";
	}

	static getTimestampPreviousMonthStart() {
		return Utils.getTimestampPreviousMonthsStart(1);
	}

	static getTimestampPreviousMonthEnd() {
		return Utils.getTimestampPreviousMonthsEnd(1);
        }

	static getTimestampPreviousMonthsStart(months) {
		let m = new Date();
		m.setMonth(m.getMonth() - months);
		return m.getFullYear() + "-" + (m.getMonth()+1) +"-01 00:00:00";
	}

	static getTimestampPreviousMonthsEnd(months) {
		let m = new Date();
		m.setMonth(m.getMonth() - months);
		let d = new Date(m.getFullYear(), m.getMonth() + 1, 0);
		return d.getFullYear() + "-" + (d.getMonth()+1) + "-" + d.getDate() + " 23:59:59";
	}

	static getTimestampCurrentMonthStart() {
		let m = new Date();
		return m.getFullYear() +"-"+ (m.getMonth()+1) +"-01 00:00:00";
	}

	static getTimestampCurrentMonthEnd() {
		let m = new Date();
		let d = new Date(m.getFullYear(), m.getMonth() + 1, 0);
		return d.getFullYear() + "-" + (d.getMonth()+1) + "-" + d.getDate() + " 23:59:59";
	}

	static getTimestampMonthStart(month) {
		let m = new Date();
		return m.getFullYear() + "-" + ("0" + month).slice(-2) + "-01 00:00:00";
	}

	//month between 1-12; 1=January 12=December
	static getTimestampMonthEnd(month) {
		let m = new Date();
                let d = new Date(m.getFullYear(), month, 0);
                return d.getFullYear() + "-" + (d.getMonth()+1) + "-" + d.getDate() + " 23:59:59";
	}

	static getRandomUrlVar() {
		return "?_=" + new Date().getTime();
	}

	static getCurrencySymbol() {
		return "₱";
	}

	//Utils.not(event, 'e+-.'); //not exponent(e), plus(+), minus(-), or dot(.)
	static not(e, characters) {
		if (characters == null) characters = 'e+-';
		const input_element = e.target;
		const text = input_element.value;
		const k = e.key.toLowerCase();
		let r = true;
		let len = characters.length;
		for (let i = 0; i < len; ++i) {
			r = r && k !== characters.charAt(i);
		}
		return r;
	}

	static not_i(e) {
		return Utils.not(e, 'e+-.');
	}

	static resolveHtmlEntities(text) {
		return $('<textarea />').html(text).text();
	}

	//returns comma separated amount i.e. 1,000,000.00
	static getAmountText(value) {
		return Number.parseFloat(value).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
	}

	static getCurrencyValue(text) {
		const places = 2;
		return Number(Number(text).toFixed(places));
	}

	static play(sound) {
		sound.currentTime = 0;
		sound.play();
	}

	static getScrollWidth() {
		//return $('tbody::-webkit-scrollbar').css('width'); //not working
		return 12;
	}

	static getPaddedTransactionId(id) {
		return Utils.lpad(id, '0', 7);
	}

	static disableCutCopyPaste(input_id) {
		$(input_id).bind("cut copy paste", function(e) {
			e.preventDefault();
		});
		$(input_id).bind("contextmenu", function(e) {
			return false;
		});
	}

	static barcodeLength() {
		return 13;
	}
}

$.ajax({
	type: "POST",
	url: "/" + Utils.getRootName() + "/php/get-root-path.php" + Utils.getRandomUrlVar(),
	success: function(data) {
		//window.rootpath = data; not working
	}
});
