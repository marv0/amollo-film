<?php
// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to the login page
//if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    //header("location: login.php");
    //exit;
//}

// Output session variables to the browser's console
echo '<script>';
echo 'console.log("transactionReference: ' . $_SESSION['transactionReference'] . '");';
echo 'console.log("orderId: ' . $_SESSION['orderId'] . '");';
echo 'console.log("Session ID: ' . session_id() . '");';
echo 'console.log("Session Status: ' . session_status() . '");';
echo '</script>';