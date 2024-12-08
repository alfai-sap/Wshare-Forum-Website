<?php
require_once 'functions.php';

session_start();

if (!isset($_SESSION['username']) || !isset($_POST['post_id']) || !isset($_POST['content'])) {
    die(json_encode(['success' => false]));
}

$postId = intval($_POST['post_id']);
$content = $_POST['content'];
$userId = getUserIdByUsername($_SESSION['username']);

if (createComment($userId, $postId, $content)) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}
?>