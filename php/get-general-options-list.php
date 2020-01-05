<?php
session_start();

$root = $_SESSION['root'];
require_once("${root}php/db.php");

$field = $_POST['field'];
$table = $_POST['table'];
$order = isset($_POST['order']) ? $_POST['order'] : $field;

$query = "
SELECT t.$field
FROM $table t
ORDER BY t.$order ASC;"
;

$result = $sql_con->query($query);

$count = 0;
while ($row = $result->fetch_assoc()) {
	foreach ($row as $value) {
		echo (($count++ > 0) ? ',' : '') . "$value";
		break;
	}
}

$sql_con->close();
?>
