<?php
session_start();
$href_root = isset($_SESSION['href_root']) ? $_SESSION['href_root'] : '';
ob_start();
require_once("${href_root}php/check-detect-mobile-device.php");
$is_mobile = ob_get_clean();
echo ($is_mobile === 1 ? "${href_root}css/main-styles-mobile.css" : "";
?>
