"use strict";

class AwesompleteInputHandler {
	constructor(name, url) {
		let thiz = this;
		this.name = name;
		this.url = url;
		this.postvar1 = null;
		this.postvar2 = null;
		this.autoFirst = true;
		this.input_element = null;
		$(document).ready(function() {
			thiz.onDocumentReady();
		});
	}

	onDocumentReady() {
		this.input_element = new Awesomplete(document.getElementById(this.name), {
			minChars:2,
			maxItems:20,
			autoFirst:this.autoFirst,
			replace: function(suggestion) {
				this.input.value = suggestion.label;
			}
		});
		let thiz = this;
		$("#" + this.name).keyup(function(e) {
			thiz.onItemSearchInputChange(e);
		});
	}

	onItemSearchInputChange(e) {
		let name = $("#" + this.name).val().trim();
		if (name == "") {
			//$("#items_dropdown").html("");
		} else {
			let thiz = this;
			$.ajax({
				type: "POST",
				url: this.url + Utils.getRandomUrlVar(),
				data: {
					search: name,
					postvar1: this.postvar1,
					postvar2: this.postvar2
				},
				success: function(data) {
					thiz.onAjaxSuccess(data);
				}
			});
		}
	}

	onAjaxSuccess(data) {
		//https://github.com/LeaVerou/awesomplete/issues/17207
	}
}
