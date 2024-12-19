<?php
require_once 'functions.php';
session_start();

// Check for file upload errors first
if (empty($_POST) && isset($_SERVER['CONTENT_LENGTH']) && $_SERVER['CONTENT_LENGTH'] > 0) {
    die("Error: The uploaded files exceed the maximum allowed size. Please reduce file sizes or upload fewer files.");
}

// Ensure the user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get and validate user ID first
    $query = "SELECT UserID FROM users WHERE Username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $_SESSION['username']);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $userID = $user['UserID'] ?? null;

    if (!$userID) {
        die("Error: Invalid or missing userID");
    }

    // Validate required fields
    if (empty($_POST['title']) || empty($_POST['content'])) {
        die("Error: Title and content are required fields");
    }

    $title = trim($_POST['title']);
    $content = trim($_POST['content']);

    // Validate file uploads before processing
    $maxSingleFileSize = 100 * 1024 * 1024; // 100MB
    $maxTotalSize = 150 * 1024 * 1024; // 150MB
    $totalSize = 0;

    // Function to check file size
    function validateFileSize($files) {
        global $maxSingleFileSize, $totalSize;
        if (empty($files['name'][0])) return true;
        
        foreach ($files['size'] as $size) {
            if ($size > $maxSingleFileSize) {
                die("Error: Individual file size cannot exceed 100MB");
            }
            $totalSize += $size;
        }
        return true;
    }

    // Validate all file uploads
    validateFileSize($_FILES['photo'] ?? ['name' => [], 'size' => []]);
    validateFileSize($_FILES['additional_photos'] ?? ['name' => [], 'size' => []]);
    validateFileSize($_FILES['documents'] ?? ['name' => [], 'size' => []]);
    validateFileSize($_FILES['videos'] ?? ['name' => [], 'size' => []]);

    if ($totalSize > $maxTotalSize) {
        die("Error: Total upload size cannot exceed 150MB");
    }

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

        // Handle document uploads
        if (!empty($_FILES['documents']['name'][0])) {
            $uploadedDocuments = uploadDocuments($_FILES['documents']);
            foreach ($uploadedDocuments as $documentPath) {
                $sql = "INSERT INTO post_documents (PostID, DocumentPath) VALUES (?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('is', $postID, $documentPath);
                $stmt->execute();
            }
        }

        // Handle video uploads
        if (isset($_FILES['videos']) && !empty($_FILES['videos']['name'][0])) {
            $uploadDir = 'uploads/videos/';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $maxSize = 100 * 1024 * 1024; // 100MB
            
            foreach($_FILES['videos']['tmp_name'] as $key => $tmp_name) {
                if ($_FILES['videos']['error'][$key] === UPLOAD_ERR_OK) {
                    if ($_FILES['videos']['size'][$key] <= $maxSize) {
                        $videoName = uniqid() . '_' . basename($_FILES['videos']['name'][$key]);
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
