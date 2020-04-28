<?php
// Initialize the session
session_start();
 
// Check if the user is already logged in, if yes then redirect him to account page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
  header("location: account.php");
  exit;
}
 
// Include config file
require_once "config.php";
 
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
                            
                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;                            
                            
                            // Redirect user to account page
                            header("location: account.php");
                        } else{
                            // Display an error message if password is not valid
                            $password_err = "The password you entered was not valid.";
                        }
                    }
                } else{
                    // Display an error message if username doesn't exist
                    $username_err = "No account found with that username.";
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Close connection
    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1">
    <title>Petty</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">  
    <link rel="stylesheet" href="../Front-end/asset/css/base.css">
    <link rel="stylesheet" href="../Front-end/asset/css/main.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
    <link rel="stylesheet" href="../Front-end/asset/css/owl.carousel.min.css">
    <link rel="stylesheet" href="../Front-end/asset/css/owl.theme.default.min.css">
</head>
<body>
    <!--Header-->
    <?php
        include "header.php";
    ?>
    <!--Menu-->
    <div class="catalog">
        <div class="item-catalog home">Trang chủ</div>
        <div class="item-catalog">Giới thiệu</div>
        <div class="item-catalog">Dịch vụ</div>
        <div class="item-catalog">Liên hệ</div>
        <div class="item-catalog">Blog</div>
    </div>
    <!--Content-->
    <div class="content container" style="min-height: 300px; display: flex; padding-top: 15px; padding-bottom: 15px;">
        <div class="mr-4">
            <img src="../Front-end/asset/resource/img/cat2.png">
        </div>
        <div>
            <div class="shadow" style="width: 500px;padding: 20px;">
                <h2 style="text-align: center; font-family: 'My Font Regular'; color: #ef5030;"><i class="fas fa-paw"></i>Đăng nhập<i class="fas fa-paw"></i></h2>
                <p style="text-align: center;">Please fill in your credentials to login.</p>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="form-group petty-form">
                    <div class="item-reg">
                        <label>Username(*)</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-user-alt"></i></span>
                              </div>
                            <input class="form-control" type="text" name="username" placeholder="Enter your username..."  value="<?php echo $username; ?>" required>
                        </div>
                        <span><?php echo $username_err?></span>
                    </div>    
                    <div class="item-reg">
                        <label>Password(*)</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-key"></i></span>
                            </div>
                            <input class="form-control" type="password" name="password" placeholder="Enter your password..."  value="<?php echo $password; ?>" required>
                        </div>
                        <span><?php echo $password_err?></span>
                    </div>
                    <div class="item-reg" style="margin-top: 30px;">
                        <input class="submit" type="submit" value="Login">
                    </div>
                    <p>Don't have an account? <a href="register.php">Sign up now</a>.</p>
                </form>
            </div>
        </div>
    </div>

    <!--Footer-->
    <?php
        include "footer.php";
    ?>
</body>
</html>