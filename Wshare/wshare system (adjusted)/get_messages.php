
<?php
require_once 'functions.php';
require_once 'chat_functions.php';
session_start();

if (!isset($_SESSION['user_id']) || !isset($_GET['community_id'])) {
    http_response_code(403);
    exit;
}

$communityID = intval($_GET['community_id']);
$messages = getMessages($communityID);

// Convert messages to JSON and send response
header('Content-Type: application/json');
echo json_encode($messages);