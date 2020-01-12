<?php
  require_once("../../php/page-header.php");
?>
<!DOCTYPE html>
<html lang="en">
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Klebby's Store</title>
  <link type="text/css" rel="stylesheet" href="<?php echo $href_root; ?>css/main-styles.css">
  <link type="text/css" rel="stylesheet" href="<?php echo $href_root; ?>css/navigation-bar.css">
  <link type="text/css" rel="stylesheet" href="<?php echo $href_root; ?>css/awesomplete.css">
  <link type="text/css" rel="stylesheet" href="<?php echo $href_root; ?>css/common-table.css">
  <script type="text/javascript" src="<?php echo $href_root; ?>js/libs/jquery-3.4.1.min.js"></script>
  <script type="text/javascript" src="<?php echo $href_root; ?>js/libs/awesomplete.js"></script>
  <script type="text/javascript" src="<?php echo $href_root; ?>js/AwesompleteInputHandler.js"></script>
  <script type="text/javascript" src="<?php echo $href_root; ?>js/Utils.js"></script>
  <script type="text/javascript" src="<?php echo $href_root; ?>service/js/CustomerSearchInputHandler.js"></script>
  <script type="text/javascript" src="<?php echo $href_root; ?>js/sound-effects.js"></script>
  <script type="text/javascript" src="js/CreditorSelectionHandler.js"></script>
  <script type="text/javascript" src="js/AccountsPayableHandler.js"></script>
  <script type="text/javascript" src="js/Controller.js"></script>
  <script type="text/javascript" src="js/main.js"></script>
  <?php require_once("${root}php/favicon.php"); ?>
</head>

<body class="body">
  <?php echo $navbar_content; ?>
  <div class="container-wrapper">
    <div class="container-left">
      <label class="heading"><script type="text/javascript">document.write(Utils.getStoreHeading());</script></label>                                                              | <label class="heading-sub"><script type="text/javascript">document.write(Utils.getStoreSubHeading());</script></label>
      <hr class="division">
      <div style="margin-left:2px;margin-bottom:3px;">
        <label class="drop-shadow" style="font-weight:bold;">ACCOUNTS PAYABLE</label>
      </div>
      <div id="search_creditor">
        <input type="text" class="awesomplete" id="search_creditor_input" placeholder="Type here to search creditor ..." spellcheck="false" />
      </div>
      <div id="table_container" class='common-table-wrapper' style='display:none;height:290px;'>
        <table id='creditor_table' class='common-table' cellspacing="0" cellpadding="0">
        </table>
      </div>
    </div>
  </div>
  <div id="eventdispatcher" style="display:none;" />
</body>
</html>

