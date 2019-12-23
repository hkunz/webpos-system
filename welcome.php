<?php
session_start();
/*
foreach ($_SERVER as $key => $value) {
	echo "$key : $value<br>";
}
exit;
//*/
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
require 'php/navigation-bar.php';
?>
<!DOCTYPE html>
<html lang="en">
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Welcome To Klebby's</title>
  <link type="text/css" rel="stylesheet" href="css/responsive-web-page.css">
  <link type="text/css" rel="stylesheet" href="css/main-styles.css">
  <link type="text/css" rel="stylesheet" href="css/navigation-bar.css">
  <link type="text/css" rel="stylesheet" href="<?php require_once("php/detect-mobile-device.php"); ?>">
  <script type="text/javascript" src="js/libs/jquery-3.4.1.min.js"></script>
  <script type="text/javascript" src="js/Utils.js"></script>
  <script type="text/javascript" src="js/main.js"></script>
</head>
<body class="body">
  <?php echo $navbar_content; ?>
  <div class="container-wrapper">
  <div class="container-left" style='width:100%;max-width:500px;'>
    <div class='store-heading'>
      <label class="heading"><script type="text/javascript">document.write(Utils.getStoreHeading());</script></label>
     | <label class="heading-sub"><script type="text/javascript">document.write(Utils.getStoreSubHeading());</script></label>
      <hr class="division">
    </div>
    <h1>Welcome <?php echo htmlspecialchars($_SESSION["username"]); ?></h1>
    <div>
      <img class="welcome-image" src="imgs/makoy.jpg" style='min-width:100px;max-width:300px;width:100%;'>
    </div>
  </div>
  </div>
  <div id="eventdispatcher" style="display:none;" />
</body>
</html>

