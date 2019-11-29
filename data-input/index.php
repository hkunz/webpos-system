<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
  <title>Klebby's Statistics</title>
  <link type="text/css" rel="stylesheet" href="../css/main-styles.css">
  <link type="text/css" rel="stylesheet" href="../css/awesomplete.css">
  <script type="text/javascript" src="../js/libs/jquery-3.4.1.min.js"></script>
  <script type="text/javascript" src="../js/libs/awesomplete.js"></script>
  <script type="text/javascript" src="../js/sound-effects.js"></script>
</head>

<body class="body">
  <div class="container-wrapper">
  <div class="container-left">
    <span class="heading">
       <u><b style="color:#FFFF00;">Klebby's Supplies &amp; Computer Services</b></u></span><span> | </span><span style="color:#CCCCCC;">KUNZ Inc</span>
    <form>
    <br/>
      Customer:&nbsp;&nbsp;&nbsp;
      <input type="text" id="customer" style="margin-bottom:7px;" maxlength="30" placeholder="Customer Name ..." spellcheck="false"/>
      <br>
  </div>
  </div>
  <div class="container-right">
    <button id="commit_transaction_button" class="commit-transaction-button" disabled="true">COMMIT TRANSACTION</button>
  </div>
  </div>
  <div id="eventdispatcher" style="display:none;" />
</body>
</html>

