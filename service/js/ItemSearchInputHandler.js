"use strict";

class ItemSearchInputHandler extends AwesompleteInputHandler {
	constructor() {
		super("search_item_input", Utils.getRootPath() + "service/php/get-search-results.php");
	}

	onAjaxSuccess(data) {
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
			const label = json.code + " [" + json.unit + "] " + description + " [₱" + json.sell_price + "]";
			list.push({label:label, value:json, itemId:json.item_id});
		}
		this.input_element.list = list;
		this.input_element.evaluate();
	}

	getValue(item, value) {
		//return example output item.substring(item.indexOf("{{code}}") + 8, item.lastIndexOf("{{code}}"));
		return item.substring(item.indexOf(value) + value.length, item.lastIndexOf(value));
	}
}
