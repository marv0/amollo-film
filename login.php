<?php
// Initialize the session
session_start();
 
// Check if the user is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: welcome.php");
    exit;
}
 
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$username = $password = "";
$username_err = $password_err = $login_err = "";
 
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
                            // session_start();
                            
                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;                            
                            
                            // Redirect user to welcome page
                            header("location: payment.php");
                        } else{
                            // Password is not valid, display a generic error message
                            $login_err = "Invalid username or password.";
                        }
                    }
                } else{
                    // Username doesn't exist, display a generic error message
                    $login_err = "Invalid username or password.";
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
<html>
<head>
	<title>Amollo Film - Login</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

		<link rel="stylesheet" href="css/bootstrap.min.css">
		<link rel="stylesheet" href="style.css">
		<link rel="stylesheet" href="../../fonts.googleapis.com/css222a2.css?family=Fira+Sans+Condensed:wght@300;400;500;700&amp;family=Lato:wght@300;400;700&amp;display=swap">
		
		<link rel="stylesheet" href="icons/fontawesome/css/all.min.css"><!-- FontAwesome Icons -->
		<link rel="stylesheet" href="icons/dashicons/css/dashicons-min.css"><!-- DashIcons For Star Ratings -->
	<style>
		body {
			background-color: #141414;
			color: #fff;
			font-family: "Helvetica Neue",Helvetica,Arial,sans-serif;
			font-size: 14px;
			margin: 0;
			padding: 0;
		}

		.container {
			margin: 0 auto;
			max-width: 460px;
			padding: 0 20px;
			text-align: center;
			margin-top: 50px;
		}

		.logo {
			margin-bottom: 30px;
			text-align: center;
		}

		.logo img {
			max-width: 100%;
			height: auto;
			width: auto\9;
		}

		form {
			background-color: rgba(0, 0, 0, 0.75);
			border-radius: 4px;
			padding: 15px 2px;
			box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.4);
		}

		form input[type="text"],
		form input[type="password"] {
			background-color: #333;
			border: none;
			padding: 8px 20px;
			margin: 4px 0;
			width: 100%;
			color: #fff;
			border-radius: 4px;
			box-sizing: border-box;
		}

		form input[type="submit"] {
			background-color: #e50914;
			border: none;
			color: #fff;
			padding: 8px 0;
			margin-top: 10px;
			width: 100%;
			border-radius: 4px;
			cursor: pointer;
			font-size: 14px;
			font-weight: bold;
		}

		form input[type="submit"]:hover {
			background-color: #ff0a16;
		}

		form a {
			color: #e50914;
			display: inline-block;
			margin-top: 6px;
			text-decoration: none;
			font-size: 14px;
			font-weight: normal;
		}

		form a:hover {
			color: #ff0a16;
			text-decoration: underline;
		}


	</style>
</head>
<body>
    	<header id="masthead-pro" class="">
			<div class="header-container">
			    
			    <h1><a href="index.php"><img src="images/logo.png" alt="Logo"></a></h1>
			    
				
				<div id="mobile-bars-icon-pro" class="noselect"><i class="fas fa-bars"></i></div>
	
				<div class="clearfix"></div>
			</div><!-- close .header-container -->
			
			<nav id="mobile-navigation-pro">
				<button class="btn btn-mobile-pro btn-header-pro noselect" onclick="window.location.href = 'index.php';">Home</button>

			</nav>

		</header>
	<div class="container">


		<?php 
        if(!empty($login_err)){
            echo '<div class="alert alert-danger">' . $login_err . '</div>';
        }        
        ?>

		<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
			<h4>Sign In</h4>
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
                <span class="invalid-feedback"><?php echo $username_err; ?></span>
            </div>    
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>">
                <span class="invalid-feedback"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Login">
            </div>
            <p>Create an account <a href="register.php">here.</a></p>
		</form>
		<footer id="footer-pro">
			<div class="container">
				<div class="row">
					<div class="col-md">
						<div class="copyright-text-pro">Powered by <a href="https://www.digitalducks.co.ke">Digital Ducks</a>.</div>
					</div><!-- close .col -->
				</div><!-- close .row -->
			</div><!-- close .container -->
		</footer>
	</div>
</body>
</html>
