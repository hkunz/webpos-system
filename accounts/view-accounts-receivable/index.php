<?php
session_start();
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: ../../user/login.php");
    exit;
}
$href_root = $_SESSION['href_root'];
$root = $_SESSION['root'];

ob_start();
require_once("${root}php/check-detect-mobile-device.php");
$ismobile = ob_get_clean() === '1';
require_once("${root}php/" . ($ismobile ? "mini-navigation-bar" : "navigation-bar") . ".php");
?>
<!DOCTYPE html>
<html lang="en">
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Accounts Receivable</title>
  <link type="text/css" rel="stylesheet" href="<?php echo $href_root; ?>css/main-styles.css">
  <link type="text/css" rel="stylesheet" href="<?php echo $href_root; ?>css/navigation-bar.css">
  <link type="text/css" rel="stylesheet" href="<?php echo $href_root; ?>css/awesomplete.css">
  <link type="text/css" rel="stylesheet" href="<?php echo $href_root; ?>css/common-table.css">
  <link type="text/css" rel="stylesheet" href="<?php echo ($ismobile ? $href_root . 'css/main-styles-mobile.css' : '') ?>">
  <script type="text/javascript" src="<?php echo $href_root; ?>js/libs/jquery-3.4.1.min.js"></script>
  <script type="text/javascript" src="<?php echo $href_root; ?>js/libs/awesomplete.js"></script>
  <script type="text/javascript" src="<?php echo $href_root; ?>js/AwesompleteInputHandler.js"></script>
  <script type="text/javascript" src="<?php echo $href_root; ?>js/Utils.js"></script>
  <script type="text/javascript" src="<?php echo $href_root; ?>js/sound-effects.js"></script>
  <script type="text/javascript" src="<?php echo $href_root; ?>service/js/CustomerSearchInputHandler.js"></script>
  <script type="text/javascript" src="<?php echo $href_root; ?>js/common/TableRowHandler.js"></script>
  <script type="text/javascript" src="js/ViewState.js"></script>
  <script type="text/javascript" src="js/PayTransactionHandler.js"></script>
  <script type="text/javascript" src="js/AccountsReceivableByCustomerHandler.js"></script>
  <script type="text/javascript" src="js/Controller.js"></script>
  <script type="text/javascript" src="js/main.js"></script>
  <?php require_once("${root}php/favicon.php"); ?>
</head>

<body class="body">
  <?php echo $navbar_content; ?>
  <div class="container-wrapper">
    <div class="container-left" <?php if ($ismobile) echo "style='height:100%;overflow:hidden;'" ?>>
      <div class='store-heading content-min-height'>
        <label class="heading"><script type="text/javascript">document.write(Utils.getStoreHeading());</script></label>                                                              | <label class="heading-sub"><script type="text/javascript">document.write(Utils.getStoreSubHeading());</script></label>
        <hr class="division">
      </div>
      <div class='content-min-height' style="margin-left:2px;margin-bottom:3px;">
	<table id='header_table' cellpadding='0' cellspacing='0' style='width:100%;'>
          <tr>
            <td style='width:100%;'>
              <label class="drop-shadow" style="font-weight:bold;">ACCOUNTS RECEIVABLE: </label><label id='colon_label' class="drop-shadow" style="display:none;font-weight:bold;"></label>
              <div style='display:inline;white-space:nowrap;'>
                <div id='customer_total_div' style='display:none;'>
                  <label class="drop-shadow">[</label><label id='customer_total' class="drop-shadow" style="color:#44ff44;font-weight:bold;"></label><label class="drop-shadow">]</label>
                </div>
                <div id='customer_div' style='display:none;margin:0px;padding:0px;'>
                  <label class="drop-shadow" style=""></label><label id='customer_label' class="drop-shadow" style="font-weight:bold;color:white;"></label><label class="drop-shadow" style=""></label>
                </div>
              </div>
	      <div id='transaction_td' style='white-space:nowrap;display:none;margin:0px;padding:0px;'>
                <div style='display:inline;background-color:#000;border-radius:20px;padding-left:10px;padding-right:13px;'><label id='transaction_label' class='drop-shadow' style='color:#eee;'></label></div>
              </div>
            </td>
            <td style='text-align:right;width:100%;' valign='bottom' nowrap>
              <label id='back_button' class='drop-shadow' style='display:none;cursor:Pointer;border-top:1px solid white;background-color:#ff3333;text-align:right;'>&lt;&#61;&nbsp;<b>BACK</b>&nbsp;&lt;&#61;</label>
            </td>
          </tr>
        </table>
      </div>
      <div id="search_customer">
        <input type="text" class="awesomplete" id="search_customer_input" placeholder="Type here to search customer ..." spellcheck="false" />
      </div>
      <div id="customer_container" style='display:none;border:2px ridge #bbb;border-radius:5px;margin-top:10px;margin-bottom:8px;background: linear-gradient(to bottom, #333, #111)'>
        <div style='padding:5px;padding-left:15px;'>
          <label>Customer</label>
        </div>
      </div>
      <div class="content-full-height" style='max-height:<?php echo $ismobile ? 'none' : "none;height:100%;" ?>;'>
        <div id="table_container" class='common-table-wrapper' style='display:none;height:100%;min-height:150px;max-height:100%;'>
          <table id='customer_table' class='common-table' cellspacing="0" cellpadding="0" style='height:100%;'>
          </table>
        </div>
      </div>
    </div>
  </div>
  <div id="eventdispatcher" style="display:none;" />
</body>
</html>

