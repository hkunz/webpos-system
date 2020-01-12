<?php
$ready = (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true);
$rootpath = ($ready ? $_SESSION["href_root"] : '#');

$company_list = '
<div class="dropdown-content">
  <a href="' . $rootpath . 'service/">Service</a>
  <a href="' . $rootpath . 'company/statistics/">Statistics</a>
  <a href="' . $rootpath . 'company/transaction-history/">Transaction History</a>
  <a href="' . $_SESSION['href_host'] . 'phpmyadmin/" target="_blank">Database</a>
</div>';

$products_list = '
<div class="dropdown-content">
  <a href="' . $rootpath . 'product/add-new-product/">Add New Product</a>
  <a href="' . $rootpath . 'product/update-product-price/">Update Product Price</a>
  <a href="' . $rootpath . 'product/view-product-details/">View Product Details</a>
</div>';

$accounts_list = '
<div class="dropdown-content">
  <a href="' . $rootpath . 'accounts/view-accounts-receivable/">Accounts Receivable</a>
  <a href="' . $rootpath . 'accounts/view-accounts-payable/">Accounts Payable</a>
</div>';

$user_list = '
<div class="dropdown-content">
  <a id="menu_home" href="' . $rootpath . 'user/welcome.php">Home</a>
  <a id="menu_settings" href="#">Settings</a>
  <a id="menu_resetpass" href="' . $rootpath . 'user/reset-password.php">Reset Password</a>
  <a id="menu_logout" href="' . $rootpath . 'user/logout.php">Logout</a>
</div>';

$user_menu = '
<div class="dropdown">
  <button class="dropbtn menu-btn" style="color:Cyan;">' . htmlspecialchars($_SESSION["username"]) . '
    <i class="fa fa-caret-down"></i>
  </button>' . $user_list . '
</div>';

$navbar_content = '
<div class="row">
  <div class="col-12 navbar">' . 
    ($ready ? $user_menu : '') . '
    <!-- <a class="menu-btn" href="' . $rootpath . 'welcome.php">Home</a> -->
    <div class="dropdown">
      <button class="dropbtn menu-btn">Company
        <i class="fa fa-caret-down"></i>
      </button>' . ($ready ? $company_list : '') . '
    </div>
    <div class="dropdown">
      <button class="dropbtn menu-btn">Product
        <i class="fa fa-caret-down"></i>
      </button>' . ($ready ? $products_list : '') . '
    </div>
    <div class="dropdown">
      <button class="dropbtn menu-btn">Account
        <i class="fa fa-caret-down"></i>
      </button>' . ($ready ? $accounts_list : '') . '
    </div> 
  </div>
</div>';
?>
