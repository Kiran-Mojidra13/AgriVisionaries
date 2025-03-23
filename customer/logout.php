<?php
session_start();

// Destroy the session and unset all session variables
session_unset();
session_destroy();

// Redirect to the login page
header("Location: cust_login.php");
exit();
?>
