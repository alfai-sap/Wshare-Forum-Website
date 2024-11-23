<?php
require_once 'db_connection.php';
require_once 'functions.php'; 

session_start();

$id = getUserIdByUsername($_SESSION['username']);

// Main code block to handle the form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['like']) && isset($_POST['postID'])) {
        $postID = $_POST['postID'];
        // Perform the like/unlike action
        $likeCount = toggleLike($postID, $id);

        if ($likeCount !== false) {
            // Reload the current page after a successful like/unlike
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        } else {
            // Return an error message if the like action failed
            echo json_encode(['success' => false, 'message' => 'Failed to toggle like status.']);
        }
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