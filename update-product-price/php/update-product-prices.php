<?php
session_start();
$root = $_SESSION['root'];
require_once("${root}php/db.php");

$item_id = $_POST['item_id'];
$unit_price = $_POST['new_unit_price'];
$sell_price = $_POST['new_sell_price'];
$unit_price_asofdate = $_POST['unit_price_asofdate'];
$sell_price_asofdate = $_POST['sell_price_asofdate'];

format_date($unit_price_asofdate);
format_date($sell_price_asofdate);

function format_date(&$date) {
	if ($date === '') {
		$date = "CURRENT_TIMESTAMP";
	} else {
		$date = "'" . $date . "'";
	}
}

$query = "INSERT INTO `items_prices`(`item_id`, `unit_price_asofdate`, `unit_price`, `sell_price_asofdate`, `sell_price`) VALUES ($item_id, $unit_price_asofdate, $unit_price, $sell_price_asofdate, $sell_price);";

if ($sql_con->query($query) === TRUE) {
	echo '{"success":1,"message":"Current prices update success!","new_unit_price":' . $unit_price . ',"new_sell_price":' . $sell_price . '}';
} else {
	echo '{"success":0,"message":"' . $sql_con->error . '","query":"' . $query . '"}';
}
?>

