<?php
// Initialize the session
session_start();

// Set the PHP timezone to UTC (or your desired timezone)
date_default_timezone_set('UTC');

// Include the check_payment_status script
include "check_payment_status.php";

// Output session variables to the browser's console
echo '<script>';
echo 'console.log("transactionReference: ' . $_SESSION['transactionReference'] . '");';
echo 'console.log("orderId: ' . $_SESSION['orderId'] . '");';
echo 'console.log("Session ID: ' . session_id() . '");';
echo 'console.log("Session Status: ' . session_status() . '");';
echo '</script>';

// Function to fetch user details from the database and check payment status
function getUserDetailsFromDatabase()
{
    global $link; // Assuming you have a database connection object named $link
    global $paymentStatus, $paymentTime; // Define these variables to use them later

    // Retrieve the user details from the database based on the logged-in user's ID
    $userId = $_SESSION["id"]; // Assuming the ID of the logged-in user is stored in $_SESSION["id"]

    $sql = "SELECT first_name, last_name, email, mobile_number, country, state, city, payment_status, payment_time FROM users WHERE id = ?";
    
    if ($stmt = mysqli_prepare($link, $sql)) {
        // Bind the user ID as a parameter
        mysqli_stmt_bind_param($stmt, "i", $userId);
        
        // Execute the statement
        if (mysqli_stmt_execute($stmt)) {
            // Bind the result
            mysqli_stmt_bind_result($stmt, $firstName, $lastName, $email, $mobileNumber, $country, $state, $city, $paymentStatus, $paymentTime);
            
            // Fetch the user details
            if (mysqli_stmt_fetch($stmt)) {
                // Return the fetched user details and payment status/time as an associative array
                return array(
                    "first_name" => $firstName,
                    "last_name" => $lastName,
                    "email" => $email,
                    "mobile_number" => $mobileNumber,
                    "country" => $country,
                    "state" => $state,
                    "city" => $city,
                    "payment_status" => $paymentStatus,
                    "payment_time" => $paymentTime
                );
            }
        }
        
        // Close the statement
        mysqli_stmt_close($stmt);
    }
    
    // In case of an error or no user details found, return an empty array or handle it as needed
    return array();
}

// Call the function to fetch the user details and payment status
$userDetails = getUserDetailsFromDatabase();

// Extract the user details and payment status from the array
$firstName = $userDetails["first_name"] ?? "";
$lastName = $userDetails["last_name"] ?? "";
$email = $userDetails["email"] ?? "";
$mobileNumber = $userDetails["mobile_number"] ?? "";
$country = $userDetails["country"] ?? "";
$state = $userDetails["state"] ?? "";
$city = $userDetails["city"] ?? "";
$paymentStatus = $userDetails["payment_status"] ?? "";
$paymentTime = $userDetails["payment_time"] ?? "";

