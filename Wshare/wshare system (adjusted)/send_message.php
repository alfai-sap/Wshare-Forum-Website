<?php
require_once 'chat_functions.php';
session_start();

// Define maximum file size (25MB)
define('MAX_FILE_SIZE', 25 * 1024 * 1024);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $communityID = intval($_POST['community_id']);
    $userID = intval($_SESSION['user_id']);
    $messageContent = trim($_POST['message']);
    $replyTo = !empty($_POST['reply_to']) ? intval($_POST['reply_to']) : null;

    $errors = [];
    $attachments = [];

    // Handle file uploads with validation
    if (!empty($_FILES['attachments']['name'][0])) {
        $uploadDir = 'uploads/';
        $totalSize = 0;
        
        // Calculate total size of all files
        foreach ($_FILES['attachments']['size'] as $size) {
            $totalSize += $size;
        }

        // Check total size
        if ($totalSize > MAX_FILE_SIZE * count($_FILES['attachments']['name'])) {
            $_SESSION['chat_error'] = "Total file size exceeds the limit. Each file must be under 25MB.";
            header("Location: community_page.php?community_id=$communityID");
            exit();
        }

        foreach ($_FILES['attachments']['name'] as $key => $name) {
            if ($_FILES['attachments']['error'][$key] === UPLOAD_ERR_INI_SIZE || 
                $_FILES['attachments']['error'][$key] === UPLOAD_ERR_FORM_SIZE) {
                $_SESSION['chat_error'] = "File '$name' exceeds the size limit of 25MB.";
                header("Location: community_page.php?community_id=$communityID");
                exit();
            }

            if ($_FILES['attachments']['error'][$key] === UPLOAD_ERR_OK) {
                $tmpName = $_FILES['attachments']['tmp_name'][$key];
                $fileSize = $_FILES['attachments']['size'][$key];
                $fileType = $_FILES['attachments']['type'][$key];

                // Validate file size
                if ($fileSize > MAX_FILE_SIZE) {
                    $_SESSION['chat_error'] = "File '$name' exceeds the size limit of 25MB.";
                    header("Location: community_page.php?community_id=$communityID");
                    exit();
                }

                // Validate file type
                $allowedTypes = [
                    'image/jpeg', 'image/png', 'image/gif', 
                    'application/pdf', 'application/msword', 
                    'text/plain', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 
                    'video/mp4', 'video/x-msvideo', 'application/x-sh', 'application/x-python-code'
                ];
                if (!in_array($fileType, $allowedTypes)) {
                    $_SESSION['chat_error'] = "File '$name' type is not allowed.";
                    header("Location: community_page.php?community_id=$communityID");
                    exit();
                }

                $fileName = uniqid() . '_' . basename($name);
                $filePath = $uploadDir . $fileName;

                if (move_uploaded_file($tmpName, $filePath)) {
                    $attachments[] = [
                        'filePath' => $filePath,
                        'fileName' => $name,
                        'fileType' => $fileType,
                        'fileSize' => $fileSize
                    ];
                } else {
                    $_SESSION['chat_error'] = "Failed to upload file '$name'.";
                    header("Location: community_page.php?community_id=$communityID");
                    exit();
                }
            }
        }
    }

    // Send the message
    $result = sendMessage($communityID, $userID, $messageContent, $attachments, $replyTo);
    
    if ($result === false) {
        $_SESSION['chat_error'] = "Error sending message";
        header("Location: community_page.php?community_id=$communityID");
        exit();
    } else {
        // Clear any previous errors
        unset($_SESSION['chat_error']);
        header("Location: community_page.php?community_id=$communityID");
        exit();
    }
}
?>