<?php
// community_manage_member.php

require_once 'functions.php'; // Assuming this file contains your database connection and helper functions

// Start the session and check if the user is logged in
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$userID = $_SESSION['user_id'];
$communityID = $_POST['community_id'];
$memberID = $_POST['member_id'];
$action = $_POST['action'];

if (empty($communityID) || empty($memberID) || empty($action)) {
    echo "Invalid request. Missing required parameters.";
    exit;
}

// Get the current user's role in the community
$currentUserRole = getUserRoleInCommunity($communityID, $userID);

// Ensure the current user is an admin in the community
if ($currentUserRole !== 'admin') {
    echo "You do not have permission to perform this action.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $communityID = isset($_POST['community_id']) ? intval($_POST['community_id']) : 0;
    $memberID = isset($_POST['member_id']) ? intval($_POST['member_id']) : 0;
    $action = isset($_POST['action']) ? $_POST['action'] : '';

    if ($communityID > 0 && $memberID > 0) {
        // Leave Admin Role action
        if ($action === 'leave_admin_role') {
            // Ensure the current user is the one trying to leave the admin role
            if ($memberID == $_SESSION['user_id']) {
                $query = "UPDATE community_members SET Role = 'member' WHERE CommunityID = ? AND UserID = ?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param('ii', $communityID, $memberID);
                if ($stmt->execute()) {
                    // Redirect to the community page after role change
                    header("Location: community_page.php?community_id=$communityID");
                    exit();
                } else {
                    echo "Error updating role.";
                }
            } else {
                echo "Unauthorized action.";
            }
        }
        // Handle other actions like remove_member or set_admin if necessary...
    }
}

// Handle the different actions (set as admin, remove member)
switch ($action) {
    case 'set_admin':
        // Check if the member is already an admin
        $memberRole = getUserRoleInCommunity($communityID, $memberID);
        if ($memberRole === 'admin') {
            echo "This user is already an admin.";
            exit;
        }

        // Update the member's role to admin
        $result = setUserRoleInCommunity($communityID, $memberID, 'admin');
        if ($result) {
            echo "User promoted to admin successfully.";
        } else {
            echo "Failed to promote user to admin.";
        }
        break;

    case 'remove_member':
        // Ensure the admin is not trying to remove themselves
        if ($memberID == $userID) {
            echo "You cannot remove yourself from the community.";
            exit;
        }

        // Remove the member from the community
        $result = removeMemberFromCommunity($communityID, $memberID);
        if ($result) {
            echo "Member removed from the community successfully.";
        } else {
            echo "Failed to remove member from the community.";
        }
        break;

    default:
        echo "Invalid action.";
        exit;
}

// Redirect back to the community page
header("Location: community_page.php?community_id=$communityID");
exit;

// Function to get the user's role in the community
function getUserRoleInCommunity($communityID, $userID) {
    global $conn;
    $stmt = $conn->prepare("SELECT Role FROM community_members WHERE CommunityID = ? AND UserID = ?");
    $stmt->bind_param('ii', $communityID, $userID);
    $stmt->execute();
    $stmt->bind_result($role);
    $stmt->fetch();
    $stmt->close();
    
    return $role;
}

// Function to set the user's role in the community
function setUserRoleInCommunity($communityID, $userID, $role) {
    global $conn;
    $stmt = $conn->prepare("UPDATE community_members SET Role = ? WHERE CommunityID = ? AND UserID = ?");
    $stmt->bind_param('sii', $role, $communityID, $userID);
    return $stmt->execute();
}

// Function to remove a member from the community
function removeMemberFromCommunity($communityID, $userID) {
    global $conn;
    $stmt = $conn->prepare("DELETE FROM community_members WHERE CommunityID = ? AND UserID = ?");
    $stmt->bind_param('ii', $communityID, $userID);
    return $stmt->execute();
}
?>
