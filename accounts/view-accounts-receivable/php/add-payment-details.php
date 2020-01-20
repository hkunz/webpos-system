<?php
session_start();
$root = $_SESSION['root'];
require_once("${root}php/db.php");

$tid = $_POST['transaction_id'] + 0;
$type = $_POST['payment_method'];
$accname = $_POST['account_name'] == '' ? null : $_POST['account_name'];
$accnumber = $_POST['account_number'] == '' ? null : $_POST['account_number'];
$expiration = $_POST['instrument_expiration'] == '' ? null : $_POST['instrument_expiration'];
$payment = $_POST['payment'];
$remarks = $_POST['remarks'] == '' ? null : $_POST['remarks'];

$statement = $sql_con->prepare('INSERT INTO `payment_details`(`transaction_id`, `payment_method`, `amount`, `account_name`, `instrument_number`, `expiration`, `remarks`) VALUES (?, ?, ?, ?, ?, ?, ?)');
$statement->bind_param('isdssss', $tid, $type, $payment, $accname, $accnumber, $expiration, $remarks);
$success = $statement->execute();

echo '{"success":' . ($success ? 1 : 0) . ',"transaction_id":' . $tid . ',"error":"' . $sql_con->error . '"}';

$sql_con->close();
?>

