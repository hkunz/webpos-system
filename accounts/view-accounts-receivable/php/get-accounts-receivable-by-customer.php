<?php
$root = $_SESSION['root'];
require_once("../../../php/db.php");
require_once("../../../php/common/scrollable-table.php");


$currency = $_POST['currency'];
$customer = $_POST['customer'];

$query = "SELECT `customer` `Customer`,MAX(`date`) `Last Update Date`,SUM(`grand_total`) `Receivable`,SUM(`payment`) `Payment` FROM `items_transactions` WHERE `type`='SALE' AND `payment` < `grand_total` GROUP BY `customer` ORDER BY `customer` ASC;";

$table = create_scrollable_table($sql_con, $query, array('100%', '210px', '110px', '100px'), array(0, 0, 1, 1), $currency);
echo '{"content":"' . $table . '"}';
?>

