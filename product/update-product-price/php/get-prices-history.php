<?php
session_start();
$root = $_SESSION['root'];
require_once("${root}php/db.php");
require_once("${root}php/libs/MobileDetect.php");

$ismobile = (new MobileDetect)->isMobile();

$item_id = $_POST['item_id'];
$currency = $_SESSION['currency'];

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
$sql_con->close();

$len = max(count($unit_prices), count($sell_prices));


$table = "\"<div style='padding-right:" . ($ismobile ? "0px" : "6px") . ";width:" . ($ismobile ? "100%" : "50%") . ";float:left;'>";
$table .= "<div class='common-table-wrapper' style='max-height:400px;overflow-y:auto;width:100%;'>";

$table .= "<table class='common-table' cellspacing='0' cellpadding='0'><thead>";
$table .= "<tr><th>Unit Price History</th><th style='text-align:right;' nowrap>Unit Price</th></tr></thead><tbody>";

for ($i = 0; $i < $len; ++$i) {
	$date = $unit_prices[$i][0];
	if ($date == null) continue;
	$table .= "<tr><td>";
	$table .= $date . "</td><td style='text-align:right;'><span style='margin-right:2px;'>" . $currency . "</span>" . $unit_prices[$i][1];
	$table .= "</td></tr>";
}
$table .= "</tbody></table></div></div>";

$table .= "<div style='padding-left:" . ($ismobile ? "0px" : "6px") . ";width:" . ($ismobile ? "100%" : "50%") . ";float:left;'>";
$table .= "<div class='common-table-wrapper' style='max-height:400px;overflow-y:auto;width:100%;'>";

$table .= "<table class='common-table' cellspacing='0' cellpadding='0'><thead>";
$table .= "<tr><th>Sell Price History</th><th style='text-align:right;' nowrap>Sell Price</th></tr></thead><tbody>";

for ($i = 0; $i < $len; ++$i) {
        $date = $sell_prices[$i][0];
	if ($date == null) continue;
        $table .= "<tr><td>";
        $table .= $date . "</td><td style='text-align:right;'><span style='margin-right:2px;'>" . $currency . "</span>" . $sell_prices[$i][1];
        $table .= "</td></tr>";
}
$table .= "</tbody></table></div></div>\"";
echo ',"table":' . $table . ',"curr_unit_price_asofdate":"' . $unit_prices[0][0] . '","curr_sell_price_asofdate":"' . $sell_prices[0][0] . '"}';
?>

