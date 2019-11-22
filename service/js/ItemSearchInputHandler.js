"use strict";

class ItemSearchInputHandler {
	constructor() {
		let thiz = this;
		thiz.search_item_input = null;
		$(document).ready(function() {
			thiz.onInitialise(thiz);
		});
	}

	onInitialise(thiz) {
		thiz.search_item_input = new Awesomplete(document.getElementById("search_item_input"), {
			minChars:2,
			maxItems:20,
			autoFirst:true,
			replace: function(suggestion) {
				this.input.value = suggestion.label;
			}
		});
		$("#search_item_input").keyup(function(e) {thiz.onItemSearchInputChange(e, thiz)});
	}

	onItemSearchInputChange(e, thiz) {
		let name = $("#search_item_input").val();
		if (name == "") {
			//$("#items_dropdown").html("");
		} else {
			$.ajax({
				type: "POST",
				url: "ajax.php?_=" + new Date().getTime(),
				data: {
					search: name
				},
				success: function(data) {
					thiz.onAjaxSuccess(data, thiz);
				}
			});
		}
	}

	onAjaxSuccess(data, thiz) {
		//https://github.com/LeaVerou/awesomplete/issues/17207
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
			const label = code + " [" + unit + "] " + description + " [₱" + price + "] {{" + itemId + "}}";
			const json =
				'{"itemId":"' + itemId +
				'","code":"' + code +
				'","price":"' + price +
				'","unit":"' + unit +
				'","category":"' + category +
				'","brand_name":"' + brand_name +
				'","general_name":"' + general_name +
				'","supplier":"' + supplier +
				'","description":"' + description.replace(/\"/g, "&#34;").replace(/\'/g, "&#39;").replace(/&/g, "&amp;") +
			'"}';
				
			list.push({label:label, value:json, itemId:itemId});
		}
		thiz.search_item_input.list = list;
		thiz.search_item_input.evaluate();
	}

	getValue(item, value) {
		//return example output item.substring(item.indexOf("{{code}}") + 8, item.lastIndexOf("{{code}}"));
		return item.substring(item.indexOf(value) + value.length, item.lastIndexOf(value));
	}
}
