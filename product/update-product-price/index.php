<?php
session_start();
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: ../../login.php");
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
  <title>Update Product Price</title>
  <link type="text/css" rel="stylesheet" href="<?php echo $href_root; ?>css/main-styles.css">
  <link type="text/css" rel="stylesheet" href="<?php echo $href_root; ?>css/navigation-bar.css">
  <link type="text/css" rel="stylesheet" href="<?php echo $href_root; ?>css/awesomplete.css">
  <link type="text/css" rel="stylesheet" href="<?php echo $href_root; ?>css/common-table.css">
  <link type="text/css" rel="stylesheet" href="<?php echo $href_root; ?>css/responsive-web-page.css">
  <link type="text/css" rel="stylesheet" href="<?php echo ($ismobile ? $href_root . 'css/main-styles-mobile.css' : '') ?>">
  <script type="text/javascript" src="<?php echo $href_root; ?>js/libs/jquery-3.4.1.min.js"></script>
  <script type="text/javascript" src="<?php echo $href_root; ?>js/libs/awesomplete.js"></script>
  <script type="text/javascript" src="<?php echo $href_root; ?>js/AwesompleteInputHandler.js"></script>
  <script type="text/javascript" src="<?php echo $href_root; ?>js/Utils.js"></script>
  <script type="text/javascript" src="<?php echo $href_root; ?>service/js/ItemSearchInputHandler.js"></script>
  <script type="text/javascript" src="<?php echo $href_root; ?>js/sound-effects.js"></script>
  <script type="text/javascript" src="js/ProductSelectionHandler.js"></script>
  <script type="text/javascript" src="js/Controller.js"></script>
  <script type="text/javascript" src="js/main.js"></script>
  <?php require_once("${root}php/favicon.php"); ?>
</head>

<body class="body">
  <?php echo $navbar_content; ?>
  <div class="container-wrapper">
    <div class="container-left">
      <div class='store-heading'>
        <label class="heading"><script type="text/javascript">document.write(Utils.getStoreHeading());</script></label>                                                              | <label class="heading-sub"><script type="text/javascript">document.write(Utils.getStoreSubHeading());</script></label>
        <hr class="division">
      </div>
      <div style="margin-left:2px;margin-bottom:3px;">
        <label class="drop-shadow" style="font-weight:bold;">UPDATE PRODUCT PRICE</label>
      </div>
      <div class="search_item">
        <input type="text" class="awesomplete" id="search_item_input" placeholder="Type here to search product ..." spellcheck="false" />
      </div>
      <div id="product_name_container" class='common-table-wrapper' style='overflow-y:visible;margin-top:13px;margin-bottom:5px;display:none;padding:2px;background: linear-gradient(to bottom, #333, #444)'>
        <div>
          <div class='header' style='height:100%;'>
            <div style='padding:8px;padding-left:15px;'>
              <label id="product_code" class="standard-label drop-shadow" style="display:block"></label>
              <label id="product_name" class="product-caption"></label>
            </div>
          </div>
        </div>
        <div id="price_editor_container" style='width:100%;margin:0px;margin-top:10px;margin-bottom:10px;padding-left:15px;padding-right:15px;'>
          <div style='width:100%;'>
            <table cellpadding='0' cellspacing='0' <?php if ($ismobile) echo 'style="width:100%;"' ; ?>>
              <tr><td>
                <label class='standard-label'>Curr Unit Price:</label></td><td style='text-align:<?php echo $ismobile ? "right" : "left"; ?>'><div style='white-space:nowrap;'><label class='standard-label'><script type="text/javascript">document.write(Utils.getCurrencySymbol());</script>&nbsp;</label><input id='unit_price_input' type="number" min='0' placeholder='' onkeydown="return Utils.not(event);" style='color:#ffffaa;width:100%;max-width:100px;margin-bottom:5px;'></div>
              </tr><tr><td>
                <label class='standard-label'>Curr Sell Price:</label></td><td style='text-align:<?php echo $ismobile ? "right" : "left"; ?>'><div style='white-space:nowrap;'><label class='standard-label'><script type="text/javascript">document.write(Utils.getCurrencySymbol());</script>&nbsp;</label><input id='sell_price_input' type="number" min='0' placeholder='' onkeydown="return Utils.not(event);" style='color:#ffffaa;width:100%;max-width:100px;'></div>
              </td></tr> 
            </table>
          </div>
          <div style='display:block;float:left;padding-top:10px;padding-bottom:10px;width:100%;max-width:<?php echo ($mobile) ? "100%" : "100%" ?>;'>
            <button id="update_price_button" class="commit-transaction-button" disabled="true">UPDATE PRODUCT PRICE</button>
          </div>
        </div>
      </div>
      <div id="table_container" style="width:100%;margin:0px;">
        <div style='padding-right:<?php echo $ismobile ? "0px" : "6px"; ?>;width:<?php echo $ismobile ? "100%" : "50%"; ?>;float:left;'>
          <div class="common-table-wrapper" style='width:100%;'>
            <table class='common-table' cellspacing="0" cellpadding="0">
              <thead><tr><th>Unit Price History</th><th align='right' nowrap></th></tr></thead>
              <tbody><tr><td>&nbsp;</td><td></td></tr></tbody>
            </table>
          </div>
        </div>
        <div style='padding-left:<?php echo $ismobile ? "0px" : "6px"; ?>;width:<?php echo $ismobile ? "100%" : "50%"; ?>;float:left;'>
          <div class="common-table-wrapper" style='width:100%'> 
            <table class='common-table' cellspacing="0" cellpadding="0">
              <thead><tr><th>Sell Price History</th><th align='right' nowrap></th></tr></thead>
              <tbody><tr><td>&nbsp;</td><td></td></tr></tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div id="eventdispatcher" style="display:none;" />
</body>
</html>

