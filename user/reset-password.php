<?php
session_start();
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

$root = $_SESSION['root'];
$href_root = $_SESSION['href_root'];

require_once("${root}php/db.php");
require_once("${root}php/navigation-bar.php");

ob_start();
require_once("${root}php/check-detect-mobile-device.php");
$ismobile = ob_get_clean() === '1';
require_once("${root}php/" . ($ismobile ? "mini-navigation-bar" : "navigation-bar") . ".php");


$username = htmlspecialchars($_SESSION["username"]); 

// Define variables and initialize with empty values
$curr_password = "";
$curr_password_err = "";
$new_password = $confirm_password = "";
$new_password_err = $confirm_password_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $curr_password = $_POST["curr_password"];
    if(empty(trim($curr_password))) {
        $curr_password_err = "Enter current password.";
    } else {
        $sql = "SELECT id, username, password FROM users WHERE username = ?";

        if($stmt = $sql_con->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);

            // Set parameters
            $param_username = $username;

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Store result
                mysqli_stmt_store_result($stmt);

                // Check if username exists, if yes then verify password
                if(mysqli_stmt_num_rows($stmt) == 1){
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);
                    if(mysqli_stmt_fetch($stmt)){
                        if(!password_verify($curr_password, $hashed_password)){
                            $curr_password_err = "Current password incorrect";
			}
                    } else {
                        echo "Opps! Something went wrong.. Please try again later..";
                    }
                } else {
                    echo "No account found with that username.";
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
        } else {
            echo "Ohhh snap!";
        }
    }
 
    // Validate new password
    if(empty(trim($_POST["new_password"]))){
        $new_password_err = "Please enter new password.";     
    } elseif(strlen(trim($_POST["new_password"])) < 6){
        $new_password_err = "Password must have at least 6 characters.";
    } else{
        $new_password = trim($_POST["new_password"]);
    }
    
    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm password.";
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($new_password_err) && ($new_password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }
        
    // Check input errors before updating the database
    if(empty($curr_password_err) && empty($new_password_err) && empty($confirm_password_err)){
        // Prepare an update statement
        $sql = "UPDATE users SET password = ? WHERE id = ?";
        
        if($stmt = $sql_con->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "si", $param_password, $param_id);
            
            // Set parameters
            $param_password = password_hash($new_password, PASSWORD_DEFAULT);
            $param_id = $_SESSION["id"];
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Password updated successfully. Destroy the session, and redirect to login page
                session_destroy();
                header("location: login.php");
                exit();
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        
        // Close statement
        mysqli_stmt_close($stmt);
    }

    if ($curr_password_err) $curr_password_err = "&nbsp;" . $curr_password_err . "&nbsp;";
    if ($new_password_err) $new_password_err = "&nbsp;" . $new_password_err . "&nbsp;";
    if ($confirm_password_err) $confirm_password_err = "&nbsp;" . $confirm_password_err . "&nbsp;";

    $sql_con->close();
}
?>
<!DOCTYPE html>
<html lang="en" style='height:100%;overflow:hidden;'>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reset Password</title>
  <script type="text/javascript" src="<?php echo $href_root; ?>js/libs/jquery-3.4.1.min.js"></script>
  <script type="text/javascript" src="<?php echo $href_root; ?>js/Utils.js"></script>
  <link type="text/css" rel="stylesheet" href="<?php echo $href_root; ?>css/main-styles.css">
  <link type="text/css" rel="stylesheet" href="<?php echo $href_root; ?>css/navigation-bar.css">
  <?php require_once("${root}php/favicon.php"); ?>
</head>
<body class="body" style='overflow:none;'>
  <?php echo $navbar_content; ?>
  <div class="container-wrapper">
    <div class="container-left" style='width:100%;max-width:500px;overflow:hidden;overflow-y:auto;padding-bottom:30px;margin-bottom:50px;'>
      <div class='store-heading' <?php echo $ismobile ? "style='display:none;'" : '' ?>>
        <label class="heading"><script type="text/javascript">document.write(Utils.getStoreHeading());</script></label> | <label class="heading-sub"><script type="text/javascript">document.write(Utils.getStoreSubHeading());</script></label>
        <hr class="division">
      </div>
      <div style='width:100%;'>
        <label style="font-weight:bold;">RESET PASSWORD&nbsp;</label><?php if ($ismobile) echo "<hr class='division'>"; ?><label class='standard-label'>FOR USER: </label><label style="color:cyan;font-weight:bold;"><?php echo $username?></label>
      </div>
      <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"> 
        <div class="form-group <?php echo (!empty($curr_password_err)) ? 'has-error' : ''; ?>" style="padding-bottom:7px;padding-top:20px;">
          <label class="standard-label">Current Password: </label>
          <div style='width:100%;'>
            <input type="password" name="curr_password" class="form-control" style="max-width:200px;padding:6px;">
          </div>
          <span class="help-block"><?php echo $curr_password_err; ?></span>
        </div>
        <div class="form-group <?php echo (!empty($new_password_err)) ? 'has-error' : ''; ?>" style="padding-bottom:7px;">
          <label class="standard-label">New Password:&nbsp;&nbsp;&nbsp;&nbsp;</label>
          <div style='width:100%;'>
            <input type="password" name="new_password" class="form-control" value="<?php echo $new_password; ?>" style="max-width:200px;padding:6px;">
          </div>
          <span class="help-block"><?php echo $new_password_err; ?></span>
        </div>
        <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
          <label class="standard-label">Confirm Password: </label>
          <div style='width:100%;'>
            <input type="password" name="confirm_password" class="form-control" style="max-width:200px;padding:6px;">
          </div>
          <span class="help-block"><?php echo $confirm_password_err; ?></span>
        </div>
        <div class="form-group" style="margin-top:15px">
          <button type="submit" class="standard-button" style="max-width:200px;">RESET PASSWORD</button>
          <button type="button" class="standard-button standard-button-gray" onclick="window.location.href = 'welcome.php';" style="width:149px;display:none;">CANCEL</button>
        </div>
      </form>
    </div>
  </div>
  <div class='navbar' style='height:400px;'></div>
</body>
</html>
