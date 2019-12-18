<?php
session_start();
$root = $_SESSION['root'];
require_once("${root}php/db.php");

if (isset($_POST['search'])) {
	$Name = str_replace(" ", "%", $_POST['search']);
   	$Query = "
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
			ii.item_description LIKE '%$Name%' OR
			ii.bar_code LIKE '%$Name%' OR
			ii.general_name LIKE '%$Name%' OR
			ii.brand_name LIKE '%$Name%'
		)
		ORDER BY ii.item_description DESC
		LIMIT 20;"
	;

	$ExecQuery = MySQLi_query($con, $Query);

	while ($Result = MySQLi_fetch_array($ExecQuery)) {
		$code = $Result['bar_code'];
		$barcode = $code;
		$code = (empty($code) ? "≡≡≡≡≡≡≡≡≡≡≡≡≡" : str_pad($code, 13, '0', STR_PAD_LEFT));
		$unit = str_pad($Result['unit'], 2, ' ', STR_PAD_LEFT);
		$count = $Result['count'];
		$description = $Result['item_description'];
		$sell_price = $Result['sell_price'];
		$unit_price = $Result['unit_price'];
		$item_id = $Result['item_id'];
		$category = $Result['category'];
		$general_name = $Result['general_name'];
		$brand_name = $Result['brand_name'];
		$supplier = $Result['supplier_name'];
		$stock = $Result['stock'];
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
?>

