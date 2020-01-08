"use strict";

class DateUtils {

	static getTimestamp(date) {
		const d = new Date(date);
		const month = '' + (d.getMonth() + 1);
		const day = '' + d.getDate();
		const year = d.getFullYear();
		const h = DateUtils.lpad(d.getHours(), '0', 2);
		const m = DateUtils.lpad(d.getMinutes(), '0', 2);
		const s = DateUtils.lpad(d.getSeconds(), '0', 2);
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

	// returns 'yyyy-MM-dd hh:mm:ss' formatted string
	static getTimestampNowUTC() {
		let m = new Date();
		return m.getUTCFullYear() +"-"+ (m.getUTCMonth()+1) +"-"+ m.getUTCDate() + " " + m.getUTCHours() + ":" + m.getUTCMinutes() + ":" + m.getUTCSeconds();
	}

	static getTimestampNow() {
		return DateUtils.getDateTimeString(new Date());
	}

	static getTimestampTodayStart() {
		let m = new Date();
		return DateUtils.getDateStringStart(m);
	}

	static getDateString(date) {
		return date.getFullYear() + '-' + (date.getMonth() + 1) + '-' + date.getDate();
	}

	static getDateTimeString(date) {
		return DateUtils.getDateString(date) + ' ' + date.getHours() + ':' + date.getMinutes() + ':' + date.getSeconds();
	}

	static getTimestampPreviousMonthStart() {
		return DateUtils.getTimestampPreviousMonthsStart(1);
	}

	static getTimestampPreviousMonthEnd() {
		return DateUtils.getTimestampPreviousMonthsEnd(1);
        }

	static getTimestampPreviousMonthsStart(months) {
		let m = new Date();
		m.setMonth(m.getMonth() - months);
		m.setDate(1);
		return DateUtils.getDateStringStart(m);
	}

	static getTimestampPreviousMonthsEnd(months) {
		let m = new Date();
		m.setMonth(m.getMonth() - months);
		let d = new Date(m.getFullYear(), m.getMonth() + 1, 0);
		return DateUtils.getDateStringEnd(d);
	}

	static getTimestampCurrentMonthStart() {
		let m = new Date();
		m.setDate(1);
		return DateUtils.getDateStringStart(m);
	}

	static getTimestampCurrentMonthEnd() {
		let m = new Date();
		let d = new Date(m.getFullYear(), m.getMonth() + 1, 0);
		return DateUtils.getDateStringEnd(d);
	}

	static getTimestampMonthStart(month) {
		let m = new Date();
		return m.getFullYear() + "-" + ("0" + month).slice(-2) + "-01 00:00:00";
	}

	static getDateStringStart(date) {
		return DateUtils.getDateString(date) + ' 00:00:00';
	}

	static getDateStringEnd(date) {
		return DateUtils.getDateString(date) + ' 23:59:59';
	}

	//month between 1-12; 1=January 12=December
	static getTimestampMonthEnd(month) {
		let m = new Date();
                let d = new Date(m.getFullYear(), month, 0);
                return DateUtils.getDateString(d) + " 23:59:59";
	}
}
