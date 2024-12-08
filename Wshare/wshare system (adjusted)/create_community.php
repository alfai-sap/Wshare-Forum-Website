<?php
require_once 'functions.php';
require_once 'changes.php'; // Add this line if not already present
session_start();

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

if (checkUserBan()) {
    echo "<div style='text-align: center; margin-top: 50px; color: red;'>";
    echo checkUserBan(true);
    echo "<br><a href='Communities.php'>Return to Communities</a>";
    echo "</div>";
    exit;
}

$creatorID = getUserIdByUsername($_SESSION['username']);
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $visibility = $_POST['visibility'];  // Capture the visibility setting
    
    // Handle file upload
    $thumbnail = 'defaultbg.jpg'; // Default thumbnail
    if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] == UPLOAD_ERR_OK) {
        $uploadDir = 'community_thumbs/';
        $uploadFile = $uploadDir . basename($_FILES['thumbnail']['name']);
        $fileType = strtolower(pathinfo($uploadFile, PATHINFO_EXTENSION));

        // Check file type
        $allowedTypes = array('jpg', 'jpeg', 'png', 'gif');
        if (in_array($fileType, $allowedTypes)) {
            if (move_uploaded_file($_FILES['thumbnail']['tmp_name'], $uploadFile)) {
                $thumbnail = $uploadFile;
            } else {
                echo "File upload failed.";
                exit;
            }
        } else {
            echo "Only image files are allowed.";
            exit;
        }
    }

    // Insert values into the community, including visibility
    $query = "INSERT INTO communities (CreatorID, Title, Description, Thumbnail, Visibility) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('issss', $creatorID, $title, $description, $thumbnail, $visibility);

    if ($stmt->execute()) {
        // if successful, get the community id
        $communityID = $stmt->insert_id;

        // Use the community ID to reference the community the user belongs to
        $query = "INSERT INTO community_members (CommunityID, UserID, Role) VALUES (?, ?, 'admin')";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('ii', $communityID, $creatorID);

        if ($stmt->execute()) {
            echo "Community created successfully, and you are the admin!";
            header("Location: Communities.php");
        } else {
            echo "Error adding admin: " . $stmt->error;
        }
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Community</title>
    <link rel="stylesheet" href="./css/navbar.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="./css/left-navbar.css?v=<?php echo time(); ?>">
    <style>
        body {
            font-family: Georgia, serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }
        h1 {
            color: #007BFF;
            padding: 20px;
            text-align: center;
            margin: 0;
        }
        .form-container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        label {
            display: block;
            font-size: 1em;
            margin: 10px 0 5px;
            color: #0056b3;
        }
        input[type="text"], textarea, input[type="file"], select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
        }
        textarea {
            resize: vertical;
            min-height: 100px;
        }
        input[type="submit"] {
            width: 100%;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 10px 15px;
            font-size: 1em;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
        .alert {
            color: red;
            text-align: center;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>
    <h1>Create a Community</h1>
    <div class="form-container">
        <form action="" method="POST" enctype="multipart/form-data">
            <label for="title">Community Title:</label>
            <input type="text" id="title" name="title" required>
            
            <label for="description">Description:</label>
            <textarea id="description" name="description" required></textarea>
            
            <label for="thumbnail">Thumbnail:</label>
            <input type="file" id="thumbnail" name="thumbnail" accept="image/*">

            <label for="visibility">Community Visibility:</label>
            <select id="visibility" name="visibility" required>
                <option value="public">Public</option>
                <option value="private">Private</option>
            </select>
            
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($creatorID); ?>">
            <input type="submit" value="Create">
        </form>
    </div>
</body>
</html>
