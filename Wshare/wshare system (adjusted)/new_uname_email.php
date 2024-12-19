<?php
session_start();
require_once 'functions.php';

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $oldUsername = $_SESSION['username'];
    $newUsername = $_POST['new_username'];
    $password = $_POST['password'];
    
    $result = updateUsernameWithValidation($oldUsername, $newUsername, $password);
    
    if ($result['success']) {
        $_SESSION['username'] = $newUsername;
        $_SESSION['message'] = $result['message'];
        header('Location: edit_profile.php');
        exit;
    } else {
        $_SESSION['error'] = $result['message'];
        header('Location: edit_profile.php');
        exit;
    }
}

header('Location: edit_profile.php');
exit;
