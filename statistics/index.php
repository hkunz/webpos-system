<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: ../login.php");
    exit;
}
$href_root = $_SESSION['href_root'];
$root = $_SESSION['root'];

ob_start();
require_once("${root}php/check-detect-mobile-device.php");
$ismobile = ob_get_clean() === '1';
require_once("${root}php/" . ($ismobile ? "mini-navigation-bar" : "navigation-bar") . ".php");

function sbr($indent) {
  echo '<br>';
  if ($indent) echo '&nbsp;&nbsp;&nbsp;';
}

?>
<!DOCTYPE html>
<html lang="en">
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Statistics</title>
  <link type="text/css" rel="stylesheet" href="<?php echo $href_root; ?>css/main-styles.css">
  <link type="text/css" rel="stylesheet" href="<?php echo $href_root; ?>css/navigation-bar.css">
  <link type="text/css" rel="stylesheet" href="<?php echo $href_root; ?>css/awesomplete.css">
  <link type="text/css" rel="stylesheet" href="<?php echo $href_root; ?>css/responsive-web-page.css">
  <link type="text/css" rel="stylesheet" href="<?php echo $href_root; ?>css/common-table.css">
  <link type="text/css" rel="stylesheet" href="<?php echo ($ismobile ? $href_root . 'css/main-styles-mobile.css' : '') ?>">
  <link type="text/css" rel="stylesheet" href="css/stats-styles.css">
  <script type="text/javascript" src="<?php echo $href_root; ?>js/libs/jquery-3.4.1.min.js"></script>
  <script type="text/javascript" src="<?php echo $href_root; ?>js/libs/awesomplete.js"></script>
  <script type="text/javascript" src="<?php echo $href_root; ?>js/Utils.js"></script>
  <script type="text/javascript" src="<?php echo $href_root; ?>js/sound-effects.js"></script>
  <script type="text/javascript" src="js/main.js"></script>
  <?php require_once("${root}php/favicon.php"); ?>
</head>

<body class="body">
  <?php echo $navbar_content; ?>
  <div class="container-wrapper">
  <div class="container-left">
    <div class='store-heading'>
      <label class="heading"><script type="text/javascript">document.write(Utils.getStoreHeading());</script></label> | <label class="heading-sub"><script type="text/javascript">document.write(Utils.getStoreSubHeading());</script></label>
      <hr class="division">
      <br/>
    </div>
    <div style='width:100%;'>
      <div class='common-table-wrapper' style='margin-top:0px;'>
        <div class='stats-heading'>
          <label class='standard-label'>Total Revenue To Date:</label><?php if ($ismobile) sbr(1); ?>
          <label id='revenue_total' class='drop-shadow stat-total'></label>
        </div>
        <div class='stats-content'>
          <label class='standard-label'>Prepaid-Load:</label><?php if ($ismobile) sbr(1); ?>
          <label id='revenue_total_prepaid' class='drop-shadow'></label> (<label id='profit_total_prepaid' class='drop-shadow profit'></label>)<br/>
          <label class='standard-label'>Products:</label><?php if ($ismobile) sbr(1); ?>
          <label id='revenue_total_products' class='drop-shadow'></label> (<label id='profit_total_products' class='drop-shadow profit'></label>)<br/>
          <label class='standard-label'>Services:</label><?php if ($ismobile) sbr(1); ?>
          <label id='revenue_total_services' class='drop-shadow'></label>
        </div>
      </div>
      <div class='common-table-wrapper'>
        <div class='stats-heading'>
          <label class='standard-label'>Total Revenue For Current Month:</label><?php if ($ismobile) sbr(1); ?>
          <label id='revenue_total_curr_month' class='drop-shadow stat-total'></label>
        </div>
        <div class='stats-content'>
          <label class='standard-label'>&nbsp;&nbsp;Prepaid-Load:</label><?php if ($ismobile) sbr(1); ?>
          <label id='revenue_total_curr_month_prepaid' class='drop-shadow'></label> (<label id='profit_curr_month_prepaid' class='drop-shadow profit'></label>)<br/>
          <label class='standard-label'>&nbsp;&nbsp;Products:</label><?php if ($ismobile) sbr(1); ?>
          <label id='revenue_total_curr_month_products' class='drop-shadow'></label> (<label id='profit_curr_month_products' class='drop-shadow profit'></label>)<br/>
          <label class='standard-label'>&nbsp;&nbsp;Services:</label><?php if ($ismobile) sbr(1); ?>
          <label id='revenue_total_curr_month_services' class='drop-shadow'></label>
        </div>
      </div>
      <div class='common-table-wrapper'>
        <div class='stats-heading'>
          <label class='standard-label'>Total Revenue For Previous Month:</label><?php if ($ismobile) sbr(1); ?>
          <label id='revenue_total_prev_month' class='drop-shadow stat-total'></label>
        </div>
        <div class='stats-content'>
          <label class='standard-label'>&nbsp;&nbsp;Prepaid-Load:</label><?php if ($ismobile) sbr(1); ?>
          <label id='revenue_total_prev_month_prepaid' class='drop-shadow'></label> (<label id='profit_prev_month_prepaid' class='drop-shadow profit'></label>)<br/>
          <label class='standard-label'>&nbsp;&nbsp;Products:</label><?php if ($ismobile) sbr(1); ?>
          <label id='revenue_total_prev_month_products' class='drop-shadow'></label> (<label id='profit_prev_month_products' class='drop-shadow profit'></label>)<br/>
          <label class='standard-label'>&nbsp;&nbsp;Services:</label><?php if ($ismobile) sbr(1); ?>
          <label id='revenue_total_prev_month_services' class='drop-shadow'></label>
        </div>
      </div>
      <div class='common-table-wrapper'>
        <div class='stats-heading'>
          <label class='standard-label'>Total Revenue For One Before Previous Month:</label><?php if ($ismobile) sbr(1); ?>
          <label id='revenue_total_prev2_month' class='drop-shadow stat-total'></label>
        </div>
        <div class='stats-content'>
          <label class='standard-label'>&nbsp;&nbsp;Prepaid-Load:</label><?php if ($ismobile) sbr(1); ?>
          <label id='revenue_total_prev2_month_prepaid' class='drop-shadow'></label> (<label id='profit_prev2_month_prepaid' class='drop-shadow profit'></label>)<br/>
          <label class='standard-label'>&nbsp;&nbsp;Products:</label><?php if ($ismobile) sbr(1); ?>
          <label id='revenue_total_prev2_month_products' class='drop-shadow'></label> (<label id='profit_prev2_month_products' class='drop-shadow profit'></label>)<br/>
          <label class='standard-label'>&nbsp;&nbsp;Services:</label><?php if ($ismobile) sbr(1); ?>
          <label id='revenue_total_prev2_month_services' class='drop-shadow'></label><br/>
        </div>
      </div>
    </div>
  </div>
  </div>
  </div>
  <div id="eventdispatcher" style="display:none;" />
</body>
</html>

