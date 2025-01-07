<?php
include 'dashFunctions.php';

if (isset($_GET['comment_id'])) {
    $commentId = $_GET['comment_id'];
    $query = "SELECT PostID FROM comments WHERE CommentID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $commentId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    header('Content-Type: application/json');
    echo json_encode(['postId' => $row['PostID'] ?? null]);
}
