"use strict";

class Utils {
	static getRootName() {
		return "webpos-system";
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
		return "Macky's Printing Services";
	}

	static getStoreSubHeading() {
		return "Kunz, Inc.";
	}

	static getRandomValue() {
		return new Date().getTime();
	}

	static getRandomUrlVar() {
		return "?_=" + Utils.getRandomValue();
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

	static getCurrencyValueText(text) {
		const places = 2;
		return Number(text).toFixed(places);
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

	static getTransactionPrefix(id) {
		return 'TRX-' + id;
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
