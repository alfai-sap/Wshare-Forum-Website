<?php
require_once 'db_connection.php';

function sendMessage($communityID, $userID, $messageContent, $attachments = [], $replyTo = null) {
    global $conn;

    // Validate replyTo if provided
    if ($replyTo !== null) {
        $checkReply = "SELECT MessageID FROM community_messages WHERE MessageID = ?";
        $stmt = $conn->prepare($checkReply);
        $stmt->bind_param("i", $replyTo);
        $stmt->execute();
        if ($stmt->get_result()->num_rows === 0) {
            $replyTo = null; // Reset replyTo if parent message doesn't exist
        }
        $stmt->close();
    }

    // Insert the message
    $query = "INSERT INTO community_messages (CommunityID, UserID, Content, ParentMessageID) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iisi", $communityID, $userID, $messageContent, $replyTo);
    
    if (!$stmt->execute()) {
        error_log("Error sending message: " . $stmt->error);
        return false;
    }
    
    $messageID = $stmt->insert_id;
    $stmt->close();

    // Handle attachments if any
    if (!empty($attachments)) {
        foreach ($attachments as $attachment) {
            $query = "INSERT INTO message_attachments (MessageID, FilePath, FileName, FileType) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("isss", $messageID, $attachment['filePath'], $attachment['fileName'], $attachment['fileType']);
            $stmt->execute();
            $stmt->close();
        }
    }

    return $messageID;
}

function getMessages($communityID) {
    global $conn;

    $query = "SELECT m.*, u.Username, up.ProfilePic 
              FROM community_messages m
              JOIN users u ON m.UserID = u.UserID 
              JOIN userprofiles up ON u.UserID = up.UserID
              WHERE m.CommunityID = ? AND m.DeletedAt IS NULL
              ORDER BY m.CreatedAt ASC LIMIT 50";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $communityID);
    $stmt->execute();
    $result = $stmt->get_result();
    $messages = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    return $messages;
}

function getMessageAttachments($messageID) {
    global $conn;

    $query = "SELECT * FROM message_attachments WHERE MessageID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $messageID);
    $stmt->execute();
    $result = $stmt->get_result();
    $attachments = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    return $attachments;
}

function getUsernameByMessageID($messageID) {
    global $conn;

    $query = "SELECT u.Username 
              FROM community_messages m
              JOIN users u ON m.UserID = u.UserID
              WHERE m.MessageID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $messageID);
    $stmt->execute();
    $result = $stmt->get_result();
    $username = $result->fetch_assoc()['Username'];
    $stmt->close();

    return $username;
}

function getMessageByID($messageID) {
    global $conn;

    $query = "SELECT m.*, u.Username 
              FROM community_messages m
              JOIN users u ON m.UserID = u.UserID
              WHERE m.MessageID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $messageID);
    $stmt->execute();
    $result = $stmt->get_result();
    $message = $result->fetch_assoc();
    $stmt->close();

    return $message;
}

function getMessageReactions($messageID) {
    global $conn;
    $query = "SELECT mr.ReactionType, COUNT(*) as count, GROUP_CONCAT(u.Username) as users
              FROM message_reactions mr
              JOIN users u ON mr.UserID = u.UserID
              WHERE mr.MessageID = ?
              GROUP BY mr.ReactionType";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $messageID);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_all(MYSQLI_ASSOC);
}

function hasUserReacted($messageID, $userID, $reactionType) {
    global $conn;
    $query = "SELECT 1 FROM message_reactions 
              WHERE MessageID = ? AND UserID = ? AND ReactionType = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iis", $messageID, $userID, $reactionType);
    $stmt->execute();
    return $stmt->get_result()->num_rows > 0;
}

function toggleReaction($messageID, $userID, $reactionType) {
    global $conn;
    
    // First check if the reaction exists
    if (hasUserReacted($messageID, $userID, $reactionType)) {
        // Remove reaction
        $query = "DELETE FROM message_reactions 
                 WHERE MessageID = ? AND UserID = ? AND ReactionType = ?";
    } else {
        // Add reaction
        $query = "INSERT INTO message_reactions (MessageID, UserID, ReactionType) 
                 VALUES (?, ?, ?)";
    }
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iis", $messageID, $userID, $reactionType);
    return $stmt->execute();
}
?> 