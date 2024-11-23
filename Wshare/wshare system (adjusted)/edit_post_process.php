<?php
require_once 'functions.php';
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $postID = $_POST['post_id'];
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $selectedTags = isset($_POST['selected_tags']) ? $_POST['selected_tags'] : '';
    $currentPhoto = $_POST['current_photo'];

    // Handle file upload
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == UPLOAD_ERR_OK) {
        $photo = $_FILES['photo'];
        $photoPath = 'uploads/' . basename($photo['name']);
        move_uploaded_file($photo['tmp_name'], $photoPath);
    } else {
        $photoPath = $currentPhoto; // Retain the current photo if no new photo is uploaded
    }

    // Update the post in the database
    $sql = "UPDATE posts SET Title = ?, Content = ?, PhotoPath = ? WHERE PostID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sssi', $title, $content, $photoPath, $postID);
    $stmt->execute();

    // Update the tags for the post
    $sql = "DELETE FROM post_tags WHERE PostID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $postID);
    $stmt->execute();

    if (!empty($selectedTags)) {
        $tagsArray = explode(',', $selectedTags);
        $stmt = $conn->prepare("INSERT INTO post_tags (PostID, TagID) VALUES (?, (SELECT TagID FROM tags WHERE TagName = ?))");
        foreach ($tagsArray as $tagName) {
            $stmt->bind_param('is', $postID, $tagName);
            $stmt->execute();
        }
    }

    // Redirect to the post page or another appropriate page
    header('Location: view_post.php?post_id=' . $postID);
    exit;
}
?>
