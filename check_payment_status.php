<?php
// Initialize the session
session_start();

require_once "config.php";

if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    $id = $_SESSION["id"];

    // Query the database to check the payment_status for the current user
    $sql = "SELECT payment_status FROM users WHERE id = ?";
    
    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $id);

        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_store_result($stmt);

            if (mysqli_stmt_num_rows($stmt) == 1) {
                mysqli_stmt_bind_result($stmt, $payment_status);
                mysqli_stmt_fetch($stmt);

                if ($payment_status !== "success") {
                    // Payment status is not 'success', redirect to payment page
                    header("location: payment.php");
                    exit();
                }
            } else {
                // User not found in the database, handle as needed
                header("location: login.php"); // Redirect to login page, for example
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
?>