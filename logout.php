<?php
session_start();  // Start the session

// Destroy all session data
session_destroy();

// Redirect to the login page
header("Location: customer_login.php");
exit;
?>
