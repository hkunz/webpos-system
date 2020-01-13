"use strict";

class TableRowHandler {
	constructor() {
		this.table = null;
		this.callback = null;
	}

	init(table, callback) {
		this.reset();
		this.table = table;
		this.callback = callback;
		this.addRowHandlers();
	}

	reset() {
		let rows = this.table ? this.table.getElementsByTagName("tr") : null;
		let len = rows ? rows.length : 0;
		for (let i = 0; i < len; ++i) {
			let row = this.table.rows[i];
			row.onclick = null;
		}
	}

	addRowHandlers() {
		let rows = this.table.getElementsByTagName("tr");
		let thiz = this;
		for (let i = 0; i < rows.length; ++i) {
			let currentRow = this.table.rows[i];
			let createClickHandler = function(row) {
				return function() {
					let cell = row.getElementsByTagName("td")[0];
					let id = cell.innerHTML;
					thiz.callback(id);
				};
			};
			currentRow.onclick = createClickHandler(currentRow);
		}
	}

	static getRowByCellContent(cellcontent, rows) {
		let len = rows ? rows.length : 0;
		for (let i = 0; i < len; ++i) {
			let row = rows[i];
			for (let key in row) {
				if (row.hasOwnProperty(key) && row[key] === cellcontent) {
					return row;
				}
			}
		}
		return null;
	}
}

