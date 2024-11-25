<?php
require_once 'functions.php';
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Get user ID from session
$username = $_SESSION['username'];
$userID = getUserIdByUsername($username);

// Get community ID and post details from the form
$communityID = isset($_POST['community_id']) ? intval($_POST['community_id']) : 0;
$title = isset($_POST['title']) ? trim($_POST['title']) : '';
$content = isset($_POST['content']) ? trim($_POST['content']) : '';
$tags = isset($_POST['selected_tags']) ? trim($_POST['selected_tags']) : ''; // Added to handle tags

// Validate inputs
if (empty($title) || empty($content)) {
    echo 'Title and content are required.';
    exit;
}

// Handling file upload
$photoPath = null;
if (isset($_FILES['photo']) && $_FILES['photo']['error'] == UPLOAD_ERR_OK) {
    $targetDir = 'uploads/'; // Define the directory to save uploaded images
    $fileName = basename($_FILES['photo']['name']);
    $targetFilePath = $targetDir . uniqid() . '_' . $fileName; // Unique file name to prevent collisions

    // Move the uploaded file to the target directory
    if (move_uploaded_file($_FILES['photo']['tmp_name'], $targetFilePath)) {
        $photoPath = $targetFilePath; // Store the path to the uploaded image
    } else {
        echo 'Error uploading the image.';
        exit;
    }
}

// Insert the new post into the database (including the photo path if uploaded)
$query = "INSERT INTO community_posts (CommunityID, UserID, Title, Content, PhotoPath, CreatedAt) VALUES (?, ?, ?, ?, ?, NOW())";
$stmt = $conn->prepare($query);
$stmt->bind_param('iisss', $communityID, $userID, $title, $content, $photoPath);

if ($stmt->execute()) {
    $postID = $stmt->insert_id; // Get the ID of the newly created post

    // Handle tags if any are provided
    if (!empty($tags)) {
        $tagsArray = array_map('trim', explode(',', $tags)); // Split tags by commas and trim whitespace

        foreach ($tagsArray as $tag) {
            if (!empty($tag)) {
                // Check if the tag already exists in the database
                $tagQuery = "SELECT TagID FROM tags WHERE TagName = ?";
                $tagStmt = $conn->prepare($tagQuery);
                $tagStmt->bind_param('s', $tag);
                $tagStmt->execute();
                $tagStmt->store_result();

                if ($tagStmt->num_rows > 0) {
                    // Tag exists, get its ID
                    $tagStmt->bind_result($tagID);
                    $tagStmt->fetch();
                } else {
                    // Tag does not exist, insert a new one
                    $insertTagQuery = "INSERT INTO tags (TagName) VALUES (?)";
                    $insertTagStmt = $conn->prepare($insertTagQuery);
                    $insertTagStmt->bind_param('s', $tag);
                    $insertTagStmt->execute();
                    $tagID = $insertTagStmt->insert_id; // Get the ID of the newly inserted tag
                }

                // Associate the tag with the post
                $postTagQuery = "INSERT INTO community_post_tags (PostID, TagID) VALUES (?, ?)";
                $postTagStmt = $conn->prepare($postTagQuery);
                $postTagStmt->bind_param('ii', $postID, $tagID);
                $postTagStmt->execute();
            }
        }
    }

    // Redirect to the community page
    header("Location: community_page.php?community_id=" . $communityID);
    exit;
} else {
    echo 'Error creating post: ' . $stmt->error;
}
?>
