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
  <title>View Product Details</title>
  <link type="text/css" rel="stylesheet" href="<?php echo $href_root; ?>css/main-styles.css">
  <link type="text/css" rel="stylesheet" href="<?php echo $href_root; ?>css/navigation-bar.css">
  <link type="text/css" rel="stylesheet" href="<?php echo $href_root; ?>css/awesomplete.css">
  <link type="text/css" rel="stylesheet" href="<?php echo $href_root; ?>css/common-table.css">
  <link type="text/css" rel="stylesheet" href="<?php echo ($ismobile ? $href_root . 'css/main-styles-mobile.css' : '') ?>">
  <link type="text/css" rel="stylesheet" href="css/style.css">
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
        <label class="heading"><script type="text/javascript">document.write(Utils.getStoreHeading());</script></label> | <label class="heading-sub"><script type="text/javascript">document.write(Utils.getStoreSubHeading());</script></label>
        <hr class="division">
      </div>
      <div style="margin-left:2px;margin-bottom:3px;">
        <label class="drop-shadow" style="font-weight:bold;">VIEW PRODUCT DETAILS</label>
      </div>
      <div class="search_item">
        <input type="text" class="awesomplete" id="search_item_input" placeholder="Type here to search product ..." spellcheck="false" />
      </div>
      <div class="common-table-wrapper" style='height:100%;max-height:100%;display:flex;flex-flow:column;'>
      <div>
      <table class="common-table" cellspacing="0" cellpadding="0" style=''>
        <thead style='display:table;'>
        <tr>
          <th id="product_description" colspan='2'>Description</th>
        </tr>
        </thead>
      </table>
      </div>
      <div style='overflow-y:auto;flex:1;height:100%;'>
      <table class="common-table" cellspacing="0" cellpadding="0" style='height:100%;'>
        <tbody style='display:flex;flex-flow:column;height:100%;overflow:hidden'>
        <tr>
          <td class='td-col'><label class="standard-label">Product ID</label></td>
          <td class='td2-col'><label id="product_id" class="drop-shadow"></label>
        </tr>
        <tr>
          <td class='td-col'><label class="standard-label">Bar Code</label></td>
          <td class='td2-col'><label id="product_barcode" class="drop-shadow"></label>
        </tr>
        <tr>
          <td class='td-col'><label class="standard-label">Content Count</label></td>
          <td class='td2-col'><label id="content_count" class="drop-shadow"></label>
        </tr>
        <tr>
          <td class='td-col'><label class="standard-label">Stock</label></td>
          <td class='td2-col'><label id="stock" class="drop-shadow"></label>
        </tr>
        <tr>
          <td class='td-col'><label class="standard-label">Category</label></td>
          <td class='td2-col'><label id="product_category" class="drop-shadow"></label>
        </tr>
        <tr>
          <td class='td-col'><label class="standard-label">Unit Price</label></td>
          <td class='td2-col'><label id="unit_price" class="drop-shadow"></label>
        </tr>
        <tr>
          <td class='td-col'><label class="standard-label">Sell Price</label></td>
          <td class='td2-col'><label id="sell_price" class="drop-shadow"></label>
        </tr>
        <tr>
          <td class='td-col'><label class="standard-label">General Name</label></td>
          <td class='td2-col'><label id="general_name" class="drop-shadow"></label>
        </tr>
        <tr>
          <td class='td-col'><label class="standard-label">Brand Name</label></td>
          <td class='td2-col'><label id="brand_name" class="drop-shadow"></label>
        </tr>
        <tr>
          <td class='td-col'><label class="standard-label">Supplier</label></td>
          <td class='td2-col'><label id="product_supplier" class="drop-shadow"></label>
        </tr>
        <tr>
          <td class='td-col'><label class="standard-label">Manufacturer</label></td>
          <td class='td2-col'><label id="product_manufacturer" class="drop-shadow"></label>
        </tr>
        </tbody>
        </table>
        </div>
        </div>
      </div>
    </div>
  <div id="eventdispatcher" style="display:none;" />
</body>
</html>

