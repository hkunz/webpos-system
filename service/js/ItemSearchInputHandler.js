"use strict";

class ItemSearchInputHandler extends AwesompleteInputHandler {
	constructor() {
		super("search_item_input", "/" + Utils.getRootName() + "/service/php/get-search-results.php");
	}

	onAjaxSuccess(data) {
		const adata = this.getDataArray(data);
		const len = adata ? adata.length : 0;
		let list = [];
		for (let i = 0; i < len; ++i) {
			let json = this.parseItem(adata[i]);
			if (json === null) continue;
			const label = json.code + " [" + json.unit + "] " + json.description + " [" + Utils.getCurrencySymbol() + json.sell_price + "]";
			list.push({label:label, value:json, itemId:json.item_id});
		}
		this.input_element.list = list;
		this.input_element.evaluate();
	}

	getFirstItem(data) {
		const adata = this.getDataArray(data);
		const len = adata ? adata.length : 0;
		for (let i = 0; i < len; ++i) {
			let json = this.parseItem(adata[i]);
			if (json !== null) {
				return json;
			}
		}
		return null;
	}

	getDataArray(data) {
		return data.split('||');
	}

	parseItem(json_str) {
		let description = this.getValue(json_str, "{{description}}");
		json_str = json_str.replace(/{{description\}\}.*\{\{description\}\}/g, '');
		if (json_str.length <= 1) {
			return null;
		}
		let json = JSON.parse(json_str);
		json.description = description;
		return json;
	}

	getValue(item, value) {
		//return example output item.substring(item.indexOf("{{code}}") + 8, item.lastIndexOf("{{code}}"));
		return item.substring(item.indexOf(value) + value.length, item.lastIndexOf(value));
	}
}
