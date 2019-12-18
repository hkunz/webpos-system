"use strict";

class CustomerSearchInputHandler extends AwesompleteInputHandler {
	constructor(elementId, url) {
		super(elementId ? elementId : "customer", url ? url : "../php/get-general-search-results.php");
		this.postvar1 = "customer";
		this.postvar2 = "items_transactions";
		this.autoFirst = false;
	}

	onAjaxSuccess(data) {
		const adata = data.split(',');
		const len = adata ? adata.length : 0;
		let list = [];
		for (let i = 0; i < len; ++i) {
			const item = adata[i];
			const label = item;
			const json = '{}';
			list.push({label:label, value:json});
		}
		this.input_element.list = list;
		this.input_element.evaluate();
	}
}
