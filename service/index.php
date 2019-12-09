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
  <title>Klebby's Store</title>
  <!-- <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script> -->
  <link type="text/css" rel="stylesheet" href="../css/main-styles.css">
  <link type="text/css" rel="stylesheet" href="../css/navigation-bar.css">
  <link type="text/css" rel="stylesheet" href="css/item-amount-popup.css">
  <link type="text/css" rel="stylesheet" href="css/items-list-styles.css">
  <link type="text/css" rel="stylesheet" href="css/grand-total-view.css">
  <link type="text/css" rel="stylesheet" href="../css/awesomplete.css">
  <script type="text/javascript" src="../js/libs/jquery-3.4.1.min.js"></script>
  <script type="text/javascript" src="../js/libs/awesomplete.js"></script>
  <script type="text/javascript" src="../js/AwesompleteInputHandler.js"></script>
  <script type="text/javascript" src="../js/Utils.js"></script>
  <script type="text/javascript" src="js/Controller.js"></script>
  <script type="text/javascript" src="js/constants.js"></script>
  <script type="text/javascript" src="js/ItemAmountInputPopupHandler.js"></script>
  <script type="text/javascript" src="js/ItemSearchInputHandler.js"></script>
  <script type="text/javascript" src="js/CustomerSearchInputHandler.js"></script>
  <script type="text/javascript" src="js/ItemSelectionListHandler.js"></script>
  <script type="text/javascript" src="js/GrandTotalViewHandler.js"></script>
  <script type="text/javascript" src="js/SqlTransactionHandler.js"></script>
  <script type="text/javascript" src="../js/sound-effects.js"></script>
  <script type="text/javascript" src="js/main.js"></script>
</head>

<body class="body">
  <div class="hover_bkgr_fricc" id="amount_popup_box">
    <span class="helper"></span>
    <div style="width:100%;">
        <div class="popupCloseButton">X</div>
        <label id="popup_item_code" style="display:block;color:#888888;padding-bottom:6px;font-weight:bold"></label>
        <div style="width:100%;border:0px solid #222222;border-radius:5px">
          <label id="popup_item_description" style="display:block;padding-bottom:10px;text-shadow:1px 1px #111111;"></label><label id="popup_item_stock" style="color:#FFFF00">
        </div>
	<label id="popup_item_stock" style="color:#FFFF00"></label><br/>
	<label id="popup_item_category" style="color:#888888"></label><br/>
	<label>===&gt;&nbsp;</label><label id="popup_item_price" style="color:#FF3333;font-weight:bold;text-shadow: 1px 1px #222222;"></label><label>&nbsp;&lt;===</label><br/>
	<form>
        	<input class="amount-input" style="margin-top:10px;margin-left:25px;" type="number" id="popup_amount_input" min="1" max="999" onkeydown="return Utils.notE(event);"/>
        	<label id="item_amount_unit"></label>
	</form>
    </div>
  </div>
  <?php echo $navbar_content; ?>
  <div class="container-wrapper">
  <div class="container-left">
    <label class="heading"><script type="text/javascript">document.write(Utils.getStoreHeading());</script></label>                                                              | <label class="heading-sub"><script type="text/javascript">document.write(Utils.getStoreSubHeading());</script></label>
    <hr class="division">
    <form>
      <div style="margin-bottom:5px;margin-left:-2px">
        <table>
          <tr><td valign="middle">
            Customer:&nbsp;&nbsp;&nbsp;
          </td><td valign="middle">
            <input type="text" id="customer" maxlength="30" style="margin-left:4px;" placeholder="Customer Name ..." spellcheck="false"/>
          </td></td>
        </table>
      </div>
      Transaction:
      <select id="transaction_type">
        <option value="sale">SALE</option>
        <option value="restock">RESTOCK</option>
        <option value="return">RETURN</option>
        <option value="loss">LOSS</option>
      </select>
      <div style="margin-left:-2px;margin-top:-7px;">
      <table cellpadding="0">
        <tr>
          <td valign="middle" width="111px">
            Timestamp:
          </td><td valign="top" width="135px">
            <div class="checkbox-container" style="margin-right:10px">
              <label class="checkbox-container">Today-Now
                <input id="use_currentdate_checkbox" type="checkbox" checked>
                <span class="checkmark"></span>
              </label>
            </div>
          </td><td valign="middle">
            <input type="datetime-local" id="transaction_timestamp" step="1" style="width:250px;margin-top:6px;" disabled>
          </td>
        </tr>
      </table>
      </div>
    </form>
    Product Search:
    <div class="search_item">
      <input type="text" class="awesomplete" id="search_item_input" placeholder="Type here to search product ..." spellcheck="false" />
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
  <div class="container-right" style="">
    <div class="receipt">
      <label class="receipt">RECEIPT</label>
    </div>
    <div class="transaction-text-div">
      Transaction ID: <label id="transaction_id" style="color:#ffff00;font-weight:bold;">&nbsp;</label>
    </div>
    <br/>
    <table class="totals-grid">
      <tr class="totals-tr">
        <td><label class="label-text">Sub-Total:</label></td>
        <td class="td-value"><label id="sub_total_value" class="calc-amount-value">0.00</label></td>
      </tr>
      <tr class="totals-tr">
        <td colspan="2"><hr class="division"/></td>
      </tr>
      <tr class="totals-tr">
        <td><label class="label-text">Service Charge:</label></td>
        <td class="td-value">
          <input class="cash-input" type="number" id="service_charge_input" disabled="true" min="0" placeholder="₱" onkeydown="return Utils.notE(event);"/>
        </td>
      </tr>
      <tr class="totals-tr">
        <td colspan="2"><hr class="division"/></td>
      </tr>
      <tr class="totals-tr">
        <td><label class="label-text">Discount:</label></td>
        <td class="td-value"><label id="discount_value" class="calc-amount-value">0.00</label></td>
      </tr>
      <tr class="totals-tr">
        <td colspan="2"><hr class="division"/></td>
      </tr>
      <tr class="totals-tr">
        <td>
          <label class="grand-total">GRAND TOTAL:</label> 
        </td>
      </tr>
      <tr class="totals-tr">
      </tr>
      <tr class="totals-tr">
        <td class="td-value" colspan="2">
          <div class="grand-total-value">
            <label class="grand-total-value" style="padding-right:5px;">₱</label><label id="grand_total_value" class="grand-total-value">0.00</label>
          </div>
        </td>
      </tr>
      <tr class="totals-tr">
        <td colspan="2">
          <div class="checkbox-container">
            <label class="checkbox-container">Require payment
              <input id="require_payment_checkbox" type="checkbox" checked>
              <span class="checkmark"></span>
            </label>
          </div>
        </td>
      </tr>
      <tr class="totals-tr">
        <td><label class="label-text">Cash:</label></td>
        <td class="td-value">
          <input class="cash-input" type="number" id="cash_input" disabled="true" min="0" placeholder="₱" onkeydown="return Utils.notE(event);"/>
        </td>
      </tr>
      <tr class="totals-tr">
        <td colspan="2"><hr class="division"/></td>
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

