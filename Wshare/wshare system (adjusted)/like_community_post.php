<?php
// Start session to check user login
session_start();
require_once 'db_connection.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get the UserID and PostID
$userID = $_SESSION['user_id'];
$postID = $_POST['postID'] ?? null;

if ($postID) {
    // Check if the user has already liked this post
    $query = "SELECT * FROM community_likes WHERE PostID = ? AND UserID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $postID, $userID);
    $stmt->execute();
    $result = $stmt->get_result();
    $likeExists = $result->fetch_assoc();
    $stmt->close(); // Close the statement to avoid sync issues

    if ($likeExists) {
        // If the like exists, delete it to "unlike"
        $deleteQuery = "DELETE FROM community_likes WHERE PostID = ? AND UserID = ?";
        $deleteStmt = $conn->prepare($deleteQuery);
        $deleteStmt->bind_param("ii", $postID, $userID);
        $deleteStmt->execute();
        $deleteStmt->close(); // Close the statement
    } else {
        // If the like does not exist, add it to "like"
        $insertQuery = "INSERT INTO community_likes (PostID, UserID, LikedAt) VALUES (?, ?, NOW())";
        $insertStmt = $conn->prepare($insertQuery);
        $insertStmt->bind_param("ii", $postID, $userID);
        $insertStmt->execute();
        $insertStmt->close(); // Close the statement
    }
}

// Redirect back to the previous page or a specific page
header('Location: ' . $_SERVER['HTTP_REFERER']);
exit();
?>
