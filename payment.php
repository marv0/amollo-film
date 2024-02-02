<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to the login page
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

// Include config and query file
require_once "config.php";

if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    $id = $_SESSION["id"];
    require_once "config.php";

    // Query the database to check the payment_status for the current user
    $sql = "SELECT payment_status FROM users WHERE id = ?";
    
    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $id);

        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_store_result($stmt);

            if (mysqli_stmt_num_rows($stmt) == 1) {
                mysqli_stmt_bind_result($stmt, $payment_status);
                mysqli_stmt_fetch($stmt);

                if ($payment_status == "success") {
                    // Payment status is not 'success', redirect to payment page
                    header("location: welcome.php");
                    exit();
                }
            } else {
                // User not found in the database, handle as needed
                //header("location: login.php"); // Redirect to login page, for example
                echo 'ERRORFOUND';
                exit();
            }
        } else {
            echo "Error executing SQL query: " . mysqli_error($link);
        }
    } else {
        echo "Error in prepared statement: " . mysqli_error($link);
    }
} else {
    // User is not logged in, handle as needed
    header("location: login.php"); // Redirect to login page, for example
    exit();
}

// Function to fetch user details from the database
function getUserDetailsFromDatabase()
{
    global $link; // Assuming you have a database connection object named $link

    // Retrieve the user details from the database based on the logged-in user's ID
    $userId = $_SESSION["id"]; // Assuming the ID of the logged-in user is stored in $_SESSION["id"]

    $sql = "SELECT first_name, last_name, email, mobile_number, country, state, city FROM users WHERE id = ?";
    
    if ($stmt = mysqli_prepare($link, $sql)) {
        // Bind the user ID as a parameter
        mysqli_stmt_bind_param($stmt, "i", $userId);
        
        // Execute the statement
        if (mysqli_stmt_execute($stmt)) {
            // Bind the result
            mysqli_stmt_bind_result($stmt, $firstName, $lastName, $email, $mobileNumber, $country, $state, $city);
            
            // Fetch the user details
            if (mysqli_stmt_fetch($stmt)) {
                // Return the fetched user details as an associative array
                return array(
                    "first_name" => $firstName,
                    "last_name" => $lastName,
                    "email" => $email,
                    "mobile_number" => $mobileNumber,
                    "country" => $country,
                    "state" => $state,
                    "city" => $city
                );
            }
        }
        
        // Close the statement
        mysqli_stmt_close($stmt);
    }
    
    // In case of an error or no user details found, return an empty array or handle it as needed
    return array();
}



// Call the function to fetch the user details
$userDetails = getUserDetailsFromDatabase();

// Extract the user details from the array
$firstName = $userDetails["first_name"] ?? "";
$lastName = $userDetails["last_name"] ?? "";
$email = $userDetails["email"] ?? "";
$mobileNumber = $userDetails["mobile_number"] ?? "";
$country = $userDetails["country"] ?? "";
$state = $userDetails["state"] ?? "";
$city = $userDetails["city"] ?? "";


// Close the database connection
mysqli_close($link);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Amollo Film - Payment Processing</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="../../fonts.googleapis.com/css222a2.css?family=Fira+Sans+Condensed:wght@300;400;500;700&amp;family=Lato:wght@300;400;700&amp;display=swap">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <link rel="stylesheet" href="icons/fontawesome/css/all.min.css"><!-- FontAwesome Icons -->
    <link rel="stylesheet" href="icons/dashicons/css/dashicons-min.css"><!-- DashIcons For Star Ratings -->
    <style>
        <style>
    body {
        background-color: #ffffff;
        color: #fff;
        font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
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
    color: #fff;
    font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
    font-size: 14px;
    padding: 20px;
    margin-top: 50px;
    max-width: 600px;
    margin: 0 auto;
}

form h4 {
    margin-bottom: 20px;
}

form label {
    display: block;
    margin-bottom: 8px;
}

form input[type="text"],
form input[type="email"],
form input[type="password"],
form select {
    background-color: #333;
    border: none;
    padding: 8px 20px;
    margin-bottom: 10px;
    width: 100%;
    color: #fff;
    border-radius: 4px;
    box-sizing: border-box;
}

