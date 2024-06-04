<?php
session_start();

if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    // User is logged in
    $id = $_SESSION["id"];

    // Include your database configuration file
    require_once "config.php";

    // Update the payment_status and payment_time to "expired"
    $payment_status = null;
    $payment_time = null;

    $sql = "UPDATE users SET payment_status = ?, payment_time = ? WHERE id = ?";

    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "ssi", $payment_status, $payment_time, $id);

        if (mysqli_stmt_execute($stmt)) {
            // Payment status and time updated to "expired" successfully
            header("location: payment.php");
            exit();
        } else {
            //echo "Error updating payment information: " . mysqli_error($link);
        }

        mysqli_stmt_close($stmt);
    } else {
        //echo "Error in prepared statement: " . mysqli_error($link);
    }

    // Close the database connection
    mysqli_close($link);
} else {
    // User is not logged in, handle as needed
    header("location: login.php"); // Redirect to login page, for example
    exit();
}
?>
