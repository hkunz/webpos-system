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
require_once('php/navigation-bar.php');
$username = htmlspecialchars($_SESSION["username"]);
?>
<!DOCTYPE html>
<html lang="en" style='height:100%;'>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Welcome <?php echo $username; ?></title>
  <link type="text/css" rel="stylesheet" href="css/responsive-web-page.css">
  <link type="text/css" rel="stylesheet" href="css/main-styles.css">
  <link type="text/css" rel="stylesheet" href="css/navigation-bar.css">
  <script type="text/javascript" src="js/libs/jquery-3.4.1.min.js"></script>
  <script type="text/javascript" src="js/Utils.js"></script>
  <script type="text/javascript" src="js/main.js"></script>
</head>
<body class="body" style='overflow:hidden'>
  <div class='navbar' style='padding:12px;padding-left:16px;'><label class="header-caption"><script type="text/javascript">document.write(Utils.getStoreHeading());</script></label></div>
  <div class="container-wrapper" style='padding-bottom:0px;margin-bottom:50px;'>
  <div class="container-left" style='width:100%;max-width:500px;margin-bottom:0px;'>
    <h1>Welcome <?php echo $username; ?></h1>
    <div>
      <img class="welcome-image" src="imgs/makoy.jpg" style='min-width:100px;max-width:330px;width:100%;'>
    </div>
  </div>
  </div>
  <div id="eventdispatcher" style="display:none;"></div>
  <div class='navbar' style='height:400px;'></div>
</body>
</html>

