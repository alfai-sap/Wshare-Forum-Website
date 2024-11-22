<?php
require_once 'functions.php'; // Assuming this file handles the database connection
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php'); // Redirect to login page if not logged in
    exit;
}

// Fetch user ID
$username = $_SESSION['username'];
$userID = getUserIdByUsername($username); // You should define this function in `functions.php`

// Get the community ID from the URL
if (!isset($_GET['community_id'])) {
    header('Location: communities.php'); // Redirect to communities page if no community ID is provided
    exit;
}
$communityID = $_GET['community_id'];

// Check if the community exists and its visibility status
$query = "SELECT Visibility FROM communities WHERE CommunityID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $communityID);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($visibility);
$stmt->fetch();
$stmt->close();

// If the community is private, add the user to the join requests table
if ($visibility === 'private') {
    // Check if the user has already requested to join this community
    $checkQuery = "SELECT COUNT(*) FROM community_join_requests WHERE CommunityID = ? AND UserID = ?";
    $checkStmt = $conn->prepare($checkQuery);
    $checkStmt->bind_param('ii', $communityID, $userID);
    $checkStmt->execute();
    $checkStmt->bind_result($existingRequest);
    $checkStmt->fetch();
    $checkStmt->close();

    if ($existingRequest > 0) {
        echo "Your join request is already pending.";
    } else {
        // Add a new join request
        $insertQuery = "INSERT INTO community_join_requests (CommunityID, UserID) VALUES (?, ?)";
        $insertStmt = $conn->prepare($insertQuery);
        $insertStmt->bind_param('ii', $communityID, $userID);
        $insertStmt->execute();
        $insertStmt->close();
        header('Location: Communities.php');
    }
} else {
    // If the community is public, add the user as a member directly
    $joinQuery = "INSERT INTO community_members (CommunityID, UserID, Role) VALUES (?, ?, 'member')";
    $joinStmt = $conn->prepare($joinQuery);
    $joinStmt->bind_param('ii', $communityID, $userID);
    $joinStmt->execute();
    $joinStmt->close();

    header('Location: community_page.php?community_id='.$communityID);
    exit;
}
?>
