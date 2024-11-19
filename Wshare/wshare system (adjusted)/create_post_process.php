<?php
require_once 'functions.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['username'])) {
    if (isset($_POST['title'], $_POST['content'])) {
        $title = $_POST['title'];
        $content = $_POST['content'];
        $username = $_SESSION['username'];

        // Handle file upload
        $photoPath = null; // Default to no photo
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            $photo = $_FILES['photo'];
            $targetDir = "uploads/"; // Ensure this directory exists
            $targetFile = $targetDir . basename($photo["name"]);
            $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
            $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

            // Validate file type
            if (in_array($imageFileType, $allowedTypes)) {
                if (move_uploaded_file($photo["tmp_name"], $targetFile)) {
                    $photoPath = $targetFile; // Save the path to the photo
                    echo "Photo uploaded successfully. Path: " . $photoPath;
                } else {
                    echo "Failed to move the uploaded file.";
                    exit;
                }
            } else {
                echo "Invalid file type. Only JPG, JPEG, PNG, and GIF are allowed.";
                exit;
            }
        }

        // Check if createPost function is working properly
        if (createPost($title, $content, $username, $photoPath)) {
            header('Location: index.php'); // Redirect to forum page after successful post creation
            exit;
        } else {
            echo "Error creating post in the database.";
        }
    } else {
        echo "Title and content fields are required.";
    }
} else {
    header('Location: login.php');
    exit;
}

?>
