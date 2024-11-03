<?php
require_once 'functions.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Add validation for email format here if needed
    // You can use PHP's filter_var() function or regular expressions

    // Example of email validation using filter_var
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format";
        exit;
    }

    // Assuming createUser function inserts data into the Users table
    if (createUser($username, $email, $password)) {
        
        header('Location: login.php');

        exit;

    } else {
        echo "Error creating user. Please reload the sign in page.";
    }
}
?>
