<?php
require_once 'functions.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$notificationId = $data['notification_id'] ?? null;

if ($notificationId && markNotificationAsRead($notificationId)) {
    http_response_code(200);
} else {
    http_response_code(400);
}
?>