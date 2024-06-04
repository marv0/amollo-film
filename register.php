<?php
// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$username = $first_name = $last_name = $email = $mobile_number = $country = $state = $city = $password = $confirm_password = "";
$username_err = $first_name_err = $last_name_err = $email_err = $mobile_number_err = $country_err = $state_err = $city_err = $password_err = $confirm_password_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Validate username
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter a username.";
    } elseif(!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["username"]))){
        $username_err = "Username can only contain letters, numbers, and underscores.";
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

            // Close statement
            mysqli_stmt_close($stmt);
        }
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

    // Validate first name
    if(empty(trim($_POST["first_name"]))){
        $first_name_err = "Please enter your first name.";
    } else{
        $first_name = trim($_POST["first_name"]);
    }

    // Validate last name
    if(empty(trim($_POST["last_name"]))){
        $last_name_err = "Please enter your last name.";
    } else{
        $last_name = trim($_POST["last_name"]);
    }

    // Validate email
    if(empty(trim($_POST["email"]))){
        $email_err = "Please enter an email.";
    } else{
        $email = trim($_POST["email"]);
    }

    // Validate mobile number
    if(empty(trim($_POST["mobile_number"]))){
        $mobile_number_err = "Please enter your mobile number.";
    } else{
        $mobile_number = trim($_POST["mobile_number"]);
    }

    // Validate country
    if(empty(trim($_POST["country"]))){
        $country_err = "Please enter your country.";
    } else{
        $country = trim($_POST["country"]);
    }

// Check if the manual state field is not empty
if (!empty(trim($_POST["manual-state"]))) {
    $state = trim($_POST["manual-state"]);
} elseif (!empty(trim($_POST["state"]))) {
    // If manual state is empty, check if the regular state field is not empty
    $state = trim($_POST["state"]);
} else {
    // If both fields are empty, set an error message for the state field
    $state_err = "Please enter your state.";
}

    // Validate city
    if(empty(trim($_POST["city"]))){
        $city_err = "Please enter your city.";
    } else{
        $city = trim($_POST["city"]);
    }

    // Check input errors before inserting in database
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err) && empty($first_name_err) && empty($last_name_err) && empty($email_err) && empty($mobile_number_err) && empty($country_err) && empty($state_err) && empty($city_err)){

        // Prepare an insert statement
        $sql = "INSERT INTO users (username, password, first_name, last_name, email, mobile_number, country, state, city) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssssssss", $param_username, $param_password, $param_first_name, $param_last_name, $param_email, $param_mobile_number, $param_country, $param_state, $param_city);

            // Set parameters
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            $param_first_name = $first_name;
            $param_last_name = $last_name;
            $param_email = $email;
            $param_mobile_number = $mobile_number;
            $param_country = $country;
            $param_state = $state;
            $param_city = $city;

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                        // Start a session.
        session_start();

        // Set the session variables for the logged-in user.
        $_SESSION["loggedin"] = true;
        $_SESSION["id"] = mysqli_insert_id($link);  // Get the ID of the newly registered user.
        $_SESSION["username"] = $username;  // The user's username.

        // Redirect the user to a welcome or dashboard page.
        header("location: payment.php?registration=success");
        exit();
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    } else {
    // Display validation errors to the user or take any other appropriate action
    echo "Form submission failed due to validation errors. Please correct them and try again.";
}

    // Close connection
    mysqli_close($link);
    
    // Display the fetched first and last names
