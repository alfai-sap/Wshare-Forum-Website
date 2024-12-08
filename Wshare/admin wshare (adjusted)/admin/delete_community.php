
<?php
include 'dashFunctions.php'; // Include your database functions

// Get community ID from query parameter
$communityId = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($communityId) {
    // Delete the community
    if (deleteCommunity($communityId)) {
        header('Location: manage_communities.php?message=Community deleted successfully');
        exit;
    } else {
        die('Failed to delete community.');
    }
} else {
    die('Invalid community ID.');
}
?>