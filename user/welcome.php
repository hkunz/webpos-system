<?php
/*
foreach ($_SERVER as $key => $value) {
	echo "$key : $value<br>";
}
exit;
//*/
require_once("../php/page-header.php");
?>
<!DOCTYPE html>
<html lang="en" style='border-top:0;'>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Welcome <?php echo $username; ?></title>
  <link type="text/css" rel="stylesheet" href="<?php echo $href_root; ?>/css/responsive-web-page.css">
  <link type="text/css" rel="stylesheet" href="<?php echo $href_root; ?>css/main-styles.css">
  <link type="text/css" rel="stylesheet" href="<?php echo $href_root; ?>css/navigation-bar.css">
  <script type="text/javascript" src="<?php echo $href_root; ?>js/libs/jquery-3.4.1.min.js"></script>
  <script type="text/javascript" src="<?php echo $href_root; ?>js/Utils.js"></script>
  <script type="text/javascript" src="<?php echo $href_root; ?>js/main.js"></script>
  <?php if ($ismobile) echo '<link type="text/css" rel="stylesheet" href="css/login.css">'; ?>
</head>
<body class="body" style='overflow:hidden'>
  <?php echo $ismobile ? "<div class='navbar' style='padding:12px;padding-left:16px;'><label class='header-caption'><script type='text/javascript'>document.write(Utils.getStoreHeading());</script></label></div>
  " : $navbar_content ?>
  <div class="container-wrapper" style='margin-bottom:30px;'>
  <div class="container-left" style='max-height:<?php echo ($ismobile) ? "100%" : "450px" ?>px;max-width:<?php echo ($ismobile) ? "100%" : "500px" ?>;margin-bottom:15px;'>
    <div class='store-heading' <?php echo $ismobile ? "style='display:none'" : ""; ?>>
      <label class="heading"><script type="text/javascript">document.write(Utils.getStoreHeading());</script></label>                                                              | <label class="heading-sub"><script type="text/javascript">document.write(Utils.getStoreSubHeading());</script></label>
      <hr class="division">
    </div>
    <h1>Welcome <?php echo $username; ?></h1>
    <div>
      <img class="welcome-image" src="<?php echo $href_root; ?>/assets/imgs/makoy.jpg" style='min-width:100px;max-width:330px;width:100%;'>
    </div>
  </div>
  </div>
  <div id="eventdispatcher" style="display:none;"></div>
  <div class='navbar' style='width:100%;position:fixed:bottom:0;height:400px;'></div>
</body>
</html>

