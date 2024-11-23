<?php
require_once 'functions.php';

function addNotification($userID, $type, $content, $referenceID = null) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO notifications (UserID, Type, Content, ReferenceID, Seen, CreatedAt) VALUES (?, ?, ?, ?, 0, NOW())");
    $stmt->bind_param("issi", $userID, $type, $content, $referenceID);
    $stmt->execute();
    $stmt->close();
}

function trackPostLikeNotif($postID, $userID) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO likes (PostID, UserID, LikedAt) VALUES (?, ?, NOW())");
    $stmt->bind_param("ii", $postID, $userID);
    $stmt->execute();
    $stmt->close();
    
    // Also track the like in post_activity
    $stmt = $conn->prepare("INSERT INTO post_activity (PostID, ActivityType) VALUES (?, 'like')");
    $stmt->bind_param("i", $postID);
    $stmt->execute();
    $stmt->close();

    // Add notification for the post owner
    $postOwnerID = getPostOwner($postID);
    $content = "Your post was liked.";
    addNotification($postOwnerID, 'like', $content, $postID);
}

function trackPostCommentNotif($postID, $userID) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO post_activity (PostID, ActivityType) VALUES (?, 'comment')");
    $stmt->bind_param("i", $postID);
    $stmt->execute();
    $stmt->close();
    
    // Also track the comment in comments table
    $stmt = $conn->prepare("INSERT INTO comments (PostID, UserID, Content, CreatedAt) VALUES (?, ?, 'Comment content here', NOW())");
    $stmt->bind_param("ii", $postID, $userID);
    $stmt->execute();
    $stmt->close();

    // Add notification for the post owner
    $postOwnerID = getPostOwner($postID);
    $content = "Your post received a new comment.";
    addNotification($postOwnerID, 'comment', $content, $postID);
}

function followUserNotif($followerID, $followingID) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO follows (FollowerID, FollowingID) VALUES (?, ?)");
    if ($stmt) {
        $stmt->bind_param("ii", $followerID, $followingID);
        $stmt->execute();
        $stmt->close();

        // Add notification for the followed user
        $content = "You have a new follower.";
        addNotification($followingID, 'follow', $content, $followerID);

        return true;
    } else {
        // Handle error
        return false;
    }
}

function getPostOwner($postID) {
    global $conn;
    $stmt = $conn->prepare("SELECT UserID FROM posts WHERE PostID = ?");
    $stmt->bind_param("i", $postID);
    $stmt->execute();
    $stmt->bind_result($userID);
    $stmt->fetch();
    $stmt->close();
    return $userID;
}