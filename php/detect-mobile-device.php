<?php
session_start();
$root = isset($_SESSION['root']) ? $_SESSION['root'] : '';
$href_root = isset($_SESSION['href_root']) ? $_SESSION['href_root'] : '';
ob_start();
require_once("${root}php/check-detect-mobile-device.php");
$ismobile = ob_get_clean() === '1';
ob_clean();
echo $ismobile ? "${href_root}css/main-styles-mobile.css" : "";
?>
