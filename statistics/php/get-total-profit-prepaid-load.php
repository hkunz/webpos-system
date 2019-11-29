<?php
include "../../php/db.php";

//if (isset($_POST['json'])) {

$dateStart = $_POST['dateStart'];
$dateEnd = $_POST['dateEnd'];
//$dateStart = '2019-01-01';
//$dateEnd = date('Y-m-d');

$query = "SELECT get_total_prepaid_load_profit('" . $dateStart . "','" . $dateEnd . "')";
$result = $sql_con->query($query);
$row = $result->fetch_array();
$profit = $row[0];

echo $profit;
?>
