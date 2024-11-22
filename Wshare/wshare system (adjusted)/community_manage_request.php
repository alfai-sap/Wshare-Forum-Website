<?php
require 'functions.php'; // Adjust the path as needed

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $communityID = $_POST['community_id'];
    $userID = $_POST['user_id'];
    $action = $_POST['action'];

    if ($action === 'accept') {
        // Update the request status to 'accepted' and add to community members
        $acceptQuery = "
            UPDATE community_join_requests 
            SET status = 'accepted' 
            WHERE CommunityID = ? AND UserID = ?;";
        $stmt = $conn->prepare($acceptQuery);
        $stmt->execute([$communityID, $userID]);

        // Add to the community_members table
        $insertMemberQuery = "
            INSERT INTO community_members (CommunityID, UserID, Role)
            VALUES (?, ?, 'member')";
        $stmt = $conn->prepare($insertMemberQuery);
        $stmt->execute([$communityID, $userID]);
    } elseif ($action === 'decline') {
        // Update the request status to 'rejected'
        $declineQuery = "
            UPDATE community_join_requests 
            SET status = 'rejected' 
            WHERE CommunityID = ? AND UserID = ?";
        $stmt = $conn->prepare($declineQuery);
        $stmt->execute([$communityID, $userID]);
    }

    // Redirect to the page that shows pending requests
    header('Location: Community_page.php?community_id='.$communityID);
    exit();
}
?>