form .invalid-feedback {
    color: #e50914;
    margin-top: 4px;
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

form p {
    margin-top: 10px;
    color: #fff;
}

form a {
    color: #e50914;
    text-decoration: none;
}

form a:hover {
    text-decoration: underline;
}

.button {
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

.button:hover {
    background-color: #ff0a16;
}
</style>

    </style>
</head>
<body>
		<header id="masthead-pro" class="">
			<div class="header-container">
			    
			    <h1><a href="index.php"><img src="images/logo.png" alt="Logo"></a></h1>
			    
			    <nav id="site-navigation-pro">
					<ul class="sf-menu">
						<li class="normal-item-pro">
							<a href="logout.php"><i class="fas fa-desktop"></i>Log Out</a>
						</li>
					</ul>
				</nav>
				
				<div id="mobile-bars-icon-pro" class="noselect"><i class="fas fa-bars"></i></div>
	
				<div class="clearfix"></div>
			</div><!-- close .header-container -->
			
			<nav id="mobile-navigation-pro">
				<button class="btn btn-mobile-pro btn-header-pro noselect" onclick="window.location.href = 'logout.php';">Log Out</button>
			</nav>

		</header>

    <div class="container">
                <?php
        // Check if the session variables exist
if (isset($_SESSION['transactionReference']) && isset($_SESSION['orderId'])) {
    $transactionReference = $_SESSION['transactionReference'];
    $orderId = $_SESSION['orderId'];
    $username  = $_SESSION["username"];
    
// Display the session variable values
    //echo '<p>Username: ' . $username . '</p>';
    //echo '<p>Transaction Reference: ' . $transactionReference . '</p>';
    //echo '<p>Order ID: ' . $orderId . '</p>';
} else {
    // Generate random values
    $transactionReference = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 10);
    $orderId = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 10);
    
    // Store data in session variables
    $_SESSION['transactionReference'] = $transactionReference;
    $_SESSION['orderId'] = $orderId;
    
    // Display the session variable values
    echo '<p>Transaction Reference: ' . $transactionReference . '</p>';
    echo '<p>Order ID: ' . $orderId . '</p>';
    echo 'Session ID: ' . session_id() . '<br>';
    echo 'Session Status: ' . session_status() . '<br>';
    echo $_SESSION["loggedin"];
}

