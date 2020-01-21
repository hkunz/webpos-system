<?php
session_start();
$root = $_SESSION['root'];
require_once("${root}php/db.php");

function output_result($success, $tid, $sql_con, $err) {
  echo '{"success":' . ($success ? 1 : 0) . ',"transaction_id":' . $tid . ',"error":"' . $err . ': ' . $sql_con->error . '"}';
}

$tid = $_POST['transaction_id'] + 0;
$type = $_POST['payment_method'];
$accname = $_POST['account_name'] == '' ? null : $_POST['account_name'];
$accnumber = $_POST['account_number'] == '' ? null : $_POST['account_number'];
$expiration = $_POST['instrument_expiration'] == '' ? null : $_POST['instrument_expiration'];
$payment = $_POST['payment'];
$remarks = $_POST['remarks'] == '' ? null : $_POST['remarks'];

$query = "SELECT payment FROM `items_transactions` WHERE transaction_id=$tid";
$result = $sql_con->query($query);
if (!$result) {
  output_result(false, $tid, $sql_con, "Failed to get current payment information");
  die;
}
$row = $result->fetch_array();
$current_payment = $row[0];
$new_total_payment = $current_payment + $payment;

$statement = $sql_con->prepare('INSERT INTO `payment_details`(`transaction_id`, `payment_method`, `amount`, `account_name`, `instrument_number`, `expiration`, `remarks`) VALUES (?, ?, ?, ?, ?, ?, ?)');
$statement->bind_param('isdssss', $tid, $type, $payment, $accname, $accnumber, $expiration, $remarks);
$result = $statement->execute();
if (!$result) {
  output_result(false, $tid, $sql_con, "Failed to insert payment details");
  die;
}

$query = "UPDATE `items_transactions` SET `payment`=$new_total_payment WHERE `transaction_id`=$tid";
$result = $sql_con->query($query);
if (!$result) {
  output_result(false, $tid, $sql_con, "Failed to update items_transactions table with updated payment");
  die;
}

output_result(true, $tid, $sql_con);

$sql_con->close();
?>

