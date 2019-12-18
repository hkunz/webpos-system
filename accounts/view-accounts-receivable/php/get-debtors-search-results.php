<?php
session_start();
$root = $_SESSION['root'];
require_once("${root}php/db.php");

if (isset($_POST['search'])) {
	$field = $_POST['postvar1'];
	$table = $_POST['postvar2'];
	$name = str_replace(" ", "%", $_POST['search']);
	$name = $_POST['search'];
   	$query = "
		SELECT DISTINCT	ii.$field
		FROM $table ii
		WHERE ii.$field LIKE \"%$name%\" AND ii.$field IN (SELECT DISTINCT customer FROM items_transactions WHERE type='SALE' AND payment < grand_total)
		ORDER BY ii.$field DESC
		LIMIT 10"
	;
	$result = $sql_con->query($query);

	while ($row = $result->fetch_assoc()) {
		foreach ($row as $value) {
			echo "$value,";
		}
	}
}
?>

