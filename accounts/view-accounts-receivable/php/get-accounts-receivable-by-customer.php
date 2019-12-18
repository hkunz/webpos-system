<?php
$root = $_SESSION['root'];
require "../../../php/db.php";

$currency = $_POST['currency'];
$customer = $_POST['customer'];

function customers_table_content($con) {
	$customer_w = "350px";
	$date_w = "195px";
	$total_w = "100px";
	$payment_w = "100px";
	$vscroll_w2 = "6px";
	$customer_field = 'customer';
	$date_field = 'last_update';
	$payment_field = 'payment';
	$total_field = 'grand_total';
	$query = "SELECT `$customer_field`,MAX(`date`) `$date_field`,SUM(`payment`) `$payment_field`,SUM(`grand_total`) `$total_field` FROM `items_transactions` WHERE `type`='SALE' AND `payment` < `grand_total` GROUP BY `customer` ORDER BY `customer` ASC;";
	$result = $con->query($query);
	$table = "<table id='customer_table' class='common-table common-table-scroll' cellspacing='0' cellpadding='0'><thead class='scroll'><tr><th style='width:$customer_w;'>Customer</th><th style='width:$date_w;' nowrap>Last Update</th><th style='width:$total_w;text-align:right;' nowrap>Grand Total</th><th style='width:$payment_w;text-align:right;' nowrap>Payment</th><th style='padding-left:$vscroll_w2;padding-right:$vscroll_w2;'></th></tr></thead><tbody class='scroll'>";
	while($row = $result->fetch_array()) {
		$customer = $row[$customer_field];
		$date = $row[$date_field];
		$payment = $row[$payment_field];
		$total = $row[$total_field];
		$table .= "<tr><td style='width:$customer_w;'>$customer</td><td style='width:$date_w;' nowrap>$date</td><td style='width:$total_w;text-align:right;' nowrap><span style='margin-right:2px;'>$currency</span>$total</td><td style='width:$payment_w;text-align:right;' nowrap><span style='margin-right:2px;'$currency</span>$payment</td></tr>";
	}
	$table .= "</tbody></table>";
	return $table;
}
$table_content = customers_table_content($sql_con);
echo '{"content":"' . $table_content . '"}';
?>

