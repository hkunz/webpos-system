<?php
$rootpath = $_SESSION["root"];
$navbar_content = '
<div class="navbar">
    <a class="menu-btn" href="' . $rootpath . 'welcome.php">Home</a>
    <div class="dropdown">
      <button class="dropbtn menu-btn">Company
        <i class="fa fa-caret-down"></i>
      </button>
      <div class="dropdown-content">
        <a href="' . $rootpath . 'service/">Service</a>
        <a href="' . $rootpath . 'statistics/">Statistics</a>
        <a href="//localhost/phpmyadmin/" target="_blank">Database</a>
      </div>
    </div>
    <div class="dropdown">
      <button class="dropbtn menu-btn">Products
        <i class="fa fa-caret-down"></i>
      </button>
      <div class="dropdown-content">
        <a href="' . $rootpath . 'add-new-product/">Add New Product</a>
        <a href="' . $rootpath . 'update-product-price/">Update Product Price</a>
        <a href="' . $rootpath . 'view-product-details/">View Product Details</a>
      </div>
    </div>
    <div class="dropdown">
      <button class="dropbtn menu-btn" style="color:Cyan;">' . htmlspecialchars($_SESSION["username"]) . '
        <i class="fa fa-caret-down"></i>
      </button>
      <div class="dropdown-content">
        <a id="menu_settings" href="#">Settings</a>
        <a id="menu_resetpass" href="' . $rootpath . 'reset-password.php">Reset Password</a>
        <a id="menu_logout" href="' . $rootpath . 'logout.php">Logout</a>
      </div>
    </div>
  </div>
  ';
?>
