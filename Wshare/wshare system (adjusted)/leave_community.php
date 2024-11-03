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

// Get community ID from the query string
if (!isset($_GET['community_id']) || !is_numeric($_GET['community_id'])) {
    header('Location: Communities.php'); // Redirect to the communities page if no valid community ID is provided
    exit;
}

$communityID = (int)$_GET['community_id'];

// Delete the user from the community
$query = "DELETE FROM community_members WHERE CommunityID = ? AND UserID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('ii', $communityID, $userID);
$stmt->execute();

// Redirect back to the communities page
header('Location: Communities.php');
exit;
