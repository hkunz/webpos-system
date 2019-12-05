"use strict";

class CustomerSearchInputHandler extends AwesompleteInputHandler {
	constructor() {
		super("customer", "../php/get-general-search-results.php");
		this.postvar1 = "customer";
		this.postvar2 = "items_transactions";
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
