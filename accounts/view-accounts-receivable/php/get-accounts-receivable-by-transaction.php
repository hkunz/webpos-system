<?php
session_start();
$root = $_SESSION['root'];
require_once("${root}php/db.php");
require_once("${root}php/common/scrollable-table.php");

$customer = $_POST['customer'];

$query = "SELECT `transaction_id` `OR#`,`date` `Date`,`grand_total` `Grand Total`,`payment` `Payment` FROM `items_transactions` WHERE customer='$customer' AND type='SALE' AND payment < grand_total ORDER BY `date` DESC";

$table = create_scrollable_table($sql_con, $query, array('100px', '100%', '150px', '100px'), array(0, 0, 1, 1));
echo '{"customer":"' . $customer . '","content":"' . $table . '"}';

$sql_con->close();
?>

