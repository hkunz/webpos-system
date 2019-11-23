"use strict"

class SqlTransactionHandler {
	constructor() {
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
	}
}
