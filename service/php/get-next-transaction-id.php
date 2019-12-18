<?php
session_start();
$root = $_SESSION['root'];
require_once("${root}php/db.php");  
echo mysqli_fetch_array(mysqli_query($con, "SELECT get_next_transaction_id()"))[0];
?>
