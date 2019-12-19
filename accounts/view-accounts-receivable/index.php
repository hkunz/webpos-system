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
        <label class="drop-shadow" style="font-weight:bold;">ACCOUNTS RECEIVABLE</label>
      </div>
      <div id="search_customer">
        <input type="text" class="awesomplete" id="search_customer_input" placeholder="Type here to search customer ..." spellcheck="false" />
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

