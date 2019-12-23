<?php
$ROOT_DIRECTORY = "klebbys/";

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
require "php/navigation-bar.php";
 
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
                        if(password_verify($password, $hashed_password)){
                            // Password is correct, so start a new session
                            session_start();

                            $server_addr = $_SERVER['SERVER_ADDR'];
                            $server_name = $_SERVER['SERVER_NAME'];
                            $server_port = $_SERVER['SERVER_PORT'];

                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;
                            $_SESSION["currency"] = '₱';
                            $_SESSION["href_host"] = "//$server_name/";
                            $_SESSION["href_root"] = "//$server_name/" . $ROOT_DIRECTORY;
                            $_SESSION["root"] = $_SERVER['DOCUMENT_ROOT'] . "/" . $ROOT_DIRECTORY;
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
    $sql_con->close();
}
?>
<!DOCTYPE html>
<html lang="en" style='height:100%;'>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>System Login</title>
  <script type="text/javascript" src="js/libs/jquery-3.4.1.min.js"></script>
  <script type="text/javascript" src="js/Utils.js"></script>
  <link type="text/css" rel="stylesheet" href="css/responsive-web-page.css">
  <link type="text/css" rel="stylesheet" href="css/main-styles.css">
  <link type="text/css" rel="stylesheet" href="css/navigation-bar.css">
  <style>.form-group {margin-bottom:9px;}
  </style>
</head>
<body class="body" style='overflow:hidden;'>
  <div class='navbar' style='padding:12px;padding-left:16px;'><label class="header-caption"><script type="text/javascript">document.write(Utils.getStoreHeading());</script></label></div>
  <div class="container-wrapper" style='height:100%;padding-bottom:90px;'>
    <div class="container-left" style="max-width:500px;height:100%;padding-top:0px;">
      <h4><u>SYSTEM LOGIN</u></h4>
      <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" autocomplete="off">
         <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
           <div style='width:100%;'>
             <label class="standard-label">Username: </label>
           </div>
           <div style='width:100%;'>
             <input type="text" name="username" class="form-control" value="<?php echo $username; ?>" spellcheck="false" style="max-width:200px;padding:6px;">
           </div>
           <span class="help-block"><?php echo $username_err; ?></span>
         </div>    
         <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
           <div style='width:100%;'>
             <label class="standard-label">Password: </label>
           </div>
           <div style='style:100%;'>
             <input type="password" name="password" class="form-control" style="padding:6px;max-width:200px;">
           </div>
           <span class="help-block"><?php echo $password_err; ?></span>
         </div>
         <div class="form-group" style="margin-top:20px;padding-bottom:10px;">
           <button type="submit" class="standard-button" style='max-width:200px;'>LOGIN</button>
         </div>
         <br><br><br>
         <label class="standard-label">Don't have an account?&nbsp;</label><a href="register.php">Sign Up</a>
       </form>
    </div>
  </div>
  <div class='navbar' style='height:400px;'></div>
</body>
</html>
