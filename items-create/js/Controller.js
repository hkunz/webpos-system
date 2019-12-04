"use strict";

class Controller {
	constructor() {
		let thiz = this;
		this.json = {};
		$(document).ready(function() {
			thiz.phpGetEnumSelectionList('unit_select', 'items', 'unit', thiz.populateUnitSelection, 'pc');
			thiz.phpGetEnumSelectionList('category_select', 'items', 'category', thiz.populateUnitSelection, null);
			thiz.phpGetEnumSelectionList('supplier_select', 'items', 'supplier_name', thiz.populateUnitSelection, null);
			$(":input").change(function(e) {
				thiz.onFormChange(e);
			});
			$(":input").keyup(function(e) {
				thiz.onFormChange(e);
			});
			$("#create_item_button").click(function(e) {
                                thiz.onCreateItemButtonClick(this);
                        });
		});
	}

	onCreateItemButtonClick(button) {
		let thiz = this;
		let json_str = JSON.stringify(this.json);
		button.disabled = true;
                sfx_commit_transaction.currentTime = 0;
                sfx_commit_transaction.play();
		const url = "php/create-new-item.php";

		console.log("Create item: " + json_str);

        	$.ajax({
			type: "POST",
			url: url + Utils.getRandomUrlVar(),
			data: {
				json: json_str
			},
			success: function(data) {
				console.log(url + " success? " + data);
				const json = JSON.parse(data);
				const id = json.item_id;
				alert((json.success == 1 ? "Create item '" + id + "' success!" : "Error creating item '" + id + "'"));
			}
		});
	}

	onFormChange(e) {
		this.update();
	}

	update() {
		const categoryIndex = $("#category_select").prop('selectedIndex');
		const supplierIndex = $("#supplier_select").prop('selectedIndex');
		const category = $("#category_select option:selected").text();
		const supplier = $("#supplier_select option:selected").text();
		const unit = $("#unit_select option:selected").text();
		const description = $("#item_description").val().trim();
		const barcode = $("#barcode").val().trim();
		const generalname = $("#general_name_input").val().trim();
		const brandname = $("#brand_name_input").val().trim();
		const count = Number($("#count_input").val());
		const stock = this.getTextInputNumber("stock_input");
		const unitprice = this.getTextInputNumber("unit_price_input");
		const sellprice = this.getTextInputNumber("sell_price_input");

		let complete = true && ((!Number.isNaN(Number(barcode)) && barcode.length == 13) || barcode == '') &&
			!Number.isNaN(count) && count > 0 &&
			!Number.isNaN(stock) && stock >= 0 &&
			!Number.isNaN(unitprice) && unitprice >= 0 &&
			!Number.isNaN(sellprice) && sellprice >= 0 &&
			description != '' &&
			categoryIndex > 0 &&
			supplierIndex > 0 &&
			generalname != '' &&
			brandname != '' &&
			true;

		if (complete) {
			this.json.category = category;
			this.json.supplier_name = supplier;
			this.json.unit = unit;
			this.json.item_description = description;
			this.json.bar_code = barcode;
			this.json.general_name = generalname;
			this.json.brand_name = brandname;
			this.json.count = count;
			this.json.stock = stock;
			this.json.unit_price = unitprice;
			this.json.sell_price = sellprice;
		}

		let button = document.getElementById("create_item_button");
		button.disabled = !complete;
	}

	getTextInputNumber(name) {
		const text = $("#" + name).val();
                return (text == '' ? 0 : Number(text));
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
			list.append(new Option(values[index], index));
		});
		if (selection) {
			$("select option").filter(function() {
				return $(this).text() == selection;
			}).prop('selected', true);
		}
	}
}
