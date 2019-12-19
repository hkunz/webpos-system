"use strict";

class TableRowHandler {
	constructor() {
		this.table = null;
		this.callback = null;
	}

	init(table, callback) {
		this.table = table;
		this.callback = callback;
		this.addRowHandlers();
	}

	addRowHandlers() {
		let rows = this.table.getElementsByTagName("tr");
		let thiz = this;
		for (let i = 0; i < rows.length; i++) {
			var currentRow = this.table.rows[i];
			var createClickHandler = function(row) {
				return function() {
					var cell = row.getElementsByTagName("td")[0];
					var id = cell.innerHTML;
					thiz.callback(id);
				};
			};
			currentRow.onclick = createClickHandler(currentRow);
		}
	}
}