for($i = 0; $i < count($firstNames); $i++){
    echo "First Name: " . $firstNames[$i] . "<br>";
    echo "Last Name: " . $lastNames[$i] . "<br><br>";
}
}
?>

 
<!DOCTYPE html>
<html>
<head>
	<title>Amollo Film - Sign Up</title>
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
				<button class="btn btn-mobile-pro btn-header-pro noselect" data-toggle="modal" data-target="login.php">Login</button>
				<button class="btn btn-mobile-pro btn-header-pro noselect" data-toggle="modal" data-target="register.php">Sign Up</button>
			</nav>

		</header>
	<div class="container">

		<?php 
        if(!empty($login_err)){
            echo '<div class="alert alert-danger">' . $login_err . '</div>';
        }        
        ?>

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" onsubmit="return validateForm()">
    <h4>Sign Up</h4>
    <div class="form-group">
        <label>Username</label>
        <input type="text" name="username" id="usernameField" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
        <span id="usernameAvailability" class="availability"></span>
        <span class="invalid-feedback"><?php echo $username_err; ?></span>
    </div>    
    <div class="form-group">
        <label>Password</label>
        <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>">
        <span class="invalid-feedback"><?php echo $password_err; ?></span>
    </div>
    <div class="form-group">
        <label>Confirm Password</label>
        <input type="password" name="confirm_password" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $confirm_password; ?>">
        <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
    </div>
    <div class="form-group">
        <label>First Name</label>
        <input type="text" name="first_name" class="form-control" value="<?php echo $first_name; ?>">
    </div>
    <div class="form-group">
        <label>Last Name</label>
        <input type="text" name="last_name" class="form-control" value="<?php echo $last_name; ?>">
    </div>
    <div class="form-group">
        <label>Email Address</label>
        <input type="email" name="email" class="form-control" value="<?php echo $email; ?>">
    </div>
    <div class="form-group">
        <label>Mobile Number</label>
        <input type="text" name="mobile_number" class="form-control" value="<?php echo $mobile_number; ?>">
    </div>
<div class="form-group">
    <label for="country">Country</label>
    <select name="country" id="country" class="form-control">
    </select>
</div>

<div class="form-group" id="state-group" style="display: none;">
    <label for="state">State/Province</label>
    <select name="state" id="state" class="form-control">
        <!-- States/Provinces will be dynamically added here -->
    </select>
</div>

<div class="form-group" id="manual-state-group" style="display: none;">
    <label for="manual-state">State/Province</label>
    <input type="text" name="manual-state" id="manual-state" class="form-control">
</div>
    <div class="form-group">
        <label>City</label>
        <input type="text" name="city" class="form-control" value="<?php echo $city; ?>">
    </div>
    <div class="form-group">
        <input type="submit" class="btn btn-primary" value="Sign Up">
    </div>
    <p>Already have an account? <a href="login.php">Login here</a>.</p>
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
<script>
        // Function to check username availability
        function checkUsernameAvailability() {
            var username = document.getElementById("usernameField").value;
            var availabilitySpan = document.getElementById("usernameAvailability");
            
            if (username) {
                // Make an AJAX request to check username availability
                var xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        var response = xhr.responseText;
                        
                        if (response === "taken") {
                            availabilitySpan.innerHTML = "This username is already taken.";
                        } else if (response === "available") {
                            availabilitySpan.innerHTML = "This username is available.";
                        } else {
                            availabilitySpan.innerHTML = "Error occurred while checking availability.";
                        }
                    }
                };
                
                xhr.open("POST", "check_username.php", true);
                xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhr.send("username=" + username);
            } else {
                availabilitySpan.innerHTML = ""; // Clear the availability message
            }
        }

        // Attach the checkUsernameAvailability function to the username input's onchange event
        document.getElementById("usernameField").onchange = checkUsernameAvailability;

