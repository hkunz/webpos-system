<?php
session_start();
$root = $_SESSION['root'];
require_once("${root}php/db.php");
require_once("${root}php/common/scrollable-table.php");

$customer = $_POST['value'];
$table_id = $_POST['table_id'];

$query = "SELECT SUM(t.grand_total) - SUM(t.payment) FROM items_transactions t WHERE t.customer='$customer' AND t.type='SALE' AND t.payment < t.grand_total";
$result = $sql_con->query($query);
$rows = $result->fetch_array();
$sum = $rows[0];

$query = "SELECT LPAD(`transaction_id`, 7, '0') `TRX-ID`,`date` `Date Time`,`grand_total` `Receivable`,`payment` `Payment` FROM `items_transactions` WHERE customer='$customer' AND type='SALE' AND payment < grand_total ORDER BY `date` DESC";

$table = create_scrollable_table($table_id, $sql_con, $query, array('130px', '100%', '180px', '180px'), array(0, 0, 1, 1));
echo '{"customer":"' . $customer . '","content":"' . $table . '","view":"view_transactions","total_receivable":"' . $sum . '"}';

$sql_con->close();
?>