// Output session variables to the browser's console
echo '<script>';
echo 'console.log("transactionReference: ' . $_SESSION['transactionReference'] . '");';
echo 'console.log("orderId: ' . $_SESSION['orderId'] . '");';
echo 'console.log("Session ID: ' . session_id() . '");';
echo 'console.log("Session Status: ' . session_status() . '");';
echo '</script>';
       ?>
       
        <?php
        if (isset($_GET['registration']) && $_GET['registration'] === 'success') {
            echo '<div class="alert alert-success">Account created successfully. Make a payment to watch the movie.</div>';
        }
        ?>
        <form action="https://gatewaybackend.quickteller.co.ke/ipg-backend/api/checkout"  id="example_form" method="post" class="form" target="_blank">
                <fieldset>
                    <h4>Choose Your Preferred Payment Method</h4>
                    <p>Enjoy 12 hours of access to this movie for a payment of $2.99 or KES.299. Please note that your access will expire after 5 hours, and you will need to make a new payment to continue enjoying the content.</p>
                    <label for="customerFirstName" style="display: none;">First Name</label>
                    <input id="customerFirstName" name="customerFirstName" type="text" value="<?php echo $firstName; ?>" style="display: none;">
                    <label for="customerSecondName" style="display: none;">Second Name</label>
                    <input id="customerSecondName" name="customerSecondName" type="text" value="<?php echo $lastName; ?>" style="display: none;">
                    <label for="customerEmail" style="display: none;">Email Address</label>
                    <input id="customerEmail" name="customerEmail" type="text"
                           value="<?php echo $email; ?>" style="display: none;">
                    <label for="customerMobile" style="display: none;">Mobile Number</label>
                    <input id="customerMobile" name="customerMobile" type="text" value="<?php echo $mobileNumber; ?>" style="display: none;">
                    <label for="customerCountry" style="display: none;">Country</label>
                    <input id="customerCountry" name="customerCountry" type="text" value="<?php echo $country; ?>" style="display: none;">
                    <label for="customerState" style="display: none;">State</label>
                    <input id="customerState" name="customerState" type="text" value="<?php echo $state; ?>" style="display: none;">
                    <label for="customerCity" style="display: none;">City</label>
                    <input id="customerCity" name="customerCity" type="text" value="<?php echo $city; ?>" style="display: none;">
                    
                    <! -- Hidden customer fields -->
                    <label for="customerId" style="display: none;">customerId</label>
                    <input id="customerId" name="customerId" type="text" value="<?php echo $_SESSION["username"]; ?>" style="display: none;">
                    <label for="redirectUrl" style="display: none;">redirectUrl</label>
                    <input id="redirectUrl" name="redirectUrl" type="text" value="https://www.kenaija.com/query.php" style="display: none;">
                    <label for="amount" style="display: none;">amount</label>
                    <input id="amount" name="amount" type="text" value="35000" style="display: none;">
                    <label for="merchantName" style="display: none;">merchantName</label>
                    <input id="merchantName" name="merchantName" type="text" style="display: none;">  
                    <label for="merchantCode" style="display: none;">merchantCode</label>
                    <input id="merchantCode" name="merchantCode" type="text" value="KENAIJA001" style="display: none;">
                    <label for="domain" style="display: none;">domain</label>
                    <input id="domain" name="domain" type="text" value="ISWKE" style="display: none;">
                    <label for="transactionReference" style="display: none;">transactionReference</label>
                    <input id="transactionReference" name="transactionReference" type="text" value="<?php echo $_SESSION['transactionReference']; ?>" style="display: none;">
                    <label for="iconUrl" style="display: none;">iconUrl</label>
                    <input id="iconUrl" name="iconUrl" type="text" value="" style="display: none;">
                    <label for="orderId" style="display: none;">orderId</label>
                    <input id="orderId" name="orderId" type="text" value="<?php echo $_SESSION['orderId']; ?>" style="display: none;">
                    <label for="expiryTime" style="display: none;">expiryTime</label>
                    <input id="expiryTime" name="expiryTime" type="text" style="display: none;">
                    <label for="dateOfPayment" style="display: none;">dateOfPayment</label>
                    <input id="dateOfPayment" name="dateOfPayment" type="text" value="2016-09-05T10:20:26" style="display: none;">
                    <label for="currencyCode" style="display: none;">currencyCode</label>
                    <input id="currencyCode" name="currencyCode" type="text" value="USD" style="display: none;">
                    <label for="cardTokensJson" style="display: none;">cardTokensJson</label>
                    <input id="cardTokensJson" name="cardTokensJson" type="text"
                           value='[{"panLast4Digits":"1895","panFirst6Digits":"506183","token":"C48FA7D7F466914A3E4440DE458AABC1914B9500CC7780BEB4","expiry":"05/20"},{"panLast4Digits":"1111","panFirst6Digits":"411111","token":"3105E927EF17A245977CDA0ED62B257E4378592E8D7C7A5272-016153570198200","expiry":"02/22"}]'                    <input id="currencyCode" name="currencyCode" type="text" value="KES" style="display: none;">

                    <label for="terminalType" style="display: none;">terminalType</label>
                    <input id="terminalType" name="terminalType" type="text" value="What?" style="display: none;">
                    <label for="narration" style="display: none;">narration</label>
                    <input id="narration" name="narration" type="text" value="Test from new gateway" style="display: none;">
                    
                    <label for="customerPostalCode" style="display: none;">customerPostalCode</label>
                    <input id="customerPostalCode" name="customerPostalCode" type="text" value="wstlnds" style="display: none;">
                    <label for="customerStreet" style="display: none;">customerStreet</label>
                    <input id="customerStreet" name="customerStreet" type="text" value="1002" style="display: none;">

                    <label for="providerIconUrl" style="display: none;">providerIconUrl</label>
                    <input id="providerIconUrl" name="providerIconUrl" type="text" style="display: none;">
                    <label for="reqId" style="display: none;">reqId</label>
                    <input id="reqId" name="reqId" type="text" style="display: none;">
                    <label for="field1" style="display: none;">field1</label>
                    <input id="field1" name="field1" type="text"
                           value='{"merchant_merchantDescriptorName":"company","merchant_merchantCategoryCode":"4816"}' style="display: none;">
                    <label for="terminalId" style="display: none;">terminalId</label>
                    <input id="terminalId" name="terminalId" type="text" value="3TLP0001" style="display: none;">
                    <label for="channel" style="display: none;">channel</label>
                    <input id="channel" name="channel" type="text" value="WEB" style="display: none;">
                    <label for="fee" style="display: none;">fee</label>
                    <input id="fee" name="fee" type="text" value="0" style="display: none;">
                    <label for="preauth" style="display: none;">preauth</label>
                    <input id="preauth" name="preauth" type="text" value="0" style="display: none;">
                    <label for="displayPrivacyPolicy" style="display: none;">displayPrivacyPolicy</label>
                    <input id="displayPrivacyPolicy" name="displayPrivacyPolicy" type="checkbox" value=true style="display: none;">
                    <label for="applyOffer" style="display: none;">applyOffer</label>
                    <input id="applyOffer" name="applyOffer" type="checkbox" value=false style="display: none;">
                    <label for="primaryAccentColor" style="display: none;">primaryAccentColor</label>
                    <input id="primaryAccentColor" name="primaryAccentColor" type="text" value="#ff00ff" style="display: none;">
                    <label for="redirectMerchantName" style="display: none;">redirectMerchantName</label>
                    <input id="redirectMerchantName" name="redirectMerchantName" type="text" value="custom return message" style="display: none;">
                    
                    <hr/>
                    <input type="checkbox" id="acceptTerms"> I accept the terms and conditions.
                </fieldset>
                <button formtarget="checkout-form-container" id="KES" onclick="openPopup(); setCurrency('KES', '100');" class="button" disabled>Kenya Shillings (KES 1)</button>
                <button formtarget="checkout-form-container" id="USD" onclick="openPopup(); setCurrency('USD', '1');" class="button" disabled>US Dollars  ($ 0.01)</button>
                <!-- <button formtarget="checkout-form-container" id="GBP" onclick="openPopup(); setCurrency('GBP', '299');" class="button" disabled>British Pound Sterling (£ 2.99)</button> -->
        </form>

        
