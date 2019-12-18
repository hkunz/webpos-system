<?php
$root = $_SESSION['root'];
require_once("../../../php/db.php");
require_once("../../../php/common/scrollable-table.php");

$currency = $_POST['currency'];
$customer = $_POST['customer'];

$query = "SELECT `transaction_id` `OR#`,`date` `Date`,`grand_total` `Grand Total`,`payment` `Payment` FROM `items_transactions` WHERE customer='$customer' AND type='SALE' AND payment < grand_total ORDER BY `date` DESC";

$table = create_scrollable_table($sql_con, $query, array('100px', '100%', '150px', '100px'), array(0, 0, 1, 1), $currency);
echo '{"customer":"' . $customer . '","content":"' . $table . '"}';
?>

