<!DOCTYPE html>
<html>
<!-- Search Item code: https://www.cloudways.com/blog/live-search-php-mysql-ajax/ -->
<head>
<meta charset="UTF-8">
  <title>Klebby's Create Item</title>
  <!-- <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script> -->
  <link type="text/css" rel="stylesheet" href="../css/main-styles.css">
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
  <div class="container-wrapper">
  <div class="container-left">
    <label class="heading"><script type="text/javascript">document.write(Utils.getStoreHeading());</script></label>                                                              | <label class="heading-sub"><script type="text/javascript">document.write(Utils.getStoreSubHeading());</script></label>
    <form>
    <br/>
      Bar Code:
      <input style="width:165px;" type="text" id="barcode" maxlength="13" placeholder="13-Digit Bar Code ..." spellcheck="false"/>
      <br><br>
      Item Description:
      <input style="width:550px;" type="text" id="item_description" maxlength="100" placeholder="Item Description ..." spellcheck="false"/>
      <br><br>
      Unit:
      <select id="unit_select">
      </select>
      Count:
      <input id="count_input" style="width:60px;" type="number" min="1" value="1">
      <br><br>
      General Name:
      <input class="awesomplete" style="width:200px;" type="text" id="general_name_input" maxlength="30" spellcheck="false" placeholder="General Name ..."/>
      <br><br>
      Brand Name:
      <input class="awesomplete" style="width:200px;" type="text" id="brand_name_input" maxlength="25" spellcheck="false" placeholder="Brand Name ..."/>
      <br><br>
      Category:
      <select id="category_select">
      </select>
      <br><br>
      Supplier:
      <select id="supplier_select">
      </select>
      <br><br>
      Unit Price:
      <input id="unit_price_input" style="width:90px;" type="number">
      Sell Price:
      <input id="sell_price_input" style="width:90px;" type="number">
      <br><br>
      Stock:
      <input id="sell_price_input" style="width:90px;" type="number" min="0">
      <br><br>
    </form>
  </div>
  <div class="container-right">
  </div>
  </div>
  <div id="eventdispatcher" style="display:none;" />
</body>
</html>

