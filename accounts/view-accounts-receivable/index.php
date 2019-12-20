<?php
session_start();
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: ../../login.php");
    exit;
}
$href_root = $_SESSION['href_root'];
require $_SESSION['root'] . 'php/navigation-bar.php';
?>
<!DOCTYPE html>
<html>
<!-- Search Item code: https://www.cloudways.com/blog/live-search-php-mysql-ajax/ -->
<head>
<meta charset="UTF-8">
  <title>Klebby's Store</title>
  <!-- <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script> -->
  <link type="text/css" rel="stylesheet" href="<?php echo $href_root; ?>css/main-styles.css">
  <link type="text/css" rel="stylesheet" href="<?php echo $href_root; ?>css/navigation-bar.css">
  <link type="text/css" rel="stylesheet" href="<?php echo $href_root; ?>css/awesomplete.css">
  <link type="text/css" rel="stylesheet" href="<?php echo $href_root; ?>css/common-table.css">
  <script type="text/javascript" src="<?php echo $href_root; ?>js/libs/jquery-3.4.1.min.js"></script>
  <script type="text/javascript" src="<?php echo $href_root; ?>js/libs/awesomplete.js"></script>
  <script type="text/javascript" src="<?php echo $href_root; ?>js/AwesompleteInputHandler.js"></script>
  <script type="text/javascript" src="<?php echo $href_root; ?>js/Utils.js"></script>
  <script type="text/javascript" src="<?php echo $href_root; ?>js/sound-effects.js"></script>
  <script type="text/javascript" src="<?php echo $href_root; ?>service/js/CustomerSearchInputHandler.js"></script>
  <script type="text/javascript" src="<?php echo $href_root; ?>js/common/TableRowHandler.js"></script>
  <script type="text/javascript" src="js/ViewState.js"></script>
  <script type="text/javascript" src="js/AccountsReceivableByCustomerHandler.js"></script>
  <script type="text/javascript" src="js/Controller.js"></script>
  <script type="text/javascript" src="js/main.js"></script>
</head>

<body class="body">
  <?php echo $navbar_content; ?>
  <div class="container-wrapper">
    <div class="container-left">
      <label class="heading"><script type="text/javascript">document.write(Utils.getStoreHeading());</script></label>                                                              | <label class="heading-sub"><script type="text/javascript">document.write(Utils.getStoreSubHeading());</script></label>
      <hr class="division">
      <div style="margin-left:2px;margin-bottom:3px;">
	<table id='header_table' cellpadding='0' cellspacing='0' style=''>
          <tr>
            <td nowrap>
              <label class="drop-shadow" style="font-weight:bold;">ACCOUNTS RECEIVABLE: </label><label id='colon_label' class="drop-shadow" style="display:none;font-weight:bold;"></label>
            </td>
            <td nowrap>
              <label class="drop-shadow">&nbsp;(</label><label id='customer_total' class="drop-shadow" style="color:#44ff44;font-weight:bold;"></label><label class="drop-shadow">)&nbsp;</label>
            </td>
            <td nowrap>
              <div id='customer_div' style='display:none;margin:0px;padding:0px;'>
                <label class="drop-shadow" style=""></label><label id='customer_label' class="drop-shadow" style="font-weight:bold;color:white;"></label><label class="drop-shadow" style=""></label>
              </div>
            </td>
            <td nowrap>
	      <div id='transaction_td' style='margin:0px;padding:0px;'>
                <label style='color:#fff;'>&nbsp;&#61;&gt;&nbsp;</label><label id='transaction_label' class='drop-shadow' style='color:#aaa;'></label>
              </div>
            </td>
            <td style='text-align:right;width:100%;'>
              <label id='back_button' class='drop-shadow' style='display:none;cursor:Pointer;text-decoration:overline;background-color:#ff3333;text-align:right;'>&lt;&#61;&nbsp;BACK&nbsp;&lt;&#61;</label>
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
      <div id="table_container" class='common-table-wrapper' style='display:none;height:290px;'>
        <table id='customer_table' class='common-table' cellspacing="0" cellpadding="0">
        </table>
      </div>
    </div>
  </div>
  <div id="eventdispatcher" style="display:none;" />
</body>
</html>

