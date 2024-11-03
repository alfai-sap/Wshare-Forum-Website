<?php
session_start();
require_once 'functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['username'])) {
        echo 'Please login to reply.';
        exit;
    }

    $comment_id = $_POST['comment_id'];
    $content = $_POST['content'];
    $user_id = $_SESSION['user_id'];

    $result = insertReply($comment_id, $user_id, $content);

    if ($result) {
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit();
    } else {
        echo 'Failed to submit reply.';
    }
}
?>
