<?php
require_once 'functions.php';
session_start();

$requestID = $_GET['request_id'];
$action = $_GET['action'];

// Approve or decline request
if ($action == 'approve') {
    // Add user to community
    $query = "INSERT INTO community_members (CommunityID, UserID, Role) 
              SELECT CommunityID, UserID, 'member' FROM community_join_requests WHERE RequestID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $requestID);
    $stmt->execute();

    // Delete request
    $query = "DELETE FROM community_join_requests WHERE RequestID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $requestID);
    $stmt->execute();

    echo "Request approved!";
} else if ($action == 'decline') {
    // Delete request
    $query = "DELETE FROM community_join_requests WHERE RequestID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $requestID);
    $stmt->execute();

    echo "Request declined!";
}

header('Location: request_page.php?community_id=' . $_GET['community_id']);
?>
