"use strict";

class DateUtils {

	static getTimestamp(date) {
		const d = new Date(date);
		const month = '' + (d.getMonth() + 1);
		const day = '' + d.getDate();
		const year = d.getFullYear();
		const h = DateUtils.lpad2(d.getHours());
		const m = DateUtils.lpad2(d.getMinutes());
		const s = DateUtils.lpad2(d.getSeconds());
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

	static lpad2(text) {
		return DateUtils.lpad(text, '0', 2);
	}

	// returns 'yyyy-MM-dd hh:mm:ss' formatted string
	static getTimestampNowUTC() {
		let m = new Date();
		let date = m.getUTCFullYear() + "-" + DateUtils.lpad2(m.getUTCMonth() + 1) + "-" + DateUtils.lpad2(m.getUTCDate());
		let time = DateUtils.lpad2(m.getUTCHours()) + ":" + DateUtils.lpad2(m.getUTCMinutes()) + ":" + DateUtils.lpad2(m.getUTCSeconds());
		return date + " " + time;
	}

	static getTimestampNow() {
		return DateUtils.getDateTimeString(new Date());
	}

	static getTimestampTodayStart() {
		let m = new Date();
		return DateUtils.getDateStringStart(m);
	}

	static getDateString(date) {
		return date.getFullYear() + '-' + DateUtils.lpad2(date.getMonth() + 1) + '-' + DateUtils.lpad2(date.getDate());
	}

	static getDateTimeString(date) {
		return DateUtils.getDateString(date) + ' ' + DateUtils.lpad2(date.getHours()) + ':' + DateUtils.lpad2(date.getMinutes()) + ':' + DateUtils.lpad2(date.getSeconds());
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

	static getDateStringStart(date) {
		return DateUtils.getDateString(date) + ' 00:00:00';
	}

	static getDateStringEnd(date) {
		return DateUtils.getDateString(date) + ' 23:59:59';
	}
}
