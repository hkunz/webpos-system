<?php
include "../../php/db.php";

$dateStart = $_POST['dateStart'];
$dateEnd = $_POST['dateEnd'];

$query = "SELECT get_total_services_revenue('" . $dateStart . "','" . $dateEnd . "')";
$result = $sql_con->query($query);
$row = $result->fetch_array();
$revenue_services = $row[0];

echo $revenue_services;
?>
