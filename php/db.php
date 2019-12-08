<?php

define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'chuyte');
 
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
 
if ($link === false) {
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

$server = "localhost";
$user = "hkunz";
$pass = "@Student219";
$db = "chuyte";

$con = MySQLi_connect($server, $user, $pass, $db); //for procedural use
$sql_con = new mysqli($server, $user, $pass, $db); //for object-oriented use


if (MySQLi_connect_errno() || $link === false) {
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
?>
