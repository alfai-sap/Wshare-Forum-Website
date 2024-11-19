<?php
require_once 'functions.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input to prevent XSS
    $username = htmlspecialchars(trim($_POST['username']));
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password = trim($_POST['password']);
    $confirmPassword = trim($_POST['confirmPassword']);

    // Error array to store all error messages
    $errors = [];

    // Validate email format
    if (empty($email)) {
        $errors['email'] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Invalid email format.";
    } elseif (!isEmailUnique($email)) {
        $errors['email'] = "A similar email was registered. Please try again.";
    }

    // Check if username is unique
    if (empty($username)) {
        $errors['username'] = "Username is required.";
    } elseif (!isUsernameUnique($username)) {
        $errors['username'] = "Username is already taken. Please choose a different one.";
    }

    // Validate password strength
    if (empty($password)) {
        $errors['password'] = "Password is required.";
    } elseif (!preg_match('/^(?=.*\d)(?=.*[A-Z])(?=.*[a-z])(?=.*[!@#$%^&*()_\-+=]).{8,}$/', $password)) {
        $errors['password'] = "Password must be at least 8 characters long, contain at least one number, one uppercase letter, one lowercase letter, and one special character.";
    }

    // Check if password and confirmation match
    if ($password !== $confirmPassword) {
        $errors['confirmPassword'] = "Passwords do not match.";
    }

    // If there are errors, redirect back with error messages
    if (!empty($errors)) {
        session_start();
        $_SESSION['errors'] = $errors;
        $_SESSION['old_input'] = $_POST; // Store old input to repopulate the form
        header('Location: signup.php');
        exit;
    }

    // If no errors, create user
    if (createUser($username, $email, $password)) {
        
        header('Location: login.php');
        exit;
    } else {
        // If user creation failed, add error message
        session_start();
        $_SESSION['errors'] = ["general" => "Error creating user. Please try again."];
        $_SESSION['old_input'] = $_POST; // Retain input for repopulation
        header('Location: signup.php');
        exit;
    }

    $bio = '';
    updateOrInsertUserBio($_SESSION['user_id'], $bio);
}
?>
