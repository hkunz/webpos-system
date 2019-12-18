<?php
session_start();
$root = $_SESSION['root'];
require_once("${root}php/db.php");  

$query = "SELECT get_next_transaction_id()";
$result = $sql_con->query($query);
$row = $result->fetch_array();
echo $row[0];
?>
