<?php
session_start();
$root = $_SESSION['root'];
require_once("${root}php/db.php");

if (isset($_POST['json'])) {
	$json = $_POST['json'];
	$json_o = json_decode($json);
	$transaction_id = $json_o->transaction_id;

	$statement = $sql_con->prepare('CALL insert_items_transaction(?, @success)');
	$statement->bind_param('s', $json); //'s' for string, 'i' for integer, etc. if more params 'sii' string int int for example
	$statement->execute();
	$select = $sql_con->query('SELECT @success');
	$result = $select->fetch_assoc();
	$success = $result['@success'];
	echo '{"success":' . $success . ',"transaction_id":' . $transaction_id . '}';
}

?>

