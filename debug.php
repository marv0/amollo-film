<?php
// Initialize the session
session_start();

// Output session variables to the browser's console
echo '<script>';
echo 'console.log("transactionReference: ' . $_SESSION['transactionReference'] . '");';
echo 'console.log("orderId: ' . $_SESSION['orderId'] . '");';
echo 'console.log("Session ID: ' . session_id() . '");';
echo 'console.log("Session Status: ' . session_status() . '");';
echo '</script>';
?>