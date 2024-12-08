<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'db_connection.php';
require_once 'functions.php'; 

$id = getUserIdByUsername($_SESSION['username']);

// Main code block to handle the form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['postID']) && isset($_SESSION['user_id'])) {
        $postID = $_POST['postID'];
        $userID = $_SESSION['user_id'];

        // Toggle like status
        toggleLike($postID, $userID);

        // Refresh the current page
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit;
    } else {
        // Return an error message if post ID or like action is missing
        header('Location: login.php');
        exit;
        echo json_encode(['success' => false, 'message' => 'Missing post ID or like action.']);
    }
} else {
    header('Location: login.php');
    exit;
}
?>