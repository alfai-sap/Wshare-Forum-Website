<?php
require_once 'functions.php';
session_start();

$communityID = $_GET['community_id'];
$userID = $_GET['user_id'];

// Remove member from community
$query = "DELETE FROM community_members WHERE CommunityID = ? AND UserID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('ii', $communityID, $userID);

if ($stmt->execute()) {
    echo "Member removed successfully!";
} else {
    echo "Error: " . $stmt->error;
}

header('Location: members_page.php?community_id=' . $communityID);
?>
