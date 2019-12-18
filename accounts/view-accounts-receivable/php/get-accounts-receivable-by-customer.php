<?php
$root = $_SESSION['root'];
require "../../../php/db.php";

$currency = $_POST['currency'];
$customer = $_POST['customer'];

function customers_table_content($con) {
	$customer_field = 'customer';
	$date_field = 'last_update';
	$payment_field = 'payment';
	$total_field = 'grand_total';
	$query = "SELECT `$customer_field`,MAX(`date`) `$date_field`,SUM(`payment`) `$payment_field`,SUM(`grand_total`) `$total_field` FROM `items_transactions` WHERE `type`='SALE' AND `payment` < `grand_total` GROUP BY `customer` ORDER BY `customer` ASC;";
	$result = $con->query($query);
	$table = "<table id='customer_table' class='common-table common-table-scroll' cellspacing='0' cellpadding='0'><thead><tr><th>Customer</label></th><th width='150px' nowrap>Last Update</th><th nowrap>Grand Total</th><th nowrap>Payment</th></tr></thead><tbody>";
	while($row = $result->fetch_array()) {
		$customer = $row[$customer_field];
		$date = $row[$date_field];
		$payment = $row[$payment_field];
		$total = $row[$total_field];
		$table .= "<tr><td width='100%'>$customer</td><td nowrap>$date</td><td align='right' nowrap><span style='margin-right:2px;'>$currency</span>$total</td><td align='right' nowrap><span style='margin-right:2px;'$currency</span>$payment</td></tr>";
	}
	$table .= "</tbody></table>";
	return $table;
}
$table_content = customers_table_content($sql_con);
echo '{"content":"' . $table_content . '"}';
?>

