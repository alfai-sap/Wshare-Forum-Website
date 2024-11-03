<?php
require_once 'functions.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $password = $_POST['password'];
    $username = $_SESSION['username'];

    // Verify password
    if (verifyPassword($username, $password)) {
        if (isset($_POST['new_username'])) {
            // Update username
            $newUsername = $_POST['new_username'];
            updateUsername($username, $newUsername);
            $_SESSION['username'] = $newUsername; // Update session variable
            header('Location: user_profile.php');
            exit;
        } elseif (isset($_POST['new_email'])) {
            // Update email
            $newEmail = $_POST['new_email'];
            updateEmail($username, $newEmail);
            header('Location: user_profile.php');
            exit;
        }
    } else {
        echo "Incorrect password. Username or email not updated.";
    }
}
?>
