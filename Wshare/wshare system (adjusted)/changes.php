<?php
function isUserBanned($userID, $communityID = null) {
    global $conn;
    
    // Check for global bans first (where CommunityID is NULL)
    $query = "SELECT * FROM user_bans 
              WHERE UserID = ? 
              AND IsActive = 1 
              AND BanEnd > NOW() 
              AND CommunityID IS NULL";
    
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $userID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $globalBan = mysqli_fetch_assoc($result);
    
    // If there's a global ban, return it
    if ($globalBan) {
        return $globalBan;
    }
    
    // If a specific community is being checked, look for community-specific ban
    if ($communityID !== null) {
        $query = "SELECT * FROM user_bans 
                  WHERE UserID = ? 
                  AND CommunityID = ?
                  AND IsActive = 1 
                  AND BanEnd > NOW()";
        
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "ii", $userID, $communityID);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        return mysqli_fetch_assoc($result);
    }
    
    return null;
}

function checkUserBan($returnMessage = false, $communityID = null) {
    if (!isset($_SESSION['username'])) return false;
    
    $banInfo = isUserBanned(getUserIdByUsername($_SESSION['username']), $communityID);
    if ($banInfo) {
        if ($returnMessage) {
            $message = "You are restricted from accessing ";
            $message .= $banInfo['CommunityID'] ? "some community features" : "some platform features";
            $message .= " until " . date('Y-m-d', strtotime($banInfo['BanEnd']));
            $message .= ". Reason: " . $banInfo['BanReason'];
            return $message;
        }
        return true;
    }
    return false;
}
?>