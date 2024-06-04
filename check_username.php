<?php
// Include config file
require_once "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    
    // Prepare a select statement
    $sql = "SELECT id FROM users WHERE username = ?";
    
    if ($stmt = mysqli_prepare($link, $sql)) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "s", $param_username);
        
        // Set parameters
        $param_username = $username;
        
        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
            /* store result */
            mysqli_stmt_store_result($stmt);
            
            if (mysqli_stmt_num_rows($stmt) == 1) {
                // Username is taken
                echo "taken";
            } else {
                // Username is available
                echo "available";
            }
        } else {
            // Error occurred
            echo "error";
        }
        
        // Close statement
        mysqli_stmt_close($stmt);
    }
    
    // Close database connection
    mysqli_close($link);
}
?>