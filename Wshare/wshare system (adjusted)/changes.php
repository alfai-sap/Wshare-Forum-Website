<?php
function isUserBanned($userID) {
    global $conn;
    $query = "SELECT * FROM user_bans 
              WHERE UserID = ? 
              AND IsActive = 1 
              AND BanEnd > NOW()";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $userID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_assoc($result);
}

function checkUserBan($returnMessage = false) {
    if (!isset($_SESSION['username'])) return false;
    
    $banInfo = isUserBanned(getUserIdByUsername($_SESSION['username']));
    if ($banInfo) {
        if ($returnMessage) {
            return "You are restricted from accessing some of the website features until " . date('Y-m-d', strtotime($banInfo['BanEnd'])) . 
                   ". Reason: " . $banInfo['BanReason'];
        }
        return true;
    }
    return false;
}
?>