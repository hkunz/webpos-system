<?php
session_start();
$root = $_SESSION['root'];
require_once("${root}php/db.php");

$table = $_POST['table'];
$field = $_POST['field'];
echo "[";
$result = $sql_con->query("SHOW COLUMNS FROM $table WHERE Field = '$field'");
$rows = $result->fetch_array();
$str_values = str_replace("'", '"', $rows[1]);
preg_match('/^enum\((\".*\")\)$/', $str_values, $values);
echo $values[1];
echo "]";
$sql_con->close();
?>

