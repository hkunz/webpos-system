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
  <title>Add New Product</title>
  <link type="text/css" rel="stylesheet" href="<?php echo $href_root; ?>css/main-styles.css">
  <link type="text/css" rel="stylesheet" href="<?php echo $href_root; ?>css/navigation-bar.css">
  <link type="text/css" rel="stylesheet" href="<?php echo $href_root; ?>css/awesomplete.css">
  <link type="text/css" rel="stylesheet" href="<?php echo $href_root; ?>css/responsive-web-page.css">
  <link type="text/css" rel="stylesheet" href="<?php echo ($ismobile ? $href_root . 'css/main-styles-mobile.css' : '') ?>">
  <script type="text/javascript" src="<?php echo $href_root; ?>js/libs/jquery-3.4.1.min.js"></script>
  <script type="text/javascript" src="<?php echo $href_root; ?>js/libs/awesomplete.js"></script>
  <script type="text/javascript" src="<?php echo $href_root; ?>js/Utils.js"></script>
  <script type="text/javascript" src="<?php echo $href_root; ?>js/sound-effects.js"></script>
  <script type="text/javascript" src="<?php echo $href_root; ?>js/AwesompleteInputHandler.js"></script>
  <script type="text/javascript" src="js/Controller.js"></script>
  <script type="text/javascript" src="js/GeneralNameInputHandler.js"></script>
  <script type="text/javascript" src="js/BrandNameInputHandler.js"></script>
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
    <div style="margin-left:2px;margin-bottom:1px;">
      <label class="drop-shadow" style="font-weight:bold;">ADD NEW PRODUCT</label>
    </div>
    <form autocomplete="off">
      <input style="width:100%;max-width:240px;margin-bottom:5px;margin-right:15px;margin-top:10px;" type="text" id="barcode" pattern="[^0-9]+" maxlength="13" placeholder="13-Digit Bar Code ..." spellcheck="false"/>
      <div style='white-space:nowrap;display:inline;'>
      Unit:
      <select id="unit_select" style="margin-right:10px;">
      </select>
      Count:
      <input id="count_input" style="width:60px;" type="number" min="1" value="1" onkeydown="return Utils.not_i(event);" disabled>
      </div>
      <input style="width:100%;" autocomplete="off" type="text" id="item_description" maxlength="100" placeholder="Product Description ..." spellcheck="false" autocomplete="false"/>
      <br>
      <table cellpadding='0x' cellspacing='0' border="0" style="margin-top:8px;margin-bottom:5px;">
        <tr>
          <td>
            <input class="awesomplete" style="width:100%;max-width:250px;" type="text" id="general_name_input" maxlength="30" spellcheck="false" placeholder="General Name ..."/>
          </td>
          <td style='width:10px;'></td>
          <td>
            <input class="awesomplete" style="width:100%;max-width:250px;" type="text" id="brand_name_input" maxlength="25" spellcheck="false" placeholder="Brand Name ..."/>
          </td>
        </tr>
      </table>
      <div>
        <select class="pick-option" id="category_select" style="width:100%;max-width:238px;margin-right:1px;margin-bottom:6px" onchange='this.style.color = "#fff"'>
          <option class="pick-option" selected disabled>——&gt; Select Category &lt;——</option>
        </select>
        <select class="pick-option" id="supplier_select" style="width:100%;max-width:238px;" onchange='this.style.color="#fff"'>
          <option class="pick-option" selected disabled>——&gt; Select Supplier &lt;——</option>
        </select>
      </div>
      Unit Price:
      <input id="unit_price_input" style="width:90px;margin-bottom:10px;" type="number" min="0" onkeydown="return Utils.not(event)">
      <br>
      Sell Price:
      <input id="sell_price_input" style="width:90px;margin-bottom:10px;margin-right:8px;" type="number" min="0" onkeydown="return Utils.not(event)">
      <div>
      Stock:
      <input id="stock_input" style="width:68px;" type="number" min="0" onkeydown="return Utils.not_i(event)">
      </div>
      <br><br>
      <button id="create_item_button" class="commit-transaction-button" disabled="true">ADD NEW PRODUCT</button>
    </form>
  </div>
  </div>
  <div id="eventdispatcher" style="display:none;" />
</body>
</html>

