<?php
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'chuyte');

$sql_con = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

if (MySQLi_connect_errno()) {
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
?>
