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
			const item = adata[i];
			const code = this.getValue(item, "{{code}}");
			const itemId = this.getValue(item, "{{id}}");
			const description = this.getValue(item, "{{description}}");
			const price = this.getValue(item, "{{price}}");
			const unit = this.getValue(item, "{{unit}}");
			const category = this.getValue(item, "{{category}}");
			const brand_name = this.getValue(item, "{{brand_name}}");
			const general_name = this.getValue(item, "{{general_name}}");
			const supplier = this.getValue(item, "{{supplier}}");
			const stock = this.getValue(item, "{{stock}}");
			const label = code + " [" + unit + "] " + description + " [₱" + price + "]";
			const json =
				'{"itemId":"' + itemId +
				'","code":"' + code +
				'","price":"' + price +
				'","unit":"' + unit +
				'","category":"' + category +
				'","brand_name":"' + brand_name +
				'","general_name":"' + general_name +
				'","supplier":"' + supplier +
				'","stock":"' + stock +
				'","description":"' + description.replace(/\"/g, "&#34;").replace(/\'/g, "&#39;").replace(/&/g, "&amp;") +
			'"}';
				
			list.push({label:label, value:json, itemId:itemId});
		}
		thiz.input_element.list = list;
		thiz.input_element.evaluate();
	}

	getValue(item, value) {
		//return example output item.substring(item.indexOf("{{code}}") + 8, item.lastIndexOf("{{code}}"));
		return item.substring(item.indexOf(value) + value.length, item.lastIndexOf(value));
	}
}
