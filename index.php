<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
  <title>Klebby's Store</title>
  <link type="text/css" rel="stylesheet" href="css/main-styles.css">
  <link type="text/css" rel="stylesheet" href="css/awesomplete.css">
  <script type="text/javascript" src="js/libs/jquery-3.4.1.min.js"></script>
  <script type="text/javascript" src="js/libs/awesomplete.js"></script>
  <script type="text/javascript" src="js/Utils.js"></script>
  <script type="text/javascript" src="js/main.js"></script>
</head>

<body class="body">
  <div class="container-wrapper" style="width:90%">
  <div class="container-left" style="width:100%;">
    <label class="heading"><script type="text/javascript">document.write(Utils.getStoreHeading());</script></label>
     | <label class="heading-sub"><script type="text/javascript">document.write(Utils.getStoreSubHeading());</script></label>
    <br/><br/>
    <table cellpadding="8">
      <tr>
        <td>
          <form action="/klebbys/service/" method="get">
            <button id="button_service" class="home-button" type="submit"><i class="icon-service"></i>Service</button>
          </form>
        </td>
        <td>
          <form action="/klebbys/statistics/" method="get">
            <button id="button_stats" class="home-button"><i class="icon-service"></i>Statistic</button>
          </form>
        </td>
        <td>
          <form action="/klebbys/items-create/" method="get">
            <button id="button_newitem" class="home-button"><i class="icon-service"></i>New Item</button>
          </form>
        </td>
        <td>
          <form action="/phpmyadmin" method="get" target="_blank">
            <button id="button_database" class="home-button"><i class="icon-service"></i>Database</button>
          </form>
        </td>
      </tr>
    </table>
  </div>
  </div>
  <div id="eventdispatcher" style="display:none;" />
</body>
</html>

