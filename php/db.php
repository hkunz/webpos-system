<?php
//Database connection.
$con = MySQLi_connect(
   "localhost", //Server host name.
   "hkunz", //Database username.
   "@Student219", //Database password.
   "chuyte" //Database name or anything you would like to call it.
);
//Check connection
if (MySQLi_connect_errno()) {
   echo "Failed to connect to MySQL: " . MySQLi_connect_error();
}
?>
