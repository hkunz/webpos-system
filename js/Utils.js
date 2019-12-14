"use strict";

class Utils {
	constructor() {}
	static getAmountText(value) {
                return Number.parseFloat(value).toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }

	static getServerName() {
		return "localhost";
	}

	static getRootPath() {
		return "//" + Utils.getServerName() + "/klebbys/";
	}

	static getTimestamp(date) {
		const d = new Date(date);
		const month = '' + (d.getMonth() + 1);
		const day = '' + d.getDate();
		const year = d.getFullYear();
		const h = addZero(d.getHours());
		const m = addZero(d.getMinutes());
		const s = addZero(d.getSeconds());
		const time = h + ":" + m + ":" + s;
		return year + '-' + month + '-' + day + ' ' + (h + ":" + m + ":" + s);
	}

	static getAmountPesoText(value) {
		return '₱' + Utils.getAmountText(value);
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

	static notE(e) {
		return e.keyCode !== 69;
	}
}
