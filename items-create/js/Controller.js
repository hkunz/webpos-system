"use strict";

class Controller {
	constructor() {
		let thiz = this;
		$(document).ready(function() {
			thiz.phpGetEnumSelectionList('unit_select', 'items', 'unit', thiz.populateUnitSelection, 'pc');
			thiz.phpGetEnumSelectionList('category_select', 'items', 'category', thiz.populateUnitSelection, null);
			thiz.phpGetEnumSelectionList('supplier_select', 'items', 'supplier_name', thiz.populateUnitSelection, null);
		});
	}

	phpGetEnumSelectionList(element, table, field, callback, selection) {
		const url = "php/get-enum-selection-list.php";
		$.ajax({
			type: "POST",
			data: {
				table:table,
				field:field
			},
			url: url + Utils.getRandomUrlVar(),
			success: function(json) {
				console.log(url + ": " + json);
				let values = JSON.parse(json);
				callback(element, values, selection);
			}
		});
	}


	populateUnitSelection(element, values, selection) {
		var list = $('#' + element);
		values.forEach(function(item, index){
			list.append(new Option(values[index]));
		});
		list.val(selection);
	}
}
