<?php
// SOURCE: https://www.tutorialrepublic.com/php-tutorial/php-mysql-login-system.php
// Initialize the session
session_start();
 
// Check if the user is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: welcome.php");
    exit;
}
 
// Include config file
require_once "php/db.php";
 
// Define variables and initialize with empty values
$username = $password = "";
$username_err = $password_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Check if username is empty
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter username.";
    } else{
        $username = trim($_POST["username"]);
    }
    
    // Check if password is empty
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter your password.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate credentials
    if(empty($username_err) && empty($password_err)){
        // Prepare a select statement
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
                        if(password_verify($password, $hashed_password)){
                            // Password is correct, so start a new session
                            session_start();
                            
                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;                            
                            $_SESSION["root"] = "//localhost/klebbys/";
                            
                            // Redirect user to welcome page
                            header("location: welcome.php");
                        } else{
                            // Display an error message if password is not valid
                            $password_err = "Invalid password entered.";
                        }
                    }
                } else{
                    // Display an error message if username doesn't exist
                    $username_err = "No account found with that username.";
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        
        // Close statement
        mysqli_stmt_close($stmt);
    }

    if ($username_err) $username_err = "&nbsp;" . $username_err . "&nbsp;";
    if ($password_err) $password_err = "&nbsp;" . $password_err . "&nbsp;";
    
    // Close connection
    mysqli_close($link);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Klebby's Login</title>
  <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css"> -->
  <script type="text/javascript" src="js/Utils.js"></script>
  <link type="text/css" rel="stylesheet" href="css/main-styles.css">
  <style>.form-group {margin-bottom:9px;}
  </style>
</head>
<body class="body">
  <div class="container-wrapper">
    <div class="container-left" style="width:700px;">
      <label class="heading"><script type="text/javascript">document.write(Utils.getStoreHeading());</script></label> | <label class="heading-sub"><script type="text/javascript">document.write(Utils.getStoreSubHeading());</script></label>
      <hr class="division">
      <h4><u>LOGIN</u></h4>
      <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" autocomplete="off">
         <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
           <label class="standard-label">Username: </label>
           <input type="text" name="username" class="form-control" value="<?php echo $username; ?>" spellcheck="false" style="width:200px;padding:6px;">
           <span class="help-block"><?php echo $username_err; ?></span>
         </div>    
         <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
           <label class="standard-label">Password: </label>
           <input type="password" name="password" class="form-control" style="padding:6px;width:200px;">
           <span class="help-block"><?php echo $password_err; ?></span>
         </div>
         <div class="form-group" style="margin-top:15px;">
           <input type="submit" class="button button-green" value="LOGIN" style="width:170px;">
         </div>
         <label class="standard-label">Don't have an account?&nbsp;</label><a href="register.php">Sign Up</a>
       </form>
    </div>
  </div>    
</body>
</html>
