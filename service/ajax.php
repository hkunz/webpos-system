<?php
include "db.php";
//Getting value of "search" variable from "script.js".
if (isset($_POST['search'])) {
	$Name = str_replace(" ", "%", $_POST['search']);
   	$Query = "
		SELECT * FROM items
		WHERE sell_price > 0 AND (item_description LIKE '%$Name%'
		OR bar_code LIKE '%$Name%'
		OR general_name LIKE '%$Name%'
		OR brand_name LIKE '%$Name%'  
		OR category LIKE '%$Name%')
		LIMIT 15"
	;

	$ExecQuery = MySQLi_query($con, $Query);

   /*
   while ($Result = MySQLi_fetch_array($ExecQuery)) {
        $code = $Result['bar_code'];
        $output = (empty($code) ? "XXXXXXXXXXXXX" : $code) . ' [' .
	$Result['unit'] . '] ' .
	$Result['item_description'] . ' [₱' .
	$Result['sell_price'] . '] {{' . 
        $Result['item_id'] . '}}||';
        echo $output;
   }*/

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
			"||"
		;
   }
}
?>

