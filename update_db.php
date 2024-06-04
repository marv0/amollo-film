<?php
session_start();

if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    // User is logged in
    $id = $_SESSION["id"];

    // Include your database configuration file
    require_once "config.php";

    // Update the payment_status and payment_time
    $payment_status = "success";
    $payment_time = date("Y-m-d H:i:s"); // Current date and time

    $sql = "UPDATE users SET payment_status = ?, payment_time = ? WHERE id = ?";

    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "ssi", $payment_status, $payment_time, $id);

        if (mysqli_stmt_execute($stmt)) {
            // Payment status and time updated successfully
            header("location: welcome.php");
            exit();
        } else {
            echo "Error updating payment information: " . mysqli_error($link);
        }

        mysqli_stmt_close($stmt);
    } else {
        echo "Error in prepared statement: " . mysqli_error($link);
    }

    // Close the database connection
    mysqli_close($link);
} else {
    // User is not logged in, handle as needed
    header("location: login.php"); // Redirect to login page, for example
    exit();
}
?>
