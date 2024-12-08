<?php
session_start();
require 'functions.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$reporterID = getUserByUsername($_SESSION['username'])['UserID'];

// Determine the context of the report (post, comment, or user)
$reportType = isset($_GET['type']) ? $_GET['type'] : null;
$targetID = isset($_GET['id']) ? intval($_GET['id']) : null;

// Validate report type
$validReportTypes = ['post', 'comment', 'user'];
if (!in_array($reportType, $validReportTypes) || !$targetID) {
    die("Invalid report type or target ID");
}

// Get target information based on report type
switch ($reportType) {
    case 'post':
        $targetInfo = getPostById($targetID);
        $pageTitle = "Report Post";
        break;
    case 'comment':
        $targetInfo = getCommentById($targetID);
        $pageTitle = "Report Comment";
        break;
    case 'user':
        $targetInfo = getUserById($targetID);
        $pageTitle = "Report User";
        break;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle file upload for evidence
    $evidencePhotoPath = null;
    if (!empty($_FILES['evidence']['name'])) {
        $uploadDir = 'uploads/reports/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        
        $fileName = uniqid() . '_' . basename($_FILES['evidence']['name']);
        $targetFilePath = $uploadDir . $fileName;
        
        if (move_uploaded_file($_FILES['evidence']['tmp_name'], $targetFilePath)) {
            $evidencePhotoPath = $targetFilePath;
        } else {
            $error = "Sorry, there was an error uploading your file.";
        }
    }

    // Get ReportedUserID based on report type
    $reportedUserID = null;
    switch($reportType) {
        case 'post':
            $userQuery = "SELECT UserID FROM posts WHERE PostID = ?";
            break;
        case 'comment':
            $userQuery = "SELECT UserID FROM comments WHERE CommentID = ?";
            break;
        case 'user':
            $reportedUserID = $targetID;
            break;
    }

    if (!$reportedUserID) {
        $stmt = $conn->prepare($userQuery);
        $stmt->bind_param("i", $targetID);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $reportedUserID = $row['UserID'];
        $stmt->close();
    }

    // Prepare report insertion
    $violation = $_POST['violation'];
    
    $query = "INSERT INTO reports (UserID, ReportedUserID, ReportType, TargetID, Violation, EvidencePhoto) 
              VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iisiss", $reporterID, $reportedUserID, $reportType, $targetID, $violation, $evidencePhotoPath);
    
    if ($stmt->execute()) {
        $_SESSION['message'] = "Report submitted successfully.";
        header("Location: view_{$reportType}.php?id={$targetID}");
        exit();
    } else {
        $error = "Error submitting the report.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
    <link rel="stylesheet" href="./css/navbar.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="./css/left-navbar.css?v=<?php echo time(); ?>">
</head>
<style>
    .container {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: calc(100vh - 80px); /* Adjust based on your navbar height */
    background-color: #f4f4f8;
    padding: 20px;
}

.report-container {
    width: 100%;
    max-width: 600px;
    background-color: white;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    padding: 30px;
    box-sizing: border-box;
}

.report-container h1 {
    color: #333;
    text-align: center;
    margin-bottom: 25px;
    font-size: 24px;
    border-bottom: 2px solid #f0f0f0;
    padding-bottom: 15px;
}

.confirm {
    background-color: #f9f9f9;
    border-left: 4px solid #007bff;
    padding: 15px;
    margin-bottom: 20px;
    font-size: 16px;
    color: #555;
    border-radius: 4px;
}

.report-container form {
    display: flex;
    flex-direction: column;
}

.report-container label {
    margin-bottom: 8px;
    font-weight: 600;
    color: #444;
}

.report-container textarea, 
.report-container input[type="file"] {
    width: 100%;
    padding: 12px;
    margin-bottom: 20px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 16px;
    transition: border-color 0.3s ease;
}

.report-container textarea:focus,
.report-container input[type="file"]:focus {
    outline: none;
    border-color: #007bff;
    box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
}

.report-container textarea {
    resize: vertical;
    min-height: 120px;
}

.report-container small {
    color: #777;
    margin-top: -15px;
    margin-bottom: 15px;
}

.report-container button {
    padding: 12px 20px;
    margin-right: 10px;
    border: none;
    border-radius: 6px;
    font-size: 16px;
    cursor: pointer;
    transition: background-color 0.3s ease, transform 0.1s ease;
}

.report-container button[type="submit"] {
    background-color: #dc3545;
    color: white;
    margin-bottom: 20px;
}

.report-container button[type="submit"]:hover {
    background-color: #c82333;
}

.report-container button[type="button"] {
    background-color: #6c757d;
    color: white;
}

.report-container button[type="button"]:hover {
    background-color: #545b62;
}

.report-container button:active {
    transform: scale(0.98);
}

.error {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
    padding: 15px;
    border-radius: 6px;
    margin-bottom: 20px;
}

/* Responsive Design */
@media (max-width: 768px) {
    .report-container {
        width: 95%;
        padding: 20px;
    }

    .report-container button {
        width: 100%;
        margin-bottom: 10px;
    }
}
</style>
<body>
    <?php include 'navbar.php'; ?>
    <div class="container">
        <div class="report-container">
            <h1><?php echo htmlspecialchars($pageTitle); ?></h1>

            <?php if (isset($error)): ?>
                <p class="error"><?php echo htmlspecialchars($error); ?></p>
            <?php endif; ?>

            <div class="target-info">
                <?php if ($reportType === 'post'): ?>
                    <h3>Reported Post</h3>
                    <p><strong>Title:</strong> <?php echo htmlspecialchars($targetInfo['Title']); ?></p>
                    <p><strong>Author:</strong> <?php echo htmlspecialchars(getUserById($targetInfo['UserID'])['Username']); ?></p>
                <?php elseif ($reportType === 'comment'): ?>
                    <h3>Reported Comment</h3>
                    <p><strong>Content:</strong> <?php echo htmlspecialchars($targetInfo['Content']); ?></p>
                    <p><strong>Author:</strong> <?php echo htmlspecialchars(getUserById($targetInfo['UserID'])['Username']); ?></p>
                <?php elseif ($reportType === 'user'): ?>
                    <h3>Reported User</h3>
                    <p><strong>Username:</strong> <?php echo htmlspecialchars($targetInfo['Username']); ?></p>
                <?php endif; ?>
            </div>


            <form action="" method="POST" enctype="multipart/form-data">
            <label for="violation">Describe the violation:</label>
                <textarea name="violation" id="violation" required></textarea>
                
                <label for="evidence">Upload evidence (optional):</label>
                <input type="file" name="evidence" id="evidence" accept="image/*">
                <small>Accepted formats: jpg, png, gif. Max size: 5MB</small>
                
                <button type="submit">Submit Report</button>
                <button type="button" onclick="window.location.href='view_<?php echo $reportType; ?>.php?id=<?php echo $targetID; ?>'">Cancel</button>
            </form>
        </div>
    </div>
</body>
</html>