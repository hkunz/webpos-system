<?php
session_start();
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
require 'php/navigation-bar.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Welcome To Klebby's</title>
  <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css"> -->
  <link type="text/css" rel="stylesheet" href="css/main-styles.css">
  <link type="text/css" rel="stylesheet" href="css/awesomplete.css">
  <link type="text/css" rel="stylesheet" href="css/navigation-bar.css">
  <script type="text/javascript" src="js/libs/jquery-3.4.1.min.js"></script>
  <script type="text/javascript" src="js/libs/awesomplete.js"></script>
  <script type="text/javascript" src="js/Utils.js"></script>
  <script type="text/javascript" src="js/main.js"></script>
</head>
<body class="body">
  <?php echo $navbar_content; ?>
  <div class="container-wrapper" style="width:600px;">
  <div class="container-left" style="width:100%;">
    <label class="heading"><script type="text/javascript">document.write(Utils.getStoreHeading());</script></label>
     | <label class="heading-sub"><script type="text/javascript">document.write(Utils.getStoreSubHeading());</script></label>
    <hr class="division">
    <h1>Welcome <?php echo htmlspecialchars($_SESSION["username"]); ?></h1>
    <div>
      <img class="welcome-image" src="imgs/makoy.jpg" width="300px">
    </div>
  </div>
  </div>
  <div id="eventdispatcher" style="display:none;" />
</body>
</html>

