<?php
require_once 'functions.php';
session_start();

if (isset($_GET['community_id']) && isset($_GET['role'])) {
    $communityID = $_GET['community_id'];
    $role = $_GET['role'];

    if ($role === 'members') {
        $query = "SELECT u.UserID, u.Username, up.ProfilePic 
                  FROM community_members cm 
                  JOIN users u ON cm.UserID = u.UserID 
                  JOIN userprofiles up ON up.UserID = u.UserID 
                  WHERE cm.CommunityID = ? AND cm.Role = 'member'";
    } else if ($role === 'admins') {
        $query = "SELECT u.UserID, u.Username, up.ProfilePic 
                  FROM community_members cm 
                  JOIN users u ON cm.UserID = u.UserID 
                  JOIN userprofiles up ON up.UserID = u.UserID 
                  WHERE cm.CommunityID = ? AND cm.Role = 'admin'";
    }

    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $communityID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<div>';
            echo '<img src="' . htmlspecialchars($row['ProfilePic'] ?? 'default_pic.svg') . '" alt="Profile Picture" style="width:50px;height:50px;border-radius:50%;">';
            echo '<span>' . htmlspecialchars($row['Username']) . '</span>';
            echo '</div>';
        }
    } else {
        echo '<p>No ' . $role . ' found.</p>';
    }
} else {
    echo '<p>Invalid request.</p>';
}
