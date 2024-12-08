<?php
require_once 'functions.php';
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $title = $_POST['title'];
    $content = $_POST['content'];
    $selectedTags = isset($_POST['selected_tags']) ? $_POST['selected_tags'] : '';

    // Get the user ID from the session - Fix for missing userID
    $query = "SELECT UserID FROM users WHERE Username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $_SESSION['username']);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $userID = $user['UserID'] ?? null;

    if (!$userID) {
        echo "Invalid or missing userID";
        exit;
    }

    $_SESSION['user_id'] = $userID; // Store for future use

    // Set initial photo path as null
    $photoPath = null;

    // Handle single photo upload
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/';
        $photoName = uniqid() . '_' . basename($_FILES['photo']['name']);
        $photoPath = $uploadDir . $photoName;
        
        if (move_uploaded_file($_FILES['photo']['tmp_name'], $photoPath)) {
            $photoPath = $photoPath;
        }
    }

    // Use your createPost function to insert post
    $postCreated = createPost($title, $content, $_SESSION['username'], $photoPath);
    
    if ($postCreated) {
        $postID = $conn->insert_id;

        // Handle additional photo uploads with order
        if (isset($_FILES['additional_photos']) && !empty($_FILES['additional_photos']['name'][0])) {
            $uploadDir = 'uploads/';
            $imageOrder = isset($_POST['image_order']) ? (is_array($_POST['image_order']) ? $_POST['image_order'] : explode(',', $_POST['image_order'])) : [];
            $uploadedFiles = [];

            // Process file uploads
            foreach($_FILES['additional_photos']['tmp_name'] as $key => $tmp_name) {
                if ($_FILES['additional_photos']['error'][$key] === UPLOAD_ERR_OK) {
                    $photoName = uniqid() . '_' . basename($_FILES['additional_photos']['name'][$key]);
                    $photoPath = $uploadDir . $photoName;
                    
                    if (move_uploaded_file($tmp_name, $photoPath)) {
                        $uploadedFiles[$photoName] = $photoPath;
                    }
                }
            }

            // Insert images in order
            if (!empty($imageOrder)) {
                foreach($imageOrder as $index => $fileName) {
                    foreach($uploadedFiles as $uploadedFileName => $uploadedFilePath) {
                        if (strpos($uploadedFileName, $fileName) !== false) {
                            $photoPath = $uploadedFilePath;
                            $sql = "INSERT INTO post_images (PostID, ImagePath, DisplayOrder) VALUES (?, ?, ?)";
                            $stmt = $conn->prepare($sql);
                            $stmt->bind_param('isi', $postID, $photoPath, $index);
                            $stmt->execute();
                        }
                    }
                }
            } else {
                // If no order specified, insert in uploaded order
                $index = 0;
                foreach($uploadedFiles as $photoName => $photoPath) {
                    $sql = "INSERT INTO post_images (PostID, ImagePath, DisplayOrder) VALUES (?, ?, ?)";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param('isi', $postID, $photoPath, $index);
                    $stmt->execute();
                    $index++;
                }
            }
        }

        // Process tags
        if (!empty($selectedTags)) {
            $tagsArray = explode(',', $selectedTags);
            $stmt = $conn->prepare("INSERT INTO post_tags (PostID, TagID) VALUES (?, (SELECT TagID FROM tags WHERE TagName = ?))");
            
            foreach ($tagsArray as $tagName) {
                $stmt->bind_param('is', $postID, $tagName);
                $stmt->execute();
            }
            $stmt->close();
        }

        header('Location: homepage.php');
        exit;
    } else {
        echo "Error creating the post.";
    }
}
?>
