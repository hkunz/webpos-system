<?php

require_once "php/db.php";
 
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
        
        if($stmt = mysqli_prepare($link, $sql)){
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
        $password_err = "Password must have at least 6 characters.";
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
         
        if($stmt = mysqli_prepare($link, $sql)){
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
                echo "Something went wrong. Please try again later.";
            }
        }
         
        // Close statement
        mysqli_stmt_close($stmt);
    } else {
	if ($username_err != "") $username_err = "&nbsp;" . $username_err . "&nbsp;";
	if ($password_err != "") $password_err = "&nbsp;" . $password_err . "&nbsp;";
	if ($confirm_password_err != "") $confirm_password_err = "&nbsp;" . $confirm_password_err . "&nbsp;";
    }
    
    // Close connection
    mysqli_close($link);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Klebby's Sign Up</title>
  <script type="text/javascript" src="js/Utils.js"></script>
  <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css"> -->
  <link type="text/css" rel="stylesheet" href="css/main-styles.css">
  <style>.form-group {margin-bottom:9px;}
  </style>
</head>
<body class="body">
  <div class="container-wrapper">
    <div class="container-left">
      <label class="heading"><script type="text/javascript">document.write(Utils.getStoreHeading());</script></label> | <label class="heading-sub"><script type="text/javascript">document.write(Utils.getStoreSubHeading());</script></label>
      <hr class="division">
      <h4><u>REGISTRATION FORM</u></h4>
      <form autocomplete="off" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
          <label class="standard-label">Username:</label>
          <input type="text" name="username" class="form-control" value="<?php echo $username; ?>" style="width:200px;padding:6px;" spellcheck="false">
          <span class="help-block"><?php echo $username_err; ?></span>
        </div>    
        <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
          <label class="standard-label">Password:</label>
          <input type="password" name="password" class="form-control" value="<?php echo $password; ?>" style="width:200px;padding:6px;" spellcheck="false">
          <span class="help-block"><?php echo $password_err; ?></span>
        </div>
        <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
          <label class="standard-label">Confirm:&nbsp;</label>
          <input type="password" name="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>" style="width:200px;padding:6px;" spellcheck="false">
          <span class="help-block"><?php echo $confirm_password_err; ?></span>
        </div>
        <div class="form-group" style="margin-top:15px;margin-bottom:15px;">
          <input type="submit" class="button button-green" value="REGISTER">
          <input type="reset" class="button button-gray" value="RESET">
        </div>
        <label class="standard-label">Already have an account?</label>&nbsp;<a href="login.php">Login Here</a>
      </form>
    </div>
  </div>    
</body>
</html>
