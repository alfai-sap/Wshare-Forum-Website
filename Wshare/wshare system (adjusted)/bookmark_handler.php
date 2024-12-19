<?php
require_once 'bookmark_functions.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userID = $_SESSION['user_id'];
    $postID = $_POST['post_id'];
    $action = $_POST['action'];
    $postType = $_POST['post_type'] ?? 'general'; // Default to 'general' if not provided
    
    switch ($action) {
        case 'add':
            if ($postType === 'community') {
                $label = $_POST['label'] ?? null;
                $notes = $_POST['notes'] ?? null;
                $success = addCommunityBookmark($userID, $postID, $label, $notes);
                $message = $success ? 'Community bookmark added successfully.' : 'Failed to add community bookmark.';
            } else {
                $label = $_POST['label'] ?? null;
                $notes = $_POST['notes'] ?? null;
                $success = addBookmark($userID, $postID, $label, $notes);
                $message = $success ? 'Bookmark added successfully.' : 'Failed to add bookmark.';
            }
            break;
            
        case 'remove':
            if ($postType === 'community') {
                $success = removeCommunityBookmark($userID, $postID);
                $message = $success ? 'Community bookmark removed successfully.' : 'Failed to remove community bookmark.';
            } else {
                $success = removeBookmark($userID, $postID);
                $message = $success ? 'Bookmark removed successfully.' : 'Failed to remove bookmark.';
            }
            break;

        case 'update_label':
            if ($postType === 'community') {
                $bookmarkID = $_POST['bookmark_id'];
                $newLabel = $_POST['label'];
                $success = updateCommunityBookmarkLabel($bookmarkID, $userID, $newLabel);
                $message = $success ? 'Community label updated successfully.' : 'Failed to update community label.';
            } else {
                $bookmarkID = $_POST['bookmark_id'];
                $newLabel = $_POST['label'];
                $success = updateBookmarkLabel($bookmarkID, $userID, $newLabel);
                $message = $success ? 'Label updated successfully.' : 'Failed to update label.';
            }
            break;
            
        case 'update_notes':
            if ($postType === 'community') {
                $bookmarkID = $_POST['bookmark_id'];
                $notes = $_POST['notes'];
                $success = updateCommunityBookmarkNotes($bookmarkID, $userID, $notes);
                $message = $success ? 'Community notes updated successfully.' : 'Failed to update community notes.';
            } else {
                $bookmarkID = $_POST['bookmark_id'];
                $notes = $_POST['notes'];
                $success = updateBookmarkNotes($bookmarkID, $userID, $notes);
                $message = $success ? 'Notes updated successfully.' : 'Failed to update notes.';
            }
            break;

        case 'create_label':
            if ($postType === 'community') {
                $label = $_POST['label'];
                $success = createCommunityBookmarkLabel($userID, $label);
                $message = $success ? 'Community label created successfully.' : 'Failed to create community label.';
            } else {
                $label = $_POST['label'];
                $success = createBookmarkLabel($userID, $label);
                $message = $success ? 'Label created successfully.' : 'Failed to create label.';
            }
            break;
            
        default:
            $success = false;
            $message = 'Invalid action.';
    }

    if ($success) {
        $_SESSION['success_message'] = $message;
    } else {
        $_SESSION['error_message'] = $message;
    }

    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}
?>