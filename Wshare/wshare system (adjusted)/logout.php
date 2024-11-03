<?php
session_start();
require_once 'db_connection.php';
require_once 'functions.php';

// Check if the user is logged in (session contains user_id)
if (isset($_SESSION['user_id'])) {
    // Log the user's logout time before session destruction
    logUserLogout($_SESSION['user_id']);
}

// Destroy the session
session_unset();    // Unset all session variables
session_destroy();  // Destroy the session

// Redirect to the login page
header("Location: login.php");
exit;
?>
