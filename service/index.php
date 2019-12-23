<?php
session_start();
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: ../login.php");
    exit;
}
$root = $_SESSION['root'];
$href_root = $_SESSION['href_root'];
require $root . 'php/navigation-bar.php';
?>
<!DOCTYPE html>
<html lang="en">
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- Search Item code: https://www.cloudways.com/blog/live-search-php-mysql-ajax/ -->
  <title>Klebby's Store</title>
  <!-- <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script> -->
  <link type="text/css" rel="stylesheet" href="<?php echo $href_root; ?>css/main-styles.css">
  <link type="text/css" rel="stylesheet" href="<?php echo $href_root; ?>css/navigation-bar.css">
  <link type="text/css" rel="stylesheet" href="<?php echo $href_root; ?>css/awesomplete.css">
  <link type="text/css" rel="stylesheet" href="<?php echo $href_root; ?>css/common-table.css">
  <link type="text/css" rel="stylesheet" href="<?php echo $href_root; ?>css/responsive-web-page.css">
  <link type="text/css" rel="stylesheet" href="<?php require_once("${root}php/detect-mobile-device.php"); ?>">
  <link type="text/css" rel="stylesheet" href="css/item-amount-popup.css">
  <link type="text/css" rel="stylesheet" href="css/items-list-styles.css">
  <link type="text/css" rel="stylesheet" href="css/grand-total-view.css">
  <script type="text/javascript" src="<?php echo $href_root; ?>js/libs/jquery-3.4.1.min.js"></script>
  <script type="text/javascript" src="<?php echo $href_root; ?>js/libs/awesomplete.js"></script>
  <script type="text/javascript" src="<?php echo $href_root; ?>js/AwesompleteInputHandler.js"></script>
  <script type="text/javascript" src="<?php echo $href_root; ?>js/Utils.js"></script>
  <script type="text/javascript" src="<?php echo $href_root; ?>js/sound-effects.js"></script>
  <script type="text/javascript" src="js/Controller.js"></script>
  <script type="text/javascript" src="js/constants.js"></script>
  <script type="text/javascript" src="js/ItemAmountInputPopupHandler.js"></script>
  <script type="text/javascript" src="js/ItemSearchInputHandler.js"></script>
  <script type="text/javascript" src="js/CustomerSearchInputHandler.js"></script>
  <script type="text/javascript" src="js/ItemSelectionListHandler.js"></script>
  <script type="text/javascript" src="js/GrandTotalViewHandler.js"></script>
  <script type="text/javascript" src="js/SqlTransactionHandler.js"></script>
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
          <input class="amount-input" style="margin-top:10px;margin-left:25px;" type="number" id="popup_amount_input" min="1" max="999" onkeydown="return Utils.not_i(event);"/>
          <label id="item_amount_unit"></label>
	</form>
    </div>
  </div>
  <?php echo $navbar_content; ?>
  <div class="container-wrapper" style='padding-bottom:15px;'>
  <div class="container-left" style='max-width:800px;margin-right:20px;'>
    <div class='store-heading'>
      <label class="heading"><script type="text/javascript">document.write(Utils.getStoreHeading());</script></label>                                                              | <label class="heading-sub"><script type="text/javascript">document.write(Utils.getStoreSubHeading());</script></label>
      <hr class="division">
    </div>
    <form>
      <div style="margin-bottom:5px;">
        <input type="text" id="customer" maxlength="30" style="max-width:250px;" placeholder="Customer Name ..." spellcheck="false"/>
      </div>
      <table cellspacing='0' cellpadding='0' style='width:100%;max-width:275px;'>
        <tr>
          <td>
            <div style='width:100%;max-width:140px;padding-right:10px;'>
              <select id="transaction_type" style='width:100%'>
                <option value="sale">SALE</option>
                <option value="restock">RESTOCK</option>
                <option value="return">RETURN</option>
                <option value="loss">LOSS</option>
              </select>
            </div>
          </td>
          <td valign='top'>
            <div class="checkbox-container" style="margin:0px;width:100%;min-width:120px;max-width:120px;">
              <label class="checkbox-container" style='width:100%;max-width:120px;'>Today-Now
                <input id="use_currentdate_checkbox" type="checkbox" checked>
                <span class="checkmark"></span>
              </label>
            </div>
          </td>
        </tr>
      </table>
      <div id="timestamp_input" style='display:none;'>
        <input type="datetime-local" id="transaction_timestamp" step="1" style="max-width:250px;height:33px;" disabled>
      </div>
    </form>
    <label class="drop-shadow" style='display:none;'>Product Search:</label>
    <input type="text" class="awesomplete" style='width:100%;' id="search_item_input" placeholder="Search product ..." spellcheck="false" />
    <div id='items_selection_div' class="common-table-wrapper" style='border-color:#aaa;padding-bottom:32px;'>
      <div class="items-list-header" style='border-bottom: 2px ridge #333333;'>
        <div style="float:left;width:30px;height:100%;background-color:#111111">
          <label class="items-list-header" style="padding-left:11px;padding-top:2px;">#</label>
        </div>
        <label class="items-list-header" style="width:15px;"></label>
        <label class="items-list-header" style="width:66px;padding-top:2px;">Qty</label>
        <label class="items-list-header" style="white-space:nowrap;max-width:130px;width:100%px;padding-top:2px;" nowrap>Description</label>
      </div>
      <div class="items-list-container">
        <div class="items-list" id="items_list"/>
      </div>
    </div>
  </div>
  </div>
  <div class="container-right">
    <div class="receipt">
      <label class="receipt">TXN ID:&nbsp;</label><label class="receipt" style='color:white' id="transaction_id">&nbsp;</label>
    </div>
    <table class="totals-grid">
      <tr class="totals-tr">
        <td><label class="label-text">Sub-Total:</label></td>
        <td class="td-value"><label id="sub_total_value" class="calc-amount-value">0.00</label></td>
      </tr>
      <tr class="totals-tr" style="display:none;">
        <td colspan="2"><hr class="division"/></td>
      </tr>
      <tr class="totals-tr" style="display:none;">
        <td><label class="label-text">Service Charge:</label></td>
        <td class="td-value">
          <input class="cash-input" type="number" id="service_charge_input" disabled="true" min="0" placeholder="P" onkeydown="return Utils.not(event);"/>
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
            <label class="grand-total-value" style="padding-right:5px;"><script type="text/javascript">document.write(Utils.getCurrencySymbol());</script></label><label id="grand_total_value" class="grand-total-value">0.00</label>
          </div>
        </td>
      </tr>
      <tr class="totals-tr">
        <td colspan="2">
          <div class="checkbox-container checkbox-container-bg">
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
          <input class="cash-input" type="number" id="cash_input" disabled="true" min="0" onkeydown="return Utils.not(event);"/>
        </td>
      </tr>
      <tr id='cash_change_tr' class="totals-tr" style='display:none;'>
        <td><label class="label-text">Change:</label></td>
        <td class="td-value"><label id="cash_change" class="cash-change" style="line-height:30px"></label></td>
      </tr>
    </table>
    <br />
    <button id="commit_transaction_button" class="commit-transaction-button" style="margin-top:0px;" disabled="true">COMMIT TRANSACTION</button>
  </div>
  </div>
  <div id="eventdispatcher" style="display:none;" />
</body>
</html>

