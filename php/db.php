<?php
//Database connection.

$server = "localhost";
$user = "hkunz";
$pass = "@Student219";
$db = "chuyte";

$con = MySQLi_connect($server, $user, $pass, $db); //for procedural use
$sql_con = new mysqli($server, $user, $pass, $db); //for object-oriented use

if (MySQLi_connect_errno()) {
   echo "Failed to connect to MySQL: " . MySQLi_connect_error();
}
?>
