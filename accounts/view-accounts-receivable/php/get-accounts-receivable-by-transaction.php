<?php
session_start();
$root = $_SESSION['root'];
require_once("${root}php/db.php");
require_once("${root}php/common/scrollable-table.php");

$customer = $_POST['value'];
$table_id = $_POST['table_id'];

$query = "SELECT `transaction_id` `Transaction-ID`,`date` `$customer`,`grand_total` `Grand Total`,`payment` `Payment` FROM `items_transactions` WHERE customer='$customer' AND type='SALE' AND payment < grand_total ORDER BY `date` DESC";

$table = create_scrollable_table($table_id, $sql_con, $query, array('130px', '100%', '180px', '180px'), array(0, 0, 1, 1));
echo '{"customer":"' . $customer . '","content":"' . $table . '","view":"view_transactions"}';

$sql_con->close();
?>

