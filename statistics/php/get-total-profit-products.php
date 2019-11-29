<?php
include "../../php/db.php";

$dateStart = $_POST['dateStart'];
$dateEnd = $_POST['dateEnd'];

$query = "SELECT get_total_products_profit('" . $dateStart . "','" . $dateEnd . "')";
$result = $sql_con->query($query);
$row = $result->fetch_array();
$profit = $row[0];

echo $profit;
?>
