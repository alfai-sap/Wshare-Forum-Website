<?php

require_once 'functions.php'; // Assuming this file handles the database connection
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php'); // Redirect to login page if not logged in
    exit;
}

// Get the community ID from the URL
$communityID = isset($_GET['community_id']) ? (int)$_GET['community_id'] : 0;

// Get the user ID from the session
$userID = getUserIdByUsername($_SESSION['username']);

// Check if the community ID and user ID are valid

if ($communityID > 0 && $userID > 0) {

    // Check if member na sya daan
    $query = "SELECT * FROM community_members WHERE CommunityID = ? AND UserID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ii', $communityID, $userID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // User is already a member
        echo "You are already a member of this community.";

    } else {
        // Add the user to the community if di pa member
        $query = "INSERT INTO community_members (CommunityID, UserID, Role) VALUES (?, ?, 'member')";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('ii', $communityID, $userID);

        if ($stmt->execute()) {
            echo "You have successfully joined the community!";
            header('location: Communities.php');
        } else {
            echo "Error: " . $stmt->error;
        }
    }
} else {
    echo "Invalid community ID.";
}
?>