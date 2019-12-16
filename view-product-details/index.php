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
        <label class="drop-shadow" style="font-weight:bold;">VIEW PRODUCT DETAILS</label>
      </div>
      <div class="search_item">
        <input type="text" class="awesomplete" id="search_item_input" placeholder="Type here to search product ..." spellcheck="false" />
      </div>
      <div class="common-table-wrapper">
      <table class="common-table" cellspacing="0" cellpadding="0">
        <tr>
          <th>Property</th>
          <th>Value</th>
        </tr>
        <tr>
          <td width="150px"><label class="standard-label">Product ID</label></td>
          <td><label id="product_id" class="drop-shadow"></label>
        </tr>
        <tr>
          <td><label class="standard-label">Bar Code</label></td>
          <td><label id="product_barcode" class="drop-shadow"></label>
        </tr>
        <tr>
          <td><label class="standard-label">Description</label></td>
          <td><label id="product_description" class="drop-shadow"></label>
        </tr>
        <tr>
          <td><label class="standard-label">Unit</label></td>
          <td><label id="unit" class="drop-shadow"></label>
        </tr>
        <tr>
          <td><label class="standard-label">Content Count</label></td>
          <td><label id="content_count" class="drop-shadow"></label>
        </tr>
        <tr>
          <td><label class="standard-label">Stock</label></td>
          <td><label id="stock" class="drop-shadow"></label>
        </tr>
        <tr>
          <td><label class="standard-label">Category</label></td>
          <td><label id="product_category" class="drop-shadow"></label>
        </tr>
        <tr>
          <td><label class="standard-label">Unit Price</label></td>
          <td><label id="unit_price" class="drop-shadow"></label>
        </tr>
        <tr>
          <td><label class="standard-label">Sell Price</label></td>
          <td><label id="sell_price" class="drop-shadow"></label>
        </tr>
        <tr>
          <td><label class="standard-label">General Name</label></td>
          <td><label id="general_name" class="drop-shadow"></label>
        </tr>
        <tr>
          <td><label class="standard-label">Brand Name</label></td>
          <td><label id="brand_name" class="drop-shadow"></label>
        </tr>
        <tr>
          <td><label class="standard-label">Supplier</label></td>
          <td><label id="product_supplier" class="drop-shadow"></label>
        </tr>
        <tr>
          <td><label class="standard-label">Manufacturer</label></td>
          <td><label id="product_manufacturer" class="drop-shadow"></label>
        </tr>
        </table>
        </div>
      </div>
    </div>
  <div id="eventdispatcher" style="display:none;" />
</body>
</html>

