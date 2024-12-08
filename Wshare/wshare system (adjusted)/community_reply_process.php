<?php
session_start();
require_once 'functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['username'])) {
        echo '<script>alert("Please login to reply.");</script>';
        exit;
    }

    $comment_id = $_POST['comment_id'];
    $content = $_POST['content'];
    $user_id = $_SESSION['user_id'];

    $result = insertCommunityReply($comment_id, $user_id, $content);

    if ($result) {
        echo '<script>alert("Reply submitted successfully."); window.location.href = "' . $_SERVER['HTTP_REFERER'] . '";</script>';
        exit();
    } else {
        echo '<script>alert("Failed to submit reply.");</script>';
    }
}
?>
