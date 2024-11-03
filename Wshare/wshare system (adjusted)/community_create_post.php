<?php
require_once 'functions.php';
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Get user ID from session
$username = $_SESSION['username'];
$userID = getUserIdByUsername($username);

// Get community ID and post details from the form
$communityID = isset($_POST['community_id']) ? intval($_POST['community_id']) : 0;
$title = isset($_POST['title']) ? trim($_POST['title']) : '';
$content = isset($_POST['content']) ? trim($_POST['content']) : '';

// Validate inputs
if (empty($title) || empty($content)) {
    echo 'Title and content are required.';
    exit;
}

// Insert the new post into the database
$query = "INSERT INTO community_posts (CommunityID, UserID, Title, Content, CreatedAt) VALUES (?, ?, ?, ?, NOW())";
$stmt = $conn->prepare($query);
$stmt->bind_param('iiss', $communityID, $userID, $title, $content);

if ($stmt->execute()) {
    // Redirect to the community page
    header("Location: community_page.php?community_id=" . $communityID);
    exit;
} else {
    echo 'Error creating post: ' . $stmt->error;
}
?>
