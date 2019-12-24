<?php

require_once 'php/db.php';
require 'php/navigation-bar.php';
 
// Define variables and initialize with empty values
$username = $password = $confirm_password = "";
$username_err = $password_err = $confirm_password_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Validate username
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter a username.";
    } else{
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE username = ?";
        
        if($stmt = $sql_con->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Set parameters
            $param_username = trim($_POST["username"]);
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $username_err = "This username is already taken.";
                } else{
                    $username = trim($_POST["username"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
         
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Validate password
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter a password.";     
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "Password is less than 6 characters.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm password.";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }
    
    // Check input errors before inserting in database
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err)){
        
        // Prepare an insert statement
        $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
         
        if($stmt = $sql_con->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_password);
            
            // Set parameters
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
                header("location: login.php");
            } else{
                $username_err = "&nbsp;Username not on whitelist&nbsp;";
            }
        }
         
        // Close statement
        mysqli_stmt_close($stmt);
    } else {
	if ($username_err != "") $username_err = "&nbsp;" . $username_err . "&nbsp;";
	if ($password_err != "") $password_err = "&nbsp;" . $password_err . "&nbsp;";
	if ($confirm_password_err != "") $confirm_password_err = "&nbsp;" . $confirm_password_err . "&nbsp;";
    }
    $sql_con->close();
}
?>
<!DOCTYPE html>
<html lang="en" style='border-top:0;'>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>System Registration</title>
  <script type="text/javascript" src="js/libs/jquery-3.4.1.min.js"></script>
  <script type="text/javascript" src="js/Utils.js"></script>
  <link type="text/css" rel="stylesheet" href="css/responsive-web-page.css">
  <link type="text/css" rel="stylesheet" href="css/main-styles.css">
  <link type="text/css" rel="stylesheet" href="css/navigation-bar.css">
  <style>.form-group {margin-bottom:9px;}
  </style>
</head>
<body class="body">
  <div class='navbar' style='padding:12px;padding-left:16px;'><label class="header-caption"><script type="text/javascript">document.write(Utils.getStoreHeading());</script></label></div>
  <div class="container-wrapper" style='padding-bottom:70px;'>
    <div class="container-left" style='max-width:500px;padding-top:0px;'>
      <h4><u>SYSTEM REGISTRATION</u></h4>
      <form autocomplete="off" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
          <div style='width:100%;'>
            <label class="standard-label">Username:</label>
          </div>
          <div style='width:100%;'>
            <input type="text" name="username" class="form-control" value="<?php echo $username; ?>" style="max-width:200px;padding:6px;" spellcheck="false">
          </div>
          <span class="help-block"><?php echo $username_err; ?></span>
        </div>    
        <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
          <div style='width:100%;'>
            <label class="standard-label">Password:</label>
          </div>
          <div style='width:100%;'>
            <input type="password" name="password" class="form-control" value="<?php echo $password; ?>" style="max-width:200px;padding:6px;" spellcheck="false">
          </div>
          <span class="help-block"><?php echo $password_err; ?></span>
        </div>
        <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
          <div style='width:100%;'>
            <label class="standard-label">Confirm:&nbsp;</label>
          </div>
          <div style='width:100%;'>
            <input type="password" name="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>" style="max-width:200px;padding:6px;" spellcheck="false">
          </div>
          <span class="help-block"><?php echo $confirm_password_err; ?></span>
        </div>
        <br>
        <button type="submit" class="standard-button" style="max-width:200px;">REGISTER</button>
        <button type="reset" class="standard-button standard-button-gray" style="max-width:118px;display:none">RESET</button><br><br>
        <br>
        <label class="standard-label">Already have an account?</label>&nbsp;<a href="login.php">Login Here</a>
      </form>
    </div>
  </div>    
  <div class='navbar' style='height:400px;'></div>
</body>
</html>