<div id="checkout-popup" style="display: none; position: absolute; z-index: 999;">
    <button id="close-iframe" onclick="closePopup()"
            style="z-index: 99999999999; color: red; position: fixed; right: 0;	font-size: xx-large; top: 0; margin: 8px; border: none; background: none;">
        ×
    </button>
    <iframe id="checkout-form-container" name="checkout-form-container" style="
                width: 100%;
                height: 100%;
                position: fixed;
                border: none;
                top: 0;
                left: 0;">
    </iframe>
    <script>
        // Get the checkbox and buttons
    var acceptTermsCheckbox = document.getElementById("acceptTerms");
    var kesButton = document.getElementById("KES");
    var usdButton = document.getElementById("USD");
    var gbpButton = document.getElementById("GBP");

    // Add an event listener to the checkbox
    acceptTermsCheckbox.addEventListener("change", function () {
        // Enable or disable buttons based on the checkbox state
        kesButton.disabled = !acceptTermsCheckbox.checked;
        usdButton.disabled = !acceptTermsCheckbox.checked;
        gbpButton.disabled = !acceptTermsCheckbox.checked;

        // Show an alert if the checkbox is not checked
        if (!acceptTermsCheckbox.checked) {
            alert("Please accept the terms and conditions to proceed.");
        }
    });
    
        function setCurrency(currencyCode, amount) {
            document.getElementById('currencyCode').value = currencyCode;
            document.getElementById('amount').value = amount;
        } 
            
        function closePopup() {
            document.getElementById("checkout-popup").style.display = "none";
        }

        function openPopup() {
            document.getElementById("checkout-popup").style.display = "block";
        }

        Date.prototype.addMinutes = function (h) {
            this.setMinutes(this.getMinutes() + h);
            return this;
        }

        function hslToHex(h, s, l) {
            l /= 100;
            const a = s * Math.min(l, 1 - l) / 100;
            const f = n => {
                const k = (n + h / 30) % 12;
                const color = l - a * Math.max(Math.min(k - 3, 9 - k, 1), -1);
                return Math.round(255 * color).toString(16).padStart(2, '0');   // convert to Hex and prefix "0" if needed
            };
            return `#${f(0)}${f(8)}${f(4)}`;
        }

        $(function () {
            $('#expiryTime').val(new Date().addMinutes(1).toISOString());
            let hue = Math.floor(Math.random() * 360);
            let saturation = 50 + Math.floor(Math.random() * 50);//50-100
            let brightness = 50 + Math.floor(Math.random() * 30);//50-80
            $('#primaryAccentColor').val(hslToHex(hue, saturation, brightness));
        });
        
    </script>
</div>

        <footer id="footer-pro">
            <div class="container">
                <div class="row">
                    <div class="col-md">
                        <div class="copyright-text-pro">
                            Powered by <a href="https://www.digitalducks.co.ke">Digital Ducks</a>.
                        </div>
                    </div><!-- close .col -->
                </div><!-- close .row -->
            </div><!-- close .container -->
        </footer>
    </div>
</body>
</html>

