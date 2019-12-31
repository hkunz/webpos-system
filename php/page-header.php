<?php
session_start();

$login = (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true);
$page = $_SERVER['PHP_SELF'];

$p_start = strrpos($page, '/');
$p_end = strrpos($page, '.');
$pagename = substr($page, $p_start + 1, $p_end - $p_start - 1);

function getLevels() {
    $slashes = explode("/", $_SERVER['PHP_SELF']);
    $loop = count($slashes) - 3; // -1 for left / & -1 for curr file & -1 coz in localhost/klebbys/ instead of localhost
    $levels = "";
    for ($i = 0; $i < $loop; ++$i) $levels .= "../";
    return $levels;
}

if ($login === false && $pagename !== 'login' && $pagename !== 'register') {
    header("location: " . getLevels() . "user/login.php");
    die;
}

$root_defined = isset($_SESSION['root']);
$href_root = $root_defined ? $_SESSION['href_root'] : "../";
$root = $root_defined ? $_SESSION['root'] : "../";

require_once("${root}/php/libs/MobileDetect.php");
$ismobile = (new MobileDetect)->isMobile();
require_once("${root}php/" . ($ismobile ? "mini-navigation-bar" : "navigation-bar") . ".php");
?>
