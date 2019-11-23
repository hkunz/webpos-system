<?php
  include "../php/db.php"
?>
<!DOCTYPE html>
<html>
<!-- Search Item code: https://www.cloudways.com/blog/live-search-php-mysql-ajax/ -->
<head>
<meta charset="UTF-8">
  <title>Klebby's Store</title>
  <!-- <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script> -->
  <link type="text/css" rel="stylesheet" href="css/main-styles.css">
  <link type="text/css" rel="stylesheet" href="css/item-amount-popup.css">
  <link type="text/css" rel="stylesheet" href="css/items-list-styles.css">
  <link type="text/css" rel="stylesheet" href="css/grand-total-view.css">
  <link type="text/css" rel="stylesheet" href="css/awesomplete.css">
  <script type="text/javascript" src="js/libs/jquery-3.4.1.min.js"></script>
  <script type="text/javascript" src="js/libs/awesomplete.js"></script>
  <script type="text/javascript" src="js/constants.js"></script>
  <script type="text/javascript" src="js/ItemAmountInputPopupHandler.js"></script>
  <script type="text/javascript" src="js/ItemSearchInputHandler.js"></script>
  <script type="text/javascript" src="js/ItemSelectionListHandler.js"></script>
  <script type="text/javascript" src="js/GrandTotalViewHandler.js"></script>
  <script type="text/javascript" src="js/SqlTransactionHandler.js"></script>
  <script type="text/javascript" src="../js/sound-effects.js"></script>
  <script type="text/javascript" src="js/main.js"></script>
</head>

<body bgcolor="#454545">
  <div class="hover_bkgr_fricc" id="amount_popup_box">
    <span class="helper"></span>
    <div style="width:100%;">
        <div class="popupCloseButton">X</div>
        <label id="popup_item_code" style="display:block;color:#888888;padding-bottom:6px;font-weight:bold"></label>
        <div style="width:100%;border:0px solid #222222;border-radius:5px">
          <label id="popup_item_description" style="display:block;padding-bottom:10px;text-shadow:1px 1px #111111;"></label>
        </div>
	<label id="popup_item_category" style="color:#888888"></label><br/>
	<label>===&gt;&nbsp;</label><label id="popup_item_price" style="color:#FF3333;font-weight:bold"></label><label>&nbsp;&lt;===</label><br/>
	<form>
        	<input class="amount-input" style="margin-top:10px;margin-left:25px;" type="number" id="popup_amount_input" min="1" max="999"/>
        	<label id="item_amount_unit"></label>
	</form>
    </div>
  </div>
  <div class="container-wrapper">
  <div class="container-left">
    <span class="heading">
       <u><b style="color:#FFFF00;">Klebby's Supplies &amp; Computer Services</b></u></span><span> | </span><span style="color:#CCCCCC;">KUNZ Inc</span>
    <form>
    <br/>
      Customer:&nbsp;&nbsp;&nbsp;
      <input type="text" id="customer" maxlength="30" placeholder="Customer Name" spellcheck="false"/>
      <br>
      Transaction:
      <select id="transaction_type">
        <option value="sale">SALE</option>
        <option value="restock">RESTOCK</option>
        <option value="return">RETURN</option>
        <option value="loss">LOSS</option>
        <option value="surplus">SURPLUS</option>
      </select>
      <br>
      Timestamp:&nbsp;&nbsp;
      <input type="datetime-local" id="transaction_timestamp" step="1">
      <br>
    </form>
    Item Search:
    <div style="width:100%">
      <input type="text" class="awesomplete" id="search_item_input" placeholder="Type here to search item ..." spellcheck="false" />
    </div>
    <div class="items-list-header">
      <div style="float:left;width:30px;height:100%;background-color:#111111">
        <label class="items-list-header">&nbsp;#</label>
      </div>
      <label class="items-list-header" style="width:15px;"></label>
      <label class="items-list-header" style="width:66px;">Qty</label>
      <label class="items-list-header" style="width:300px;">Description</label>
    </div>
    <div class="items-list" id="items_list"/>
  </div>
  </div>
  <div class="container-right">
    <div class="receipt">
      <label class="receipt">RECEIPT</label>
    </div>
    <div class="transaction-text-div">
      Transaction ID: <span style="color:#ffff00;"><b><?php echo mysqli_fetch_array(mysqli_query($con, "SELECT get_next_transaction_id()"))[0]?></b></span>
    </div>
    <hr/>
    <table class="totals-grid">
      <tr class="totals-tr">
        <td><label class="label-text">Sub-Total:</label></td>
        <td class="td-value"><label id="sub_total_value" class="calc-amount-value">0.00</label></td>
      </tr>
      <tr class="totals-tr">
        <td><hr class="division"/></td>
      </tr>
      <tr class="totals-tr">
        <td><label class="label-text">Discount:</label></td>
        <td class="td-value"><label id="discount_value" class="calc-amount-value">0.00</label></td>
      </tr>
      <tr class="totals-tr">
        <td><hr class="division"/></td>
      </tr>
      <tr class="totals-tr">
        <td>
          <label class="grand-total">GRAND TOTAL: </label>
        </td>
        <td></td>
      </tr>
      <tr class="totals-tr">
        <td></td>
        <td class="td-value">
          <div class="grand-total-value" style="background-color:#222222;height:35px;text-align:center">
            <label class="grand-total-value" style="padding-right:5px;">₱</label><label id="grand_total_value" class="grand-total-value">0.00</label>
          </div>
        </td>
      </tr>
      <tr height="10px"></tr>
      <tr class="totals-tr">
        <td><label class="label-text">Cash:</label></td>
        <td class="td-value">
          <input class="cash-input" type="number" id="cash_input" disabled="true" min="0" placeholder="₱"/>
        </td>
      </tr>
      <tr class="totals-tr">
        <td><hr class="division"/></td>
      </tr>
      <tr class="totals-tr">
        <td><label class="label-text">Change:</label></td>
        <td class="td-value"><label id="cash_change" class="cash-change"></label></td>
      </tr>
    </table>
    <br />
    <button id="commit_transaction_button" class="commit-transaction-button" disabled="true">COMMIT TRANSACTION</button>
  </div>
  </div>
  <div id="eventdispatcher" style="display:none;" />
</body>
</html>

