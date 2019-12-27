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
require_once("${root}php/navigation-bar.php");
?>
<!DOCTYPE html>
<html lang="en">
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Klebby's Statistics</title>
  <link type="text/css" rel="stylesheet" href="<?php echo $href_root; ?>css/main-styles.css">
  <link type="text/css" rel="stylesheet" href="<?php echo $href_root; ?>css/navigation-bar.css">
  <link type="text/css" rel="stylesheet" href="<?php echo $href_root; ?>css/awesomplete.css">
  <link type="text/css" rel="stylesheet" href="<?php echo $href_root; ?>css/responsive-web-page.css">
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
    <label class="heading"><script type="text/javascript">document.write(Utils.getStoreHeading());</script></label>
     | <label class="heading-sub"><script type="text/javascript">document.write(Utils.getStoreSubHeading());</script></label>
    <hr class="division">
    <br/>
    <label class='standard-label'>Total Revenue To Date:</label> 
    <label id='revenue_total' class='drop-shadow'></label><br/>
    <hr class='division'/>
    <label class='standard-label'>&nbsp;&nbsp;Prepaid-Load:</label>
    <label id='revenue_total_prepaid' class='drop-shadow'></label> (<label id='profit_total_prepaid' class='drop-shadow profit'></label>)<br/>
    <label class='standard-label'>&nbsp;&nbsp;Products:</label>
    <label id='revenue_total_products' class='drop-shadow'></label> (<label id='profit_total_products' class='drop-shadow profit'></label>)<br/>
    <label class='standard-label'>&nbsp;&nbsp;Services:</label>
    <label id='revenue_total_services' class='drop-shadow'></label><br/>
    <hr class='division'/>
    <label class='standard-label'>Total Revenue For Current Month:</label>
    <label id='revenue_total_curr_month' class='drop-shadow'></label><br/>
    <hr class='division'/>
    <label class='standard-label'>&nbsp;&nbsp;Prepaid-Load:</label>
    <label id='revenue_total_curr_month_prepaid' class='drop-shadow'></label> (<label id='profit_curr_month_prepaid' class='drop-shadow profit'></label>)<br/>
    <label class='standard-label'>&nbsp;&nbsp;Products:</label>
    <label id='revenue_total_curr_month_products' class='drop-shadow'></label> (<label id='profit_curr_month_products' class='drop-shadow profit'></label>)<br/>
    <label class='standard-label'>&nbsp;&nbsp;Services:</label>
    <label id='revenue_total_curr_month_services' class='drop-shadow'></label><br/>
    <hr class='division'/>
    <label class='standard-label'>Total Revenue For Previous Month:</label>
    <label id='revenue_total_prev_month' class='drop-shadow'></label><br/>
    <hr class='division'/>
    <label class='standard-label'>&nbsp;&nbsp;Prepaid-Load:</label>
    <label id='revenue_total_prev_month_prepaid' class='drop-shadow'></label> (<label id='profit_prev_month_prepaid' class='drop-shadow profit'></label>)<br/>
    <label class='standard-label'>&nbsp;&nbsp;Products:</label>
    <label id='revenue_total_prev_month_products' class='drop-shadow'></label> (<label id='profit_prev_month_products' class='drop-shadow profit'></label>)<br/>
    <label class='standard-label'>&nbsp;&nbsp;Services:</label>
    <label id='revenue_total_prev_month_services' class='drop-shadow'></label><br/>
    <hr class='division'/>
    <label class='standard-label'>Total Revenue For One Before Previous Month:</label>
    <label id='revenue_total_prev2_month' class='drop-shadow'></label><br/>
    <hr class='division'/>
    <label class='standard-label'>&nbsp;&nbsp;Prepaid-Load:</label>
    <label id='revenue_total_prev2_month_prepaid' class='drop-shadow'></label> (<label id='profit_prev2_month_prepaid' class='drop-shadow profit'></label>)<br/>
    <label class='standard-label'>&nbsp;&nbsp;Products:</label>
    <label id='revenue_total_prev2_month_products' class='drop-shadow'></label> (<label id='profit_prev2_month_products' class='drop-shadow profit'></label>)<br/>
    <label class='standard-label'>&nbsp;&nbsp;Services:</label>
    <label id='revenue_total_prev2_month_services' class='drop-shadow'></label><br/>
    <hr class='division'/>
  </div>
  </div>
  </div>
  <div id="eventdispatcher" style="display:none;" />
</body>
</html>

