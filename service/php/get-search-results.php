<?php
include "../../php/db.php";

if (isset($_POST['search'])) {
	$Name = str_replace(" ", "%", $_POST['search']);
   	$Query = "
		SELECT
			ii.item_id,
			ii.bar_code,
			ii.unit,
			ii.item_description,
			ii.general_name,
			ii.brand_name,
			ii.category,
			ii.supplier_name,
			pp.sell_price,
			ss.stock
		FROM items ii
		LEFT JOIN items_stock ss
			ON ss.item_id=ii.item_id
		LEFT JOIN view_items_prices_latest pp
			ON pp.item_id=ii.item_id
		WHERE pp.sell_price > 0 AND (
			ii.item_description LIKE '%$Name%' OR
			ii.bar_code LIKE '%$Name%' OR
			ii.general_name LIKE '%$Name%' OR
			ii.brand_name LIKE '%$Name%' OR
			ii.category LIKE '%$Name%'
		)
		ORDER BY ii.item_description DESC
		LIMIT 20;"
	;

	$ExecQuery = MySQLi_query($con, $Query);

	while ($Result = MySQLi_fetch_array($ExecQuery)) {
		$code = $Result['bar_code'];
		$code = (empty($code) ? "XXXXXXXXXXXXX" : $code);
		$unit = $Result['unit'];
		$description = $Result['item_description'];
		$price = $Result['sell_price'];
		$id = $Result['item_id'];
		$category = $Result['category'];
		$general_name = $Result['general_name'];
		$brand_name = $Result['brand_name'];
		$supplier = $Result['supplier_name'];
		$stock = $Result['stock'];

		echo
			"{{id}}$id{{id}}" .
			"{{code}}$code{{code}}" .
			"{{unit}}$unit{{unit}}" .
			"{{description}}$description{{description}}" .
			"{{category}}$category{{category}}" .
			"{{general_name}}$general_name{{general_name}}" .
			"{{brand_name}}$brand_name{{brand_name}}" .
			"{{supplier}}$supplier{{supplier}}" .
			"{{price}}$price{{price}}" .
			"{{stock}}$stock{{stock}}" .
			"||"
		;
	}
}
?>