function validateForm() {
        // Get references to all form fields
        var usernameField = document.querySelector('input[name="username"]');
        var passwordField = document.querySelector('input[name="password"]');
        var confirmPasswordField = document.querySelector('input[name="confirm_password"]');
        var firstNameField = document.querySelector('input[name="first_name"]');
        var lastNameField = document.querySelector('input[name="last_name"]');
        var emailField = document.querySelector('input[name="email"]');
        var mobileNumberField = document.querySelector('input[name="mobile_number"]');
        var countryField = document.getElementById("country");
        var stateField = document.getElementById("state");
        var manualStateField = document.getElementById("manual-state");
        var cityField = document.querySelector('input[name="city"]');

        // Check if any of the required fields are empty
        if (
            usernameField.value === '' ||
            passwordField.value === '' ||
            confirmPasswordField.value === '' ||
            firstNameField.value === '' ||
            lastNameField.value === '' ||
            emailField.value === '' ||
            mobileNumberField.value === '' ||
            countryField.value === ''
        ) {
            alert("Please fill in all required fields.");
            return false; // Prevent form submission
        }

        // Check if password and confirm password match
        if (passwordField.value !== confirmPasswordField.value) {
            alert("Password and Confirm Password do not match.");
            return false; // Prevent form submission
        }

        // You can add more specific validation for other fields if needed.
        // For example, checking email format, mobile number format, etc.

        // Additional validation for specific fields can be added here

        // If the selected country requires state/province input, check that too
        if (countryField.value === 'US' || countryField.value === 'CA') {
            if (stateField.value === '') {
                alert("Please fill in all required fields.");
                return false; // Prevent form submission
            }
        } else {
            if (manualStateField.value === '') {
                alert("Please fill in all required fields.");
                return false; // Prevent form submission
            }
        }
        
        // If the username is not available, prevent form submission
            var availabilitySpan = document.getElementById("usernameAvailability");
            if (availabilitySpan.innerHTML === "This username is already taken.") {
                alert("Please choose a different username.");
                return false; // Prevent form submission
            }

        // Allow form submission if all checks pass
        return true;
    }
    
    

    var countries = [
    { code: 'AF', name: 'Afghanistan' },
    { code: 'AL', name: 'Albania' },
    { code: 'DZ', name: 'Algeria' },
    { code: 'AD', name: 'Andorra' },
    { code: 'AO', name: 'Angola' },
    { code: 'AG', name: 'Antigua and Barbuda' },
    { code: 'AR', name: 'Argentina' },
    { code: 'AM', name: 'Armenia' },
    { code: 'AU', name: 'Australia' },
    { code: 'AT', name: 'Austria' },
    { code: 'AZ', name: 'Azerbaijan' },
    { code: 'BS', name: 'Bahamas' },
    { code: 'BH', name: 'Bahrain' },
    { code: 'BD', name: 'Bangladesh' },
    { code: 'BB', name: 'Barbados' },
    { code: 'BY', name: 'Belarus' },
    { code: 'BE', name: 'Belgium' },
    { code: 'BZ', name: 'Belize' },
    { code: 'BJ', name: 'Benin' },
    { code: 'BT', name: 'Bhutan' },
    { code: 'BO', name: 'Bolivia' },
    { code: 'BA', name: 'Bosnia and Herzegovina' },
    { code: 'BW', name: 'Botswana' },
    { code: 'BR', name: 'Brazil' },
    { code: 'BN', name: 'Brunei' },
    { code: 'BG', name: 'Bulgaria' },
    { code: 'BF', name: 'Burkina Faso' },
    { code: 'BI', name: 'Burundi' },
    { code: 'KH', name: 'Cambodia' },
    { code: 'CM', name: 'Cameroon' },
    { code: 'CA', name: 'Canada' },
    { code: 'CV', name: 'Cape Verde' },
    { code: 'CF', name: 'Central African Republic' },
    { code: 'TD', name: 'Chad' },
    { code: 'CL', name: 'Chile' },
    { code: 'CN', name: 'China' },
    { code: 'CO', name: 'Colombia' },
    { code: 'KM', name: 'Comoros' },
    { code: 'CG', name: 'Congo (Brazzaville)' },
    { code: 'CD', name: 'Congo (Kinshasa)' },
    { code: 'CR', name: 'Costa Rica' },
    { code: 'HR', name: 'Croatia' },
    { code: 'CU', name: 'Cuba' },
    { code: 'CY', name: 'Cyprus' },
    { code: 'CZ', name: 'Czech Republic' },
    { code: 'DK', name: 'Denmark' },
    { code: 'DJ', name: 'Djibouti' },
    { code: 'DM', name: 'Dominica' },
    { code: 'DO', name: 'Dominican Republic' },
    { code: 'EC', name: 'Ecuador' },
    { code: 'EG', name: 'Egypt' },
    { code: 'SV', name: 'El Salvador' },
    { code: 'GQ', name: 'Equatorial Guinea' },
    { code: 'ER', name: 'Eritrea' },
    { code: 'EE', name: 'Estonia' },
    { code: 'ET', name: 'Ethiopia' },
    { code: 'FJ', name: 'Fiji' },
    { code: 'FI', name: 'Finland' },
    { code: 'FR', name: 'France' },
    { code: 'GA', name: 'Gabon' },
    { code: 'GM', name: 'Gambia' },
    { code: 'GE', name: 'Georgia' },
    { code: 'DE', name: 'Germany' },
    { code: 'GH', name: 'Ghana' },
    { code: 'GR', name: 'Greece' },
    { code: 'GD', name: 'Grenada' },
    { code: 'GT', name: 'Guatemala' },
    { code: 'GN', name: 'Guinea' },
    { code: 'GW', name: 'Guinea-Bissau' },
    { code: 'GY', name: 'Guyana' },
    { code: 'HT', name: 'Haiti' },
    { code: 'HN', name: 'Honduras' },
    { code: 'HU', name: 'Hungary' },
    { code: 'IS', name: 'Iceland' },
    { code: 'IN', name: 'India' },
    { code: 'ID', name: 'Indonesia' },
    { code: 'IR', name: 'Iran' },
    { code: 'IQ', name: 'Iraq' },
    { code: 'IE', name: 'Ireland' },
    { code: 'IL', name: 'Israel' },
    { code: 'IT', name: 'Italy' },
    { code: 'JM', name: 'Jamaica' },
    { code: 'JP', name: 'Japan' },
    { code: 'JO', name: 'Jordan' },
    { code: 'KZ', name: 'Kazakhstan' },
    { code: 'KE', name: 'Kenya' },
    { code: 'KI', name: 'Kiribati' },
    { code: 'KP', name: 'North Korea' },
    { code: 'KR', name: 'South Korea' },
    { code: 'KW', name: 'Kuwait' },
    { code: 'KG', name: 'Kyrgyzstan' },
    { code: 'LA', name: 'Laos' },
    { code: 'LV', name: 'Latvia' },
    { code: 'LB', name: 'Lebanon' },
    { code: 'LS', name: 'Lesotho' },
    { code: 'LR', name: 'Liberia' },
    { code: 'LY', name: 'Libya' },
    { code: 'LI', name: 'Liechtenstein' },
    { code: 'LT', name: 'Lithuania' },
    { code: 'LU', name: 'Luxembourg' },
    { code: 'MK', name: 'Macedonia' },
    { code: 'MG', name: 'Madagascar' },
    { code: 'MW', name: 'Malawi' },
    { code: 'MY', name: 'Malaysia' },
    { code: 'MV', name: 'Maldives' },
    { code: 'ML', name: 'Mali' },
    { code: 'MT', name: 'Malta' },
    { code: 'MH', name: 'Marshall Islands' },
    { code: 'MR', name: 'Mauritania' },
    { code: 'MU', name: 'Mauritius' },
    { code: 'MX', name: 'Mexico' },
    { code: 'FM', name: 'Micronesia' },
    { code: 'MD', name: 'Moldova' },
    { code: 'MC', name: 'Monaco' },
    { code: 'MN', name: 'Mongolia' },
    { code: 'ME', name: 'Montenegro' },
    { code: 'MA', name: 'Morocco' },
    { code: 'MZ', name: 'Mozambique' },
    { code: 'MM', name: 'Myanmar' },
    { code: 'NA', name: 'Namibia' },
    { code: 'NR', name: 'Nauru' },
    { code: 'NP', name: 'Nepal' },
    { code: 'NL', name: 'Netherlands' },
    { code: 'NZ', name: 'New Zealand' },
    { code: 'NI', name: 'Nicaragua' },
    { code: 'NE', name: 'Niger' },
    { code: 'NG', name: 'Nigeria' },
    { code: 'NO', name: 'Norway' },
    { code: 'OM', name: 'Oman' },
    { code: 'PK', name: 'Pakistan' },
    { code: 'PW', name: 'Palau' },
    { code: 'PS', name: 'Palestine' },
    { code: 'PA', name: 'Panama' },
    { code: 'PG', name: 'Papua New Guinea' },
    { code: 'PY', name: 'Paraguay' },
    { code: 'PE', name: 'Peru' },
    { code: 'PH', name: 'Philippines' },
    { code: 'PL', name: 'Poland' },
    { code: 'PT', name: 'Portugal' },
    { code: 'QA', name: 'Qatar' },
    { code: 'RO', name: 'Romania' },
    { code: 'RU', name: 'Russia' },
    { code: 'RW', name: 'Rwanda' },
    { code: 'KN', name: 'Saint Kitts and Nevis' },
    { code: 'LC', name: 'Saint Lucia' },
    { code: 'VC', name: 'Saint Vincent and the Grenadines' },
    { code: 'WS', name: 'Samoa' },
    { code: 'SM', name: 'San Marino' },
    { code: 'ST', name: 'Sao Tome and Principe' },
    { code: 'SA', name: 'Saudi Arabia' },
    { code: 'SN', name: 'Senegal' },
    { code: 'RS', name: 'Serbia' },
    { code: 'SC', name: 'Seychelles' },
    { code: 'SL', name: 'Sierra Leone' },
    { code: 'SG', name: 'Singapore' },
    { code: 'SK', name: 'Slovakia' },
    { code: 'SI', name: 'Slovenia' },
    { code: 'SB', name: 'Solomon Islands' },
    { code: 'SO', name: 'Somalia' },
    { code: 'ZA', name: 'South Africa' },
    { code: 'SS', name: 'South Sudan' },
    { code: 'ES', name: 'Spain' },
    { code: 'LK', name: 'Sri Lanka' },
    { code: 'SD', name: 'Sudan' },
    { code: 'SR', name: 'Suriname' },
    { code: 'SZ', name: 'Eswatini' },
    { code: 'SE', name: 'Sweden' },
    { code: 'CH', name: 'Switzerland' },
    { code: 'SY', name: 'Syria' },
    { code: 'TW', name: 'Taiwan' },
    { code: 'TJ', name: 'Tajikistan' },
    { code: 'TZ', name: 'Tanzania' },
    { code: 'TH', name: 'Thailand' },
    { code: 'TL', name: 'Timor-Leste' },
    { code: 'TG', name: 'Togo' },
    { code: 'TO', name: 'Tonga' },
    { code: 'TT', name: 'Trinidad and Tobago' },
    { code: 'TN', name: 'Tunisia' },
    { code: 'TR', name: 'Turkey' },
    { code: 'TM', name: 'Turkmenistan' },
    { code: 'TV', name: 'Tuvalu' },
    { code: 'UG', name: 'Uganda' },
    { code: 'UA', name: 'Ukraine' },
    { code: 'AE', name: 'United Arab Emirates' },
    { code: 'GB', name: 'United Kingdom' },
    { code: 'US', name: 'United States' },
    { code: 'UY', name: 'Uruguay' },
    { code: 'UZ', name: 'Uzbekistan' },
    { code: 'VU', name: 'Vanuatu' },
    { code: 'VA', name: 'Vatican City' },
    { code: 'VE', name: 'Venezuela' },
    { code: 'VN', name: 'Vietnam' },
    { code: 'YE', name: 'Yemen' },
    { code: 'ZM', name: 'Zambia' },
    { code: 'ZW', name: 'Zimbabwe' },
];

    
document.addEventListener("DOMContentLoaded", function() {
    const countrySelect = document.getElementById("country");
    const stateGroup = document.getElementById("state-group");
    const stateSelect = document.getElementById("state");
    const manualStateGroup = document.getElementById("manual-state-group");
    const manualStateInput = document.getElementById("manual-state");
    
        // Loop through the countries array and add options to the select element
    for (var i = 0; i < countries.length; i++) {
        var option = document.createElement('option');
        option.value = countries[i].code;
        option.text = countries[i].name;
        countrySelect.appendChild(option);
    }

    const usStates = [
        "AL", "AK", "AZ", "AR", "CA", "CO", "CT", "DE", "FL", "GA",
        "HI", "ID", "IL", "IN", "IA", "KS", "KY", "LA", "ME", "MD",
        "MA", "MI", "MN", "MS", "MO", "MT", "NE", "NV", "NH", "NJ",
        "NM", "NY", "NC", "ND", "OH", "OK", "OR", "PA", "RI", "SC",
        "SD", "TN", "TX", "UT", "VT", "VA", "WA", "WV", "WI", "WY"
    ];

    const caProvinces = [
        "AB", "BC", "MB", "NB", "NL", "NS", "NT", "NU", "ON", "PE", "QC", "SK", "YT"
    ];

    countrySelect.addEventListener("change", function() {
        const selectedCountry = countrySelect.value;

        if (selectedCountry === "US" || selectedCountry === "CA") {
            stateGroup.style.display = "block";
            manualStateGroup.style.display = "none";
            stateSelect.innerHTML = "";

            const states = selectedCountry === "US" ? usStates : caProvinces;

            states.forEach(stateCode => {
                const option = document.createElement("option");
                option.value = stateCode;
                option.text = stateCode;
                stateSelect.appendChild(option);
            });
        } else {
            stateGroup.style.display = "none";
            manualStateGroup.style.display = "block";
        }
    });

    // Trigger change event to initialize state dropdown based on initial country value
    countrySelect.dispatchEvent(new Event("change"));
});
</script>
</html>
