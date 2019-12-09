<?php
session_start();
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

$rootpath = $_SESSION["root"];
require_once "php/db.php";
require "php/navigation-bar.php";

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

        if($stmt = mysqli_prepare($link, $sql)){
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
        
        if($stmt = mysqli_prepare($link, $sql)){
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
 
    // Close connection
    mysqli_close($link);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Reset Password</title>
  <script type="text/javascript" src="js/Utils.js"></script>
  <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css"> -->
  <link type="text/css" rel="stylesheet" href="css/main-styles.css">
  <link type="text/css" rel="stylesheet" href="css/navigation-bar.css">
</head>
<body class="body">
  <?php echo $navbar_content; ?>
  <div class="container-wrapper">
    <div class="container-left">
      <label class="heading"><script type="text/javascript">document.write(Utils.getStoreHeading());</script></label> | <label class="heading-sub"><script type="text/javascript">document.write(Utils.getStoreSubHeading());</script></label>
      <hr class="division">
      <label style="font-weight:bold;">RESET PASSWORD</label><label class="standard-label"> FOR USER: </label><label style="color:cyan;font-weight:bold;"><?php echo $username?></label>
      <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"> 
        <div class="form-group <?php echo (!empty($curr_password_err)) ? 'has-error' : ''; ?>" style="padding-bottom:7px;padding-top:20px;">
          <label class="standard-label">Current Password: </label>
          <input type="password" name="curr_password" class="form-control" style="width:200px;padding:6px;">
          <span class="help-block"><?php echo $curr_password_err; ?></span>
        </div>
        <div class="form-group <?php echo (!empty($new_password_err)) ? 'has-error' : ''; ?>" style="padding-bottom:7px;">
          <label class="standard-label">New Password:&nbsp;&nbsp;&nbsp;&nbsp;</label>
          <input type="password" name="new_password" class="form-control" value="<?php echo $new_password; ?>" style="width:200px;padding:6px;">
          <span class="help-block"><?php echo $new_password_err; ?></span>
        </div>
        <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
          <label class="standard-label">Confirm Password: </label>
          <input type="password" name="confirm_password" class="form-control" style="width:200px;padding:6px;">
          <span class="help-block"><?php echo $confirm_password_err; ?></span>
        </div>
        <div class="form-group" style="margin-top:15px">
          <input type="submit" class="button button-green" value="RESET PASSWORD" style="width:200px;">
          <input type="button" class="button button-gray" onclick="window.location.href = 'welcome.php';" value="CANCEL" style="width:148px"/>
        </div>
      </form>
    </div>
  </div>    
</body>
</html>
