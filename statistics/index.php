<?php
  require_once("../php/page-header.php");

  function br($indent) {
    $b = '<br>';
    if ($indent) $b .= '&nbsp;&nbsp;&nbsp;';
    return $b;
  }

  function create_stats_group($ismobile, $title, $title_widget, $totalId, $prepaidId, $productsId, $servicesId, $prepaid2Id, $products2Id, $services2Id) {
    echo "<div class='common-table-wrapper' style='margin-top:0px;margin-bottom:10px;'>
        <div class='stats-heading'>
          <table style='width:100%;'>
            <tr>
              <td style='white-space:nowrap;'>
                <label id='${totalId}_label' class='standard-label'>$title</label>" . ($ismobile ? br(1) : '') . "
                <label id='$totalId' class='drop-shadow stat-total'></label>
              </td>
              <td>
                <div style='float:right;'>$title_widget</div>
              </td>
            </tr>
          </table>
        </div>
        <div class='stats-content'>
          <label class='standard-label'>Prepaid-Load:</label>" . ($ismobile ? br(1) : '') . "
          <label id='$prepaidId' class='drop-shadow'></label> (<label id='$prepaid2Id' class='drop-shadow profit'></label>)<br/>
          <label class='standard-label'>Products:</label>" . ($ismobile ? br(1) : '') . "
          <label id='$productsId' class='drop-shadow'></label> (<label id='$products2Id' class='drop-shadow profit'></label>)<br/>
          <label class='standard-label'>Services:</label>" . ($ismobile ? br(1) : '') . "
          <label id='$servicesId' class='drop-shadow'></label>
        </div>
      </div>";
  }
?>
<!DOCTYPE html>
<html lang="en">
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Statistics</title>
  <link type="text/css" rel="stylesheet" href="<?php echo $href_root; ?>css/main-styles.css">
  <link type="text/css" rel="stylesheet" href="<?php echo $href_root; ?>css/navigation-bar.css">
  <link type="text/css" rel="stylesheet" href="<?php echo $href_root; ?>css/awesomplete.css">
  <link type="text/css" rel="stylesheet" href="<?php echo $href_root; ?>css/responsive-web-page.css">
  <link type="text/css" rel="stylesheet" href="<?php echo $href_root; ?>css/common-table.css">
  <link type="text/css" rel="stylesheet" href="<?php echo ($ismobile ? $href_root . 'css/main-styles-mobile.css' : '') ?>">
  <link type="text/css" rel="stylesheet" href="css/stats-styles.css">
  <script type="text/javascript" src="<?php echo $href_root; ?>js/libs/jquery-3.4.1.min.js"></script>
  <script type="text/javascript" src="<?php echo $href_root; ?>js/libs/awesomplete.js"></script>
  <script type="text/javascript" src="<?php echo $href_root; ?>js/Utils.js"></script>
  <script type="text/javascript" src="<?php echo $href_root; ?>js/sound-effects.js"></script>
  <script type="text/javascript" src="js/Controller.js"></script>
  <script type="text/javascript" src="js/main.js"></script>
  <?php require_once("${root}php/favicon.php"); ?>
</head>

<body class="body">
  <?php echo $navbar_content; ?>
  <div class="container-wrapper">
  <div class="container-left">
    <div class='store-heading'>
      <label class="heading"><script type="text/javascript">document.write(Utils.getStoreHeading());</script></label> | <label class="heading-sub"><script type="text/javascript">document.write(Utils.getStoreSubHeading());</script></label>
      <hr class="division">
      <br/>
    </div>
    <div style='width:100%;max-width:700px;'>
<?php
$datetime = "<input type='date' id='date_picker' step='1' min='2020-01-01' max='" . date("Y/m/d") . "' style='max-width:190px;height:33px;' required='required'>";
create_stats_group($ismobile, 'Today\'s Revenue:', $datetime, 'revenue_today', 'revenue_today_prepaid', 'revenue_today_products', 'revenue_today_services', 'profit_today_prepaid', 'profit_today_products');
create_stats_group($ismobile, 'Total Revenue To Date:', '', 'revenue_total', 'revenue_total_prepaid', 'revenue_total_products', 'revenue_total_services', 'profit_total_prepaid', 'profit_total_products', 'profit_total_services');
create_stats_group($ismobile, 'Total Revenue For Current Month:', '', 'revenue_total_curr_month', 'revenue_total_curr_month_prepaid', 'revenue_total_curr_month_products', 'revenue_total_curr_month_services', 'profit_curr_month_prepaid', 'profit_curr_month_products', 'profit_curr_month_services');
create_stats_group($ismobile, 'Total Revenue For Previous Month:', '', 'revenue_total_prev_month', 'revenue_total_prev_month_prepaid', 'revenue_total_prev_month_products', 'revenue_total_prev_month_services', 'profit_prev_month_prepaid', 'profit_prev_month_products', 'profit_prev_month_services');
create_stats_group($ismobile, 'Total Revenue For One Before Previous Month:', '', 'revenue_total_prev2_month', 'revenue_total_prev2_month_prepaid', 'revenue_total_prev2_month_products', 'revenue_total_prev2_month_services', 'profit_prev2_month_prepaid', 'profit_prev2_month_products', 'profit_prev2_month_services');
?>
    </div>
  </div>
  </div>
  </div>
  <div id="eventdispatcher" style="display:none;" />
</body>
</html>

