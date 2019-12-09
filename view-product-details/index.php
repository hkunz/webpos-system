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
  <link type="text/css" rel="stylesheet" href="../css/awesomplete.css">
  <script type="text/javascript" src="../js/libs/jquery-3.4.1.min.js"></script>
  <script type="text/javascript" src="../js/libs/awesomplete.js"></script>
  <script type="text/javascript" src="../js/AwesompleteInputHandler.js"></script>
  <script type="text/javascript" src="../js/Utils.js"></script>
  <script type="text/javascript" src="js/Controller.js"></script>
  <script type="text/javascript" src="js/ItemSearchInputHandler.js"></script>
  <script type="text/javascript" src="../js/sound-effects.js"></script>
  <script type="text/javascript" src="js/main.js"></script>
</head>

<body class="body">
  <?php echo $navbar_content; ?>
  <div class="container-wrapper">
    <div class="container-left">
      <label class="heading"><script type="text/javascript">document.write(Utils.getStoreHeading());</script></label>                                                              | <label class="heading-sub"><script type="text/javascript">document.write(Utils.getStoreSubHeading());</script></label>
      <hr class="division">
      Product Search:
      <div class="search_item">
        <input type="text" class="awesomplete" id="search_item_input" placeholder="Type here to search product ..." spellcheck="false" />
      </div>
    </div>
  </div>
  <div id="eventdispatcher" style="display:none;" />
</body>
</html>

