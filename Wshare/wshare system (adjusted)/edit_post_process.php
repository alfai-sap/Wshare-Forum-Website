<?php
require_once 'functions.php';
session_start();

if (!isset($_SESSION['username']) || !isset($_POST['post_id'])) {
    header('Location: login.php');
    exit;
}

$postID = $_POST['post_id'];
$title = $_POST['title'];
$content = $_POST['content'];
$selectedTags = isset($_POST['selected_tags']) ? $_POST['selected_tags'] : '';

// Handle existing images order and removal
if (isset($_POST['image_order']) && is_string($_POST['image_order'])) {
    $newOrder = array_filter(explode(',', $_POST['image_order'])); // Filter out empty values
    
    if (!empty($newOrder)) {
        // Update display order for existing images
        $updateOrder = $conn->prepare("UPDATE post_images SET DisplayOrder = ? WHERE ImageID = ? AND PostID = ?");
        foreach ($newOrder as $index => $imageId) {
            $updateOrder->bind_param('iii', $index, $imageId, $postID);
            $updateOrder->execute();
        }
    }
}

// Handle image removal
if (isset($_POST['remove_images']) && is_array($_POST['remove_images'])) {
    $removeStmt = $conn->prepare("DELETE FROM post_images WHERE ImageID = ? AND PostID = ?");
    foreach ($_POST['remove_images'] as $imageId) {
        $removeStmt->bind_param('ii', $imageId, $postID);
        $removeStmt->execute();
    }
}

// Handle main post photo upload
$photoPath = null;
if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
    $uploadDir = 'uploads/';
    $fileName = uniqid() . '_' . basename($_FILES['photo']['name']);
    $photoPath = $uploadDir . $fileName;
    
    if (move_uploaded_file($_FILES['photo']['tmp_name'], $photoPath)) {
        // Update post with new photo path
        $updatePost = $conn->prepare("UPDATE posts SET Title = ?, Content = ?, PhotoPath = ? WHERE PostID = ?");
        $updatePost->bind_param('sssi', $title, $content, $photoPath, $postID);
        $updatePost->execute();
    }
} else {
    // Update post without changing photo
    $updatePost = $conn->prepare("UPDATE posts SET Title = ?, Content = ? WHERE PostID = ?");
    $updatePost->bind_param('ssi', $title, $content, $postID);
    $updatePost->execute();
}

// Handle new photos for post_images
$uploadDir = 'uploads/';
if (isset($_FILES['new_photos']) && !empty($_FILES['new_photos']['name'][0])) {
    // Get current highest display order
    $orderQuery = $conn->prepare("SELECT COALESCE(MAX(DisplayOrder), -1) FROM post_images WHERE PostID = ?");
    $orderQuery->bind_param('i', $postID);
    $orderQuery->execute();
    $orderQuery->bind_result($maxOrder);
    $orderQuery->fetch();
    $orderQuery->close();
    
    $nextOrder = $maxOrder + 1;
    
    foreach($_FILES['new_photos']['tmp_name'] as $key => $tmp_name) {
        if ($_FILES['new_photos']['error'][$key] === UPLOAD_ERR_OK) {
            $fileName = uniqid() . '_' . basename($_FILES['new_photos']['name'][$key]);
            $photoPath = $uploadDir . $fileName;
            
            if (move_uploaded_file($tmp_name, $photoPath)) {
                $sql = "INSERT INTO post_images (PostID, ImagePath, DisplayOrder) VALUES (?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('isi', $postID, $photoPath, $nextOrder);
                $stmt->execute();
                $nextOrder++;
            }
        }
    }
}

// Handle document removal
if (isset($_POST['remove_documents']) && is_array($_POST['remove_documents'])) {
    $removeDocStmt = $conn->prepare("DELETE FROM post_documents WHERE DocumentPath = ? AND PostID = ?");
    foreach ($_POST['remove_documents'] as $documentPath) {
        $removeDocStmt->bind_param('si', $documentPath, $postID);
        $removeDocStmt->execute();
    }
}

// Handle new document uploads
if (isset($_FILES['documents']) && !empty($_FILES['documents']['name'][0])) {
    $uploadedDocuments = uploadDocuments($_FILES['documents']);
    foreach ($uploadedDocuments as $documentPath) {
        $sql = "INSERT INTO post_documents (PostID, DocumentPath) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('is', $postID, $documentPath);
        $stmt->execute();
    }
}

// Handle video removal
if (isset($_POST['remove_videos']) && is_array($_POST['remove_videos'])) {
    $removeVideoStmt = $conn->prepare("DELETE FROM post_videos WHERE VideoID = ? AND PostID = ?");
    foreach ($_POST['remove_videos'] as $videoId) {
        $removeVideoStmt->bind_param('ii', $videoId, $postID);
        $removeVideoStmt->execute();
    }
}

// Handle new video uploads
if (isset($_FILES['new_videos']) && !empty($_FILES['new_videos']['name'][0])) {
    $uploadDir = 'uploads/videos/';
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $maxSize = 100 * 1024 * 1024; // 100MB
    
    foreach($_FILES['new_videos']['tmp_name'] as $key => $tmp_name) {
        if ($_FILES['new_videos']['error'][$key] === UPLOAD_ERR_OK) {
            if ($_FILES['new_videos']['size'][$key] <= $maxSize) {
                $videoName = uniqid() . '_' . basename($_FILES['new_videos']['name'][$key]);
                $videoPath = $uploadDir . $videoName;
                
                if (move_uploaded_file($tmp_name, $videoPath)) {
                    $sql = "INSERT INTO post_videos (PostID, VideoPath) VALUES (?, ?)";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param('is', $postID, $videoPath);
                    $stmt->execute();
                }
            }
        }
    }
}

// Handle main photo removal
if (isset($_POST['remove_main_photo']) && $_POST['remove_main_photo'] == '1') {
    $updatePost = $conn->prepare("UPDATE posts SET PhotoPath = NULL WHERE PostID = ?");
    $updatePost->bind_param('i', $postID);
    $updatePost->execute();
}

// Handle tags
// First, remove existing tags
$conn->query("DELETE FROM post_tags WHERE PostID = $postID");

// Then add new tags
if (!empty($selectedTags)) {
    $tagsArray = explode(',', $selectedTags);
    $tagStmt = $conn->prepare("INSERT INTO post_tags (PostID, TagID) VALUES (?, (SELECT TagID FROM tags WHERE TagName = ?))");
    
    foreach ($tagsArray as $tagName) {
        if (!empty(trim($tagName))) {
            $tagStmt->bind_param('is', $postID, $tagName);
            $tagStmt->execute();
        }
    }
}

header('Location: view_post.php?id=' . $postID);
exit;
?>
