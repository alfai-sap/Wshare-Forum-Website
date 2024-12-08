<?php
session_start();
require_once 'functions.php';

if (!isset($_SESSION['user_id'])) {
    die(json_encode(['success' => false, 'message' => 'Not logged in']));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_SESSION['user_id'];
    $settings = json_decode($_POST['settings'], true);
    $success = true;
    $errors = [];

    foreach ($settings as $name => $value) {
        $sql = "INSERT INTO user_settings (user_id, setting_name, setting_value) 
                VALUES (?, ?, ?) 
                ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)";
        
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            $errors[] = "Prepare failed for setting: $name";
            $success = false;
            continue;
        }

        $stmt->bind_param("iss", $userId, $name, $value);
        if (!$stmt->execute()) {
            $errors[] = "Execute failed for setting: $name - " . $stmt->error;
            $success = false;
        }
        $stmt->close();
    }

    echo json_encode([
        'success' => $success,
        'message' => $success ? 'Settings saved successfully' : 'Error saving settings: ' . implode(', ', $errors)
    ]);
}