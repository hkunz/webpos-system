<?php
include "../../php/db.php";

if (isset($_POST['json'])) {
	$json = $_POST['json'];
	echo $json;
	//mysqli_fetch_array(mysqli_query($con, "SELECT insert_items_transaction(/)"))[0];
	
	$query = "CALL insert_items_transaction(" + $json + ", @success)";
	$exec = mysqli_query($con, $query);
	//while ($result = mysqli_fetch_array($exec)) {
	//	echo "result == " . $result . ": " . $result[0];
	//}
}

?>

