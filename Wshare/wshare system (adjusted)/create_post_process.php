<?php
require_once 'functions.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['username'])) {
    // Check if the required form fields are set
    if (isset($_POST['title'], $_POST['content'])) {
        $title = $_POST['title'];
        $content = $_POST['content'];
        $username = $_SESSION['username'];

        // Handle file upload
        $photoPath = null; // Default to no photo
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            $photo = $_FILES['photo'];
            $targetDir = "post_img/"; // Define where to save the images
            $targetFile = $targetDir . basename($photo["name"]);
            $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
            $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

            // Validate file type
            if (in_array($imageFileType, $allowedTypes)) {
                // Move the file to the uploads directory
                if (move_uploaded_file($photo["tmp_name"], $targetFile)) {
                    $photoPath = $targetFile; // Save the path to the photo
                } else {
                    echo "Error uploading photo.";
                    exit;
                }
            } else {
                echo "Invalid file type. Only JPG, JPEG, PNG, and GIF are allowed.";
                exit;
            }
        }

        if (createPost($title, $content, $username, $photoPath)) {
            header('Location: index.php'); // Redirect to forum page after successful post creation
            exit;
        } else {
            echo "Error creating post.";
        }
    } else {
        echo "Title and content fields are required."; // Error message if form fields are missing
    }
    
} else {
    // Redirect to login page if user is not logged in
    header('Location: login.php');
    exit;
}
?>
