"use strict";

class ItemSearchInputHandler extends AwesompleteInputHandler {
	constructor() {
		super("search_item_input", "php/get-search-results.php");
	}

	onAjaxSuccess(data, thiz) {
		const adata = data.split('||');
		const len = adata ? adata.length : 0;
		let list = [];
		for (let i = 0; i < len; ++i) {
			let json_str = adata[i];
			let description = this.getValue(json_str, "{{description}}");
			json_str = json_str.replace(/{{description\}\}.*\{\{description\}\}/g, '');
			if (json_str.length <= 1) continue;
			//console.log(i + " == '" + json_str + "'");
			let json = JSON.parse(json_str);
			json.description = description;
			const label = json.code + " [" + json.unit + "] " + description + " [₱" + json.price + "]";
			list.push({label:label, value:json, itemId:json.item_id});
		}
		thiz.input_element.list = list;
		thiz.input_element.evaluate();
	}

	getValue(item, value) {
		//return example output item.substring(item.indexOf("{{code}}") + 8, item.lastIndexOf("{{code}}"));
		return item.substring(item.indexOf(value) + value.length, item.lastIndexOf(value));
	}
}
