<?php
require_once 'functions.php';
session_start();

if (isset($_SESSION['username'])) {   
    $content = $_POST['content'];
    $post_id = $_POST['post_id']; 
    $username = $_SESSION['username'];

    // Insert the comment
    if (createCommunityComment(getUserIdByUsername($username), $post_id, $content)) {
        // Redirect the user back to the view_post.php page with the post ID
        header('Location: view_community_post.php?id=' . $post_id);
        
        exit;
    } else {
        echo "Error posting comment.";
    }
} else {
    header('Location: login.php');
    exit;
}
?>