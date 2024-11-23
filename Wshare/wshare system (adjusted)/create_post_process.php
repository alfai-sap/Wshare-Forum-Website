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
    $photoPath = null;

    // Handle file upload if a photo is provided
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $photoPath = 'uploads/' . basename($_FILES['photo']['name']);
        move_uploaded_file($_FILES['photo']['tmp_name'], $photoPath);
    }

    // Insert the post into the database
    $userID = $_SESSION['userID']; // Assuming you store userID in the session

    // Use your createPost function to insert post
    $postCreated = createPost($title, $content, $_SESSION['username'], $photoPath);
    
    if ($postCreated) {
        // Get the last inserted post ID
        $postID = $conn->insert_id;

        // Process tags (comma-separated values)
        if (!empty($selectedTags)) {
            $tagsArray = explode(',', $selectedTags);

            // Insert each tag relation to the post_tags table
            $stmt = $conn->prepare("INSERT INTO post_tags (PostID, TagID) VALUES (?, ?)");
            foreach ($tagsArray as $tagName) {
                // Fetch the TagID for each tag name
                $tagQuery = "SELECT TagID FROM tags WHERE TagName = ?";
                $tagStmt = $conn->prepare($tagQuery);
                $tagStmt->bind_param('s', $tagName);
                $tagStmt->execute();
                $tagStmt->bind_result($tagID);
                $tagStmt->fetch();
                $tagStmt->close();

                if ($tagID) {
                    // Bind and execute the insert for post_tags
                    $stmt->bind_param('ii', $postID, $tagID);
                    $stmt->execute();
                }
            }
            $stmt->close();
        }

        // Redirect to homepage after successful post creation
        header('Location: homepage.php');
        exit;
    } else {
        echo "Error creating the post.";
    }
}
?>
