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
                            session_start();
                            
                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;                            
                            
                            // Redirect user to welcome page
                            header("location: welcome.php");
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

<!doctype html>
<html lang="en">
	
<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

		<link rel="stylesheet" href="css/bootstrap.min.css">
		<link rel="stylesheet" href="style.css">
		<link rel="stylesheet" href="../../fonts.googleapis.com/css222a2.css?family=Fira+Sans+Condensed:wght@300;400;500;700&amp;family=Lato:wght@300;400;700&amp;display=swap">
		
		<link rel="stylesheet" href="icons/fontawesome/css/all.min.css"><!-- FontAwesome Icons -->
		<link rel="stylesheet" href="icons/dashicons/css/dashicons-min.css"><!-- DashIcons For Star Ratings -->
		
		<title>Amollo Film (2022)</title>
	</head>
	<body>
		<header id="masthead-pro" class="">
			<div class="header-container">
			    
			    <h1><a href="index.php"><img src="images/logo.png" alt="Logo"></a></h1>
			    
			    <nav id="site-navigation-pro">
					<ul class="sf-menu">
						<li class="normal-item-pro">
							<a href="login.php"><i class="fas fa-desktop"></i>Login</a>
						</li>
						<li class="normal-item-pro">
							<a href="register.php"><i class="fas fa-desktop"></i>Sign Up</a>
						</li>
					</ul>
				</nav>
				
				<div id="mobile-bars-icon-pro" class="noselect"><i class="fas fa-bars"></i></div>
	
				<div class="clearfix"></div>
			</div><!-- close .header-container -->
			
			<nav id="mobile-navigation-pro">
				<button class="btn btn-mobile-pro btn-header-pro noselect" onclick="window.location.href = 'login.php';">Log In</button>
				<button class="btn btn-mobile-pro btn-header-pro noselect" onclick="window.location.href = 'register.php';">Sign Up</button>
			</nav>

		</header>
		
		<div id="video-page-title-pro" style="background-image:url('images/demo/landing-banner.jpg');">

			
			<div style="display:none;">
				<video id="Video-Vayvo-Single" width="960" height="540" data-youtube-id="jirBNu6mcWM"></video>
			</div>
			
			<div id="video-page-title-gradient-base"></div>
		</div><!-- close #video-page-title-pro -->
		
		<div id="content-pro">
			
  	 		<div class="container custom-gutters-pro">
				
				
				<div id="video-post-container">
					<h1 class="video-post-heading-title">Amollo</h1>
					<div class="clearfix"></div>
					
					<ul id="video-post-meta-list">
					    <li id="video-post-meta-rating"><span><strong>PG-13</strong></span></li>
						<li><a href="#!"><strong>Drama</strong></a></li>
						<li id="video-post-meta-year"><strong>2022</strong></li>
						
					</ul>
					<div class="clearfix"></div>

					<div id="video-post-buttons-container">
					    <a href="#VideoLightbox-1"class="afterglow" id="video-post-play-text-btn"><i class="fas fa-play"></i>Watch Trailer</a>
						<a href="login.php"class="afterglow" id="video-post-play-text-btn"><i class="fas fa-play"></i>Play Movie</a>
						
						<div style="display:none;">
							<video id="Video-Single" width="960" height="540" data-youtube-id="jirBNu6mcWM"></video>
						</div>
					
					<div class="clearfix"></div>
					</div><!-- close #video-post-buttons-container -->
					
										<ul id="video-post-meta-list">
						<li id="video-post-meta-year"><strong>Enjoy this movie with 12 hours of access by making a payment of KES.100,  $3.99, or £2.99.</strong></li>
						
					</ul>
					<div class="clearfix"></div>
	
					<div id="vayvo-video-post-content">
						<p>Amollo is a graduate of law who wants to change  the narrative of her culture and tradition which she believes infringes on her rights. 
												This is seen in her reaction toward her parents demands to accept their choice of the groom.
												Will Amollo succeed and change the narrative?</p>


					</div><!-- #vayvo-video-post-content -->

											  <video id="VideoLightbox-1"  poster="video/#.jpg" width="960" height="540">
												  <source src="video/amollo-trailer.mp4" type="video/mp4">
											  </video>
											  
					

				</div><!-- close #video-post-container -->

				
				
				<div class="clearfix"></div>
			</div><!-- close .container -->
			
			
			
		</div><!-- close #content-pro -->

	 <footer id="footer-pro">
			<div class="container">
				<div class="row">
					<div class="col-md">
						<div class="copyright-text-pro">Powered by <a href="https://www.digitalducks.co.ke">Digital Ducks</a>.</div>
					</div><!-- close .col -->
				</div><!-- close .row -->
			</div><!-- close .container -->
		</footer>
		
		<a href="#0" id="pro-scroll-top"><i class="fas fa-chevron-up"></i></a>
		
		

		<!-- Required Framework JavaScript -->
		<script src="js/libs/jquery-3.5.1.min.js"></script><!-- jQuery -->
		<script src="js/libs/popper.min.js" defer></script><!-- Bootstrap Popper/Extras JS -->
		<script src="js/libs/bootstrap.min.js" defer></script><!-- Bootstrap Main JS -->
		<!-- All JavaScript in Footer -->
		
		<!-- Additional Plugins and JavaScript -->
		<script src="js/navigation.js" defer></script><!-- Header Navigation JS Plugin -->
		<script src="js/jquery.flexslider-min.js" defer></script><!-- FlexSlider JS Plugin -->	
		<script src="js/jquery-asRange.min.js" defer></script><!-- Range Slider JS Plugin -->
		<script src="js/afterglow.min.js" defer></script><!-- Video Player JS Plugin -->
		<script src="js/owl.carousel.min.js" defer></script><!-- Carousel JS Plugin -->
		<script src="js/scripts.js" defer></script><!-- Custom Document Ready JS -->
		
		
	</body>

</html>