// Close the database connection
mysqli_close($link);

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
		
		<title>Amollo</title>
	</head>
	<body>
		<header id="masthead-pro" class="">
			<div class="header-container">
			    
			    <h1><a href="index.php"><img src="images/logo.png" alt="Logo"></a></h1>
			    
			    <nav id="site-navigation-pro">
					<ul class="sf-menu">
					    <li class="normal-item-pro">
							<a href="payment.php"><i class="fas fa-desktop"></i>Hello, <?php echo $firstName; ?></a>
						</li>
						<li class="normal-item-pro">
							<a href="payment.php"><i class="fas fa-desktop"></i>Payment</a>
						</li>
						<li class="normal-item-pro">
							<a href="logout.php"><i class="fas fa-desktop"></i>Log Out</a>
						</li>
					</ul>
				</nav>
				
				<div id="mobile-bars-icon-pro" class="noselect"><i class="fas fa-bars"></i></div>
	
				<div class="clearfix"></div>
			</div><!-- close .header-container -->
			
			<nav id="mobile-navigation-pro">
			    <button class="btn btn-mobile-pro btn-header-pro noselect" onclick="window.location.href = '#';">Hello, <?php echo $firstName; ?></button>
				<button class="btn btn-mobile-pro btn-header-pro noselect" onclick="window.location.href = 'payment.php';">Payment</button>
				<button class="btn btn-mobile-pro btn-header-pro noselect" onclick="window.location.href = 'logout.php';">Logout</button>

			</nav>

		</header>
		
		
		<div id="video-page-title-pro" style="background-image:url('images/demo/landing-banner.jpg');">
			<a class="video-page-title-play-button afterglow" href="#Video-Vayvo-Single"><i class="fas fa-play"></i></a>
			
			<div style="display:none;">
				<video id="Video-Vayvo-Single" width="960" height="540" oncontextmenu="return false;">
                  <source src="video/amollo.mp4" type="video/mp4">
                  Your browser does not support the video tag.
                </video>
			</div>
			
			<div id="video-page-title-gradient-base"></div>
		</div><!-- close #video-page-title-pro -->
		
		<div id="content-pro">
			
  	 		<div class="container custom-gutters-pro">
				
				
				<div id="video-post-container">
					<h1 class="video-post-heading-title">Amollo<div id="countdown"></div></h1>
					<div class="clearfix"></div>
					
					<ul id="video-post-meta-list">
						<li><a href="#!">Drama</a></li>
						<li id="video-post-meta-reviews">
							<div class="average-rating-count-progression-studios"></div>
								<div class="average-rating-video-post">
									<div class="average-rating-video-empty">
										<span class="dashicons dashicons-star-empty"></span><span class="dashicons dashicons-star-empty"></span><span class="dashicons dashicons-star-empty"></span><span class="dashicons dashicons-star-empty"></span><span class="dashicons dashicons-star-empty"></span>
									</div>
									<div class="average-rating-overflow-width" style="width:90%;">
										<div class="average-rating-video-filled">
											<span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled"></span><span class="dashicons dashicons-star-filled"></span>
										<div class="clearfix"></div>
										</div><!-- close .average-rating-video-filled -->
									</div><!-- close .average-rating-overflow-width -->
								</div>
							<div class="clearfix"></div>					
						</li>
						<li id="video-post-meta-year">2022</li>
						<li id="video-post-meta-rating"><span>PG-13</span></li>
					</ul>
					<div class="clearfix"></div>

					<div id="video-post-buttons-container">
						<a href="#VideoLightbox-1"class="afterglow" id="video-post-play-text-btn"><i class="fas fa-play"></i>Watch Trailer</a>
						<div style="display:none;">
							<video id="Video-Single" width="960" height="540" data-youtube-id="jirBNu6mcWM"></video>
						</div>
						
						<div id="video-social-sharing-button" class="btn"><i class="fas fa-share"></i>Share</div>
					<div class="clearfix"></div>
					</div><!-- close #video-post-buttons-container -->
					
					<!-- Display payment, current and expiry times -->
					<p>Payment Time: <?php echo $paymentTime; ?>
                    <div id="currentTime"></div>
                    <div id="expiryTime"></div>
					
					<div>
				    <?php
				    // Check if the session variables exist
                    //if (isset($_SESSION['transactionReference']) && isset($_SESSION['orderId'])) {
                        // Clear the session variables
                       // unset($_SESSION['transactionReference']);
                        //unset($_SESSION['orderId']);
                        
                        // Optionally, you can display a message indicating that the variables have been cleared
                        //echo '<p>Session variables cleared.</p>';
                   // } else {
                        //echo '<p>Session variables not set.</p>';
                    //}
                    
                    //echo 'Session ID: ' . session_id() . '<br>';
                    //echo 'Session Status: ' . session_status() . '<br>';
                    ?>
                    </div>
	
					<div id="vayvo-video-post-content">
						<p>Amollo is a graduate of law who wants to change  the narrative of her culture and tradition which she believes infringes on her rights. 
												This is seen in her reaction toward her parents demands to accept their choice of the groom.
												Will Amollo succeed and change the narrative?</p>

<p>She struggles to change the status quo Social-cultural issues prove to be a major stumbling block when she is to be forced to marry Oyoo who comes from her community but she takes a stand and fights for what she believes is right.</p>

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
		
<script>
    // Function to calculate and display remaining time and expiry time
    function calculateTimeRemaining() {
        // Get the payment_time from PHP and parse it as a JavaScript Date object
        var paymentTime = new Date("<?php echo $paymentTime; ?>");

        // Calculate the expiry time by adding 12 hours (43,200,000 milliseconds) to payment_time
        var expiryTime = new Date(paymentTime.getTime() + 43200000);

        // Get the current time in UTC
        var currentTime = new Date();
        var currentUTCTime = new Date(currentTime.getUTCFullYear(), currentTime.getUTCMonth(), currentTime.getUTCDate(), currentTime.getUTCHours(), currentTime.getUTCMinutes(), currentTime.getUTCSeconds());

        // Calculate the remaining time in milliseconds
        var timeRemaining = expiryTime - currentUTCTime;

        // Calculate hours, minutes, and seconds
        var hoursRemaining = Math.floor(timeRemaining / 3600000);
        var minutesRemaining = Math.floor((timeRemaining % 3600000) / 60000);
        var secondsRemaining = Math.floor((timeRemaining % 60000) / 1000);

        // Format the countdown display as "hh:mm:ss"
        var countdownDisplay = hoursRemaining.toString().padStart(2, '0') + ':' + minutesRemaining.toString().padStart(2, '0') + ':' + secondsRemaining.toString().padStart(2, '0');

        // Format the expiry time as a string
        var expiryTimeString = expiryTime.toLocaleString(); // This will use the server's timezone (UTC)

        // Format the current time as a string in UTC
        var currentUTCTimeString = currentUTCTime.toLocaleString("en-US", { timeZoneName: "short" });

        // Update the countdown, expiry, and current time elements
        var countdownElement = document.getElementById('countdown');
        var expiryElement = document.getElementById('expiryTime');
        var currentTimeElement = document.getElementById('currentTime');
        countdownElement.textContent = "Countdown Timer: " + countdownDisplay;
        expiryElement.textContent = "Expires At (UTC): " + expiryTimeString + " GMT+3";
        currentTimeElement.textContent = "Current Time (UTC): " + currentUTCTimeString;

        // Check for time expiry and take action if needed (e.g., redirection)
        if (timeRemaining <= 0) {
            // Redirect or perform any other actions here
            window.location.href = "update_payment_time.php";
        }
    }

    // Call the function to calculate and display remaining time, expiry time, and current time
    calculateTimeRemaining();

    // Set an interval to update the time every second
    setInterval(calculateTimeRemaining, 1000);
</script>

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