<?php
session_start();
$root = $_SESSION['root'];
require_once("${root}php/db.php");
require_once("${root}php/common/scrollable-table.php");

$all = (!isset($_POST['value']) || $_POST['value'] === '');
$customer = $all ? '%%' : $_POST['value'];
$table_id = $_POST['table_id'] ?? null;
$limit = isset($_POST['limit']) ? $_POST['limit'] : (isset($_GET['limit']) ? $_GET['limit'] : 300);

$query = "SELECT LPAD(`transaction_id`, 7, '0') `TRX-ID`,`date` `Date Time`, `type` `Type`, `customer` `Transactor` ,`grand_total` `Total` FROM `items_transactions` WHERE customer LIKE '$customer' ORDER BY `date` DESC LIMIT $limit;";

$view = $all ? "view_transactions" : "view_transactions_by_customer";
$data = array();
$table = create_scrollable_table($table_id, $sql_con, $query, array('130px', '200px', '100px', '100%', '180px'), array(0, 0, 0, 0, 1), $data);
echo '{"customer":"' . $customer . '","content":"' . $table . '","view":"' . $view . '","data":' . json_encode($data) . '}';

$sql_con->close();
?>

