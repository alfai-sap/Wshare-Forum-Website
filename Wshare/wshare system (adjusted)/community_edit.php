<?php
require_once 'functions.php';
session_start();

$communityID = isset($_GET['community_id']) ? intval($_GET['community_id']) : 0;
$userID = isset($_SESSION['user_id']) ? intval($_SESSION['user_id']) : 0;

// Check if the user is logged in
if ($userID === 0) {
    echo "You must be logged in to edit this community.";
    exit();
}

// Check if the user is an admin of the community
$query = "SELECT Role FROM community_members WHERE CommunityID = ? AND UserID = ? AND Role = 'admin'";
$stmt = $conn->prepare($query);
$stmt->bind_param('ii', $communityID, $userID);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "You are not authorized to edit this community.";
    exit();
}

// Fetch existing community details
$query = "SELECT * FROM communities WHERE CommunityID = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $communityID);
$stmt->execute();
$communityResult = $stmt->get_result();
$community = $communityResult->fetch_assoc();

if (!$community) {
    echo "Community not found.";
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newTitle = isset($_POST['title']) ? trim($_POST['title']) : '';
    $newDescription = isset($_POST['description']) ? trim($_POST['description']) : '';
    $newVisibility = isset($_POST['visibility']) ? $_POST['visibility'] : 'public';

    // Sanitize inputs
    if (empty($newTitle) || empty($newDescription)) {
        echo "Title and description are required.";
        exit();
    }

    // Handle thumbnail upload
    $newThumbnail = $community['Thumbnail']; // Default to the existing thumbnail if no new one is uploaded
    if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === UPLOAD_ERR_OK) {
        $targetDir = "uploads/"; // Specify the upload directory
        $targetFile = $targetDir . basename($_FILES["thumbnail"]["name"]);
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        // Check if the file is an image
        if (getimagesize($_FILES["thumbnail"]["tmp_name"]) === false) {
            echo "The file is not an image.";
            exit();
        }

        // Check file size (limit to 2MB for example)
        if ($_FILES["thumbnail"]["size"] > 2097152) {
            echo "The file is too large.";
            exit();
        }

        // Allow certain file formats
        $allowedTypes = ["jpg", "png", "jpeg", "gif"];
        if (!in_array($imageFileType, $allowedTypes)) {
            echo "Only JPG, JPEG, PNG, and GIF files are allowed.";
            exit();
        }

        // Attempt to move the uploaded file
        if (move_uploaded_file($_FILES["thumbnail"]["tmp_name"], $targetFile)) {
            $newThumbnail = $targetFile; // Update the thumbnail path
        } else {
            echo "Sorry, there was an error uploading your file.";
            exit();
        }
    }

    // Update the community details in the database
    $updateQuery = "UPDATE communities SET Title = ?, Description = ?, Visibility = ?, Thumbnail = ? WHERE CommunityID = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param('ssssi', $newTitle, $newDescription, $newVisibility, $newThumbnail, $communityID);

    if ($stmt->execute()) {
        // Redirect back to the community page after successful update
        header('Location: community_page.php?community_id=' . $communityID);
        exit();
    } else {
        echo "Error updating community details.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Community</title>
    <link rel="stylesheet" href="./css/navbar.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="./css/left-navbar.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="./css/community_edit.css?v=<?php echo time(); ?>">

</head>
<body>

    <?php include 'navbar.php'; ?>

    <div class="container">
        <h1>Edit Community: <?php echo htmlspecialchars($community['Title']); ?></h1>

        <form method="POST" action="community_edit.php?community_id=<?php echo $communityID; ?>" enctype="multipart/form-data">
            <!-- Community Title input -->
            <label for="community_title">Community Title:</label>
            <input type="text" id="community_title" name="title" value="<?php echo htmlspecialchars($community['Title']); ?>" required>

            <!-- Community Description textarea -->
            <label for="community_description">Community Description:</label>
            <textarea id="community_description" name="description" required><?php echo htmlspecialchars($community['Description']); ?></textarea>

            <!-- Community Thumbnail upload input -->
            <label for="community_thumbnail">Community Thumbnail:</label>
            <input type="file" id="community_thumbnail" name="thumbnail">

            <!-- Privacy settings dropdown -->
            <label for="privacy">Privacy:</label>
            <select id="privacy" name="visibility">
                <option value="public" <?php echo $community['Visibility'] === 'public' ? 'selected' : ''; ?>>Public</option>
                <option value="private" <?php echo $community['Visibility'] === 'private' ? 'selected' : ''; ?>>Private</option>
            </select>

            <!-- Submit button for saving changes -->
            <input type="submit" value="Save Changes">
        </form>

        <br>
        <a class = "backButton" href="community_page.php?community_id=<?php echo $communityID; ?>">Back to Community</a>
    </div>

</body>
</html>
