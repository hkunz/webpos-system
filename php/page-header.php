<?php
session_start();

$login = (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true);

function getLevels() {
    $slashes = explode("/", $_SERVER['PHP_SELF']);
    $loop = count($slashes) - 3; // -1 for left / & -1 for curr file & -1 coz in localhost/klebbys/ instead of localhost
    $levels = "";
    for ($i = 0; $i < $loop; ++$i) $levels .= "../";
    return $levels;
}

if ($login === false) {
    header("location: " . getLevels() . "user/login.php");
    die;
}

$href_root = $_SESSION['href_root'];
$root = $_SESSION['root'];

require_once("${root}/php/libs/MobileDetect.php");
$ismobile = (new MobileDetect)->isMobile();
require_once("${root}php/" . ($ismobile ? "mini-navigation-bar" : "navigation-bar") . ".php");
?>
