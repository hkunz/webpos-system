<?php
session_start();
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: ../login.php");
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
  <script type="text/javascript" src="<?php echo $href_root; ?>service/js/ItemSearchInputHandler.js"></script>
  <script type="text/javascript" src="<?php echo $href_root; ?>js/sound-effects.js"></script>
  <script type="text/javascript" src="js/ProductSelectionHandler.js"></script>
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
        <label class="drop-shadow" style="font-weight:bold;">UPDATE PRODUCT PRICE</label>
      </div>
      <div class="search_item">
        <input type="text" class="awesomplete" id="search_item_input" placeholder="Type here to search product ..." spellcheck="false" />
      </div>
      <div id="product_name_container" class='common-table-wrapper' style='margin-top:13px;margin-bottom:5px;display:none;padding:15px;background: linear-gradient(to bottom, #333, #444)'>
      <div>
        <label id="product_code" class="standard-label drop-shadow" style="display:block"></label>
        <label id="product_name" class="product-caption"></label>
      </div>
      <div id="price_editor_container" style='width:100%;margin:0px;margin-top:10px;margin-bottom:10px;'>
        <table width='100%' cellpadding='0' cellspacing='0'>
          <tr><td width='300px;'>
            <label class='standard-label'>Current Unit Price: </label><input id='unit_price_input' type="number" min='0' placeholder='' onkeydown="return Utils.not(event);" style='color:#ffffaa;width:100px;margin-bottom:5px;'><br>
            <label class='standard-label'>Current Sell Price: </label><input id='sell_price_input' type="number" min='0' placeholder='' onkeydown="return Utils.not(event);" style='color:#ffffaa;width:100px;'>
          </td><td>
            <button id="update_price_button" class="commit-transaction-button" style='width:210px;' disabled="true">UPDATE PRODUCT PRICE</button>
          </td></tr>
        </table>
      </div>
      </div>
      <div id="table_container" style="width:100%;margin:0px;">
        <table cellspacing="0" cellpadding="0" width="100%"><tr><td>
          <div class="common-table-wrapper" style='margin-right:8px;'>
            <table class='common-table' cellspacing="0" cellpadding="0">
              <tr><th>Unit Price History</label></th><th>Unit Price</th></tr>
              <tr><td>&nbsp;</td><td></td></tr>
            </table>
          </div></td><td>
          <div class="common-table-wrapper" style='margin-left:8px;'> 
            <table class='common-table' cellspacing="0" cellpadding="0">
              <tr><th>Sell Price History</th><th>Sell Price</th></tr>
              <tr><td>&nbsp;</td><td></td></tr>
            </table>
          </div></td></tr>
        </table>
      </div>
    </div>
  </div>
  <div id="eventdispatcher" style="display:none;" />
</body>
</html>

