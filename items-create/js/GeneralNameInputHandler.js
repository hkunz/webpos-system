"use strict";

class GeneralNameInputHandler extends AwesompleteInputHandler {
	constructor() {
		super("general_name_input", "php/get-search-results.php");
		this.postvar1 = "general_name";
		this.autoFirst = false;
	}

	onAjaxSuccess(data, thiz) {
		const adata = data.split(',');
		const len = adata ? adata.length : 0;
		let list = [];
		for (let i = 0; i < len; ++i) {
			const item = adata[i];
			const label = item;
			const json = '{}';
			list.push({label:label, value:json});
		}
		thiz.input_element.list = list;
		thiz.input_element.evaluate();
	}
}
