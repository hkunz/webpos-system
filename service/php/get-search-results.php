<?php
session_start();
$root = $_SESSION['root'];
require_once("${root}php/db.php");

if (isset($_POST['search'])) {
	$name = str_replace(" ", "%", $_POST['search']);
	$query = "
		SELECT
			ii.item_id,
			ii.bar_code,
			ii.unit,
			ii.count,
			ii.item_description,
			ii.general_name,
			ii.brand_name,
			ii.category,
			ii.supplier_name,
			pp.unit_price,
			pp.sell_price,
			ss.stock
		FROM items ii
		LEFT JOIN items_stock ss
			ON ss.item_id=ii.item_id
		LEFT JOIN view_items_prices_latest pp
			ON pp.item_id=ii.item_id
		WHERE pp.sell_price > 0 AND (
			ii.item_description LIKE '%$name%' OR
			ii.bar_code LIKE '%$name%' OR
			ii.general_name LIKE '%$name%' OR
			ii.brand_name LIKE '%$name%'
		)
		ORDER BY ii.item_description DESC
		LIMIT 20;"
	;

	$result = $sql_con->query($query);

	while ($row = $result->fetch_assoc()) {
		$code = $row['bar_code'];
		$barcode = $code;
		$code = (empty($code) ? " " : str_pad($code, 13, '0', STR_PAD_LEFT));
		$unit = str_pad($row['unit'], 2, ' ', STR_PAD_LEFT);
		$count = $row['count'];
		$description = $row['item_description'];
		$sell_price = $row['sell_price'];
		$unit_price = $row['unit_price'];
		$item_id = $row['item_id'];
		$category = $row['category'];
		$general_name = $row['general_name'];
		$brand_name = $row['brand_name'];
		$supplier = $row['supplier_name'];
		$stock = $row['stock'];
		$manufacturer = "";

		echo '{' .
			'"item_id":' . $item_id . ',' .
			'"code":"' . $code . '",' .
			'"barcode":"' . $barcode . '",' .
			'"unit":"' . $unit . '",' .
			'"count":"' . $count . '",' .
			'"description":"{{description}}' . $description . '{{description}}",' .
			'"category":"' . $category . '",' .
			'"general_name":"' . $general_name . '",' .
			'"brand_name":"' . $brand_name . '",' .
			'"supplier":"' . $supplier . '",' .
			'"sell_price":"' . $sell_price . '",' .
			'"unit_price":"' . $unit_price . '",' .
			'"manufacturer":"' . $manufacturer . '",' .
			'"stock":' . ($stock ? $stock : '0') .
		'}||';
	}
}
$sql_con->close();
?>

