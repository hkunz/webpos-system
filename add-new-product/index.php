<?php
session_start();
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: ../login.php");
    exit;
}
require '../php/navigation-bar.php';
?>
<!DOCTYPE html>
<html>
<!-- Search Item code: https://www.cloudways.com/blog/live-search-php-mysql-ajax/ -->
<head>
<meta charset="UTF-8">
  <title>Klebby's Create Item</title>
  <!-- <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script> -->
  <link type="text/css" rel="stylesheet" href="../css/main-styles.css">
  <link type="text/css" rel="stylesheet" href="../css/navigation-bar.css">
  <link type="text/css" rel="stylesheet" href="../css/awesomplete.css">
  <script type="text/javascript" src="../js/libs/jquery-3.4.1.min.js"></script>
  <script type="text/javascript" src="../js/libs/awesomplete.js"></script>
  <script type="text/javascript" src="../js/Utils.js"></script>
  <script type="text/javascript" src="../js/sound-effects.js"></script>
  <script type="text/javascript" src="../js/AwesompleteInputHandler.js"></script>
  <script type="text/javascript" src="js/Controller.js"></script>
  <script type="text/javascript" src="js/GeneralNameInputHandler.js"></script>
  <script type="text/javascript" src="js/BrandNameInputHandler.js"></script>
  <script type="text/javascript" src="js/main.js"></script>
</head>

<body class="body">
  <?php echo $navbar_content; ?>
  <div class="container-wrapper" style="width:50%;">
  <div class="container-left" style="width:100%;">
    <label class="heading"><script type="text/javascript">document.write(Utils.getStoreHeading());</script></label>                                                              | <label class="heading-sub"><script type="text/javascript">document.write(Utils.getStoreSubHeading());</script></label>
    <hr class="division">
    <form autocomplete="off">
      <br/>
      <input style="width:250px;margin-bottom:10px;margin-right:15px;" type="text" id="barcode" pattern="[^0-9]+" maxlength="13" placeholder="13-Digit Bar Code ..." spellcheck="false"/>
      Unit:
      <select id="unit_select" style="margin-right:10px;">
      </select>
      Count:
      <input id="count_input" style="width:60px;" type="number" min="1" value="1" onkeydown="return Utils.notE(event);" disabled>
      <br>
      <input style="width:100%;" autocomplete="off" type="text" id="item_description" maxlength="100" placeholder="Product Description ..." spellcheck="false" autocomplete="false"/>
      <br><br>
      <table border="0" style="margin-left:-3px;margin-top:-11px;margin-bottom:5px;">
        <tr>
          <td>
            <input class="awesomplete" style="width:250px;margin-right:20px;" type="text" id="general_name_input" maxlength="30" spellcheck="false" placeholder="General Name ..."/>
          </td>
          <td>
            <input class="awesomplete" style="width:250px;" type="text" id="brand_name_input" maxlength="25" spellcheck="false" placeholder="Brand Name ..."/>
          </td>
        </tr>
      </table>
      <div>
        <select class="pick-option" id="category_select" style="width:250px;margin-right:15px;margin-bottom:10px" onchange='this.style.color = "#fff"'>
          <option class="pick-option" selected disabled>———&gt; Select Category &lt;———</option>
        </select>
        <select class="pick-option" id="supplier_select" style="width:250px;" onchange='this.style.color="#fff"'>
          <option class="pick-option" selected disabled>———&gt; Select Supplier &lt;———</option>
        </select>
      </div>
      Unit Price:
      <input id="unit_price_input" style="width:90px;margin-bottom:10px;" type="number" min="0" onkeydown="return Utils.notE(event)">
      <br>
      Sell Price:
      <input id="sell_price_input" style="width:90px;margin-bottom:10px;margin-right:8px;" type="number" min="0" onkeydown="return Utils.notE(event)">
      Stock:
      <input id="stock_input" style="width:68px;" type="number" min="0" onkeydown="return Utils.notE(event)">
      <br><br>
      <button id="create_item_button" class="commit-transaction-button" disabled="true">CREATE NEW ITEM</button>
    </form>
  </div>
  </div>
  <div id="eventdispatcher" style="display:none;" />
</body>
</html>

