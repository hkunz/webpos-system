<?php
include "../../php/db.php";

//if (isset($_POST['json'])) {

$dateStart = $_POST['dateStart'];
$dateEnd = $_POST['dateEnd'];
//$dateStart = '2019-01-01';
//$dateEnd = date('Y-m-d');

$query = "SELECT get_total_prepaid_load_revenue('" . $dateStart . "','" . $dateEnd . "')";
$result = $sql_con->query($query);
$row = $result->fetch_array();
$revenue_prepaid = $row[0];

$query = "SELECT get_total_products_revenue('" . $dateStart . "','" . $dateEnd . "')";
$result = $sql_con->query($query);
$row = $result->fetch_array();
$revenue_products = $row[0];

$query = "SELECT get_total_services_revenue('" . $dateStart . "','" . $dateEnd . "')";
$result = $sql_con->query($query);
$row = $result->fetch_array();
$revenue_services = $row[0];

echo $revenue_prepaid + $revenue_services + $revenue_products;
?>
