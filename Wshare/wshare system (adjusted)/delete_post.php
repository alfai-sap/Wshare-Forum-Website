<?php
require_once 'functions.php';
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Check if post ID is provided
if (!isset($_GET['id'])) {
    header('Location: user_profile.php');
    exit;
}

// Get the post ID from the URL parameter
$postId = $_GET['id'];

// Get the current user's ID
$username = $_SESSION['username'];
$userID = getUserIdByUsername($username);

// Attempt to delete the post
if (deletePost($postId, $userID)) {
    header('Location: user_profile.php');
    exit;
} else {
    echo "Error deleting post.";
}
?>
