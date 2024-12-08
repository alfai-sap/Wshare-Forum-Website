
<?php
require_once 'chat_functions.php';
session_start();

if (!isset($_SESSION['user_id']) || !isset($_POST['message_id']) || !isset($_POST['reaction_type'])) {
    echo json_encode(['success' => false, 'error' => 'Invalid request']);
    exit;
}

$messageID = intval($_POST['message_id']);
$userID = intval($_SESSION['user_id']);
$reactionType = $_POST['reaction_type'];

// Validate reaction type
$allowedReactions = ['👍', '❤️', '😄', '😮'];
if (!in_array($reactionType, $allowedReactions)) {
    echo json_encode(['success' => false, 'error' => 'Invalid reaction type']);
    exit;
}

$result = toggleReaction($messageID, $userID, $reactionType);

echo json_encode(['success' => $result]);
?>