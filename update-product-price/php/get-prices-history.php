<?php
$root = $_SESSION['root'];
require "../../php/db.php";

$item_id = $_POST['item_id'];
echo '{
"item_id":' . $item_id . ',
';

function echoPricesHistory($con, $item_id, $type) {
	$prices = array();
	echo '"' . $type . '_prices":[';
	$asofdate = $type . '_price_asofdate';
	$pricetype = $type . '_price';
	$query = "SELECT `item_id`,`$asofdate`,`$pricetype` FROM `items_prices` WHERE `item_id`=$item_id GROUP BY `item_id`, `$asofdate`, `$pricetype` ORDER BY $asofdate DESC";
	$result = $con->query($query);
	$i = 0;
	while($row = $result->fetch_array()) {
		$date = $row[$asofdate];
		$price = $row[$pricetype];
		$prices[] = array($date, $price);
		echo ($i <= 0 ? '' : ',' ) . '{';
		echo '"' . $asofdate . '":"' . $date . '",';
		echo '"' . $pricetype . '":' . $price;
		echo '}';
		++$i;
	}
	echo ']';
	return $prices;
}
$unit_prices = echoPricesHistory($sql_con, $item_id, "unit");
echo ',';
$sell_prices = echoPricesHistory($sql_con, $item_id, "sell");

$len = max(count($unit_prices), count($sell_prices));

$table = "\"<table class='common-table' cellspacing='0' cellpadding='0'><tr><th>Unit Price History</th><th>Unit Price</th><th>Sell Price History</th><th>Sell Price</th></tr>";
for ($i = 0; $i < $len; ++$i) {
	$table .= "<tr><td>";
	$table .= $unit_prices[$i][0] . "</td><td>" . $unit_prices[$i][1] . "</td><td>";
	$table .= $sell_prices[$i][0] . "</td><td>" . $sell_prices[$i][1];
	$table .= "</td></tr>";
}
$table .= "</table>\"";
echo ',"table":' . $table . '}';
?>

