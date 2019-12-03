"use strict";

class AwesompleteInputHandler {
	constructor(name, url) {
		let thiz = this;
		this.name = name;
		this.url = url;
		this.postvar1 = null;
		this.postvar2 = null;
		this.autoFirst = true;
		thiz.input_element = null;
		$(document).ready(function() {
			thiz.onInitialise(thiz);
		});
	}

	onInitialise(thiz) {
		thiz.input_element = new Awesomplete(document.getElementById(thiz.name), {
			minChars:2,
			maxItems:20,
			autoFirst:thiz.autoFirst,
			replace: function(suggestion) {
				this.input.value = suggestion.label;
			}
		});
		$("#" + thiz.name).keyup(function(e) {thiz.onItemSearchInputChange(e, thiz)});
	}

	onItemSearchInputChange(e, thiz) {
		let name = $("#" + thiz.name).val();
		if (name == "") {
			//$("#items_dropdown").html("");
		} else {
			$.ajax({
				type: "POST",
				url: thiz.url + Utils.getRandomUrlVar(),
				data: {
					search: name,
					postvar1: thiz.postvar1,
					postvar2: thiz.postvar2
				},
				success: function(data) {
					thiz.onAjaxSuccess(data, thiz);
				}
			});
		}
	}

	onAjaxSuccess(data, thiz) {
		//https://github.com/LeaVerou/awesomplete/issues/17207
	}
}
