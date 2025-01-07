<?php
// Assuming you already have the database connection set up here
require_once 'db_connection.php';

if (!$conn) {
    error_log("Database connection failed: " . mysqli_connect_error());
    die("Database connection failed. Please check the error logs.");
}



// Log user activity
function logUserActivity($userID, $activityType, $description) {
    global $conn;
    $ip = $_SERVER['REMOTE_ADDR'];
    $query = "INSERT INTO user_activity_logs (UserID, ActivityType, Description, IPAddress) 
              VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "isss", $userID, $activityType, $description, $ip);
    return mysqli_stmt_execute($stmt);
}

// Get user statistics
function getUserStats() {
    global $conn;
    $query = "SELECT 
                COUNT(DISTINCT u.UserID) as total_users,
                COUNT(DISTINCT CASE WHEN u.JoinedAt >= DATE_SUB(NOW(), INTERVAL 24 HOUR) THEN u.UserID END) as new_users_24h,
                COUNT(DISTINCT CASE WHEN u.JoinedAt >= DATE_SUB(NOW(), INTERVAL 7 DAY) THEN u.UserID END) as new_users_7d,
                COUNT(DISTINCT CASE WHEN p.UserID IS NOT NULL THEN u.UserID END) as active_posters,
                COUNT(DISTINCT CASE WHEN c.UserID IS NOT NULL THEN u.UserID END) as active_commenters
              FROM users u
              LEFT JOIN posts p ON u.UserID = p.UserID AND p.CreatedAt >= DATE_SUB(NOW(), INTERVAL 30 DAY)
              LEFT JOIN comments c ON u.UserID = c.UserID AND c.CreatedAt >= DATE_SUB(NOW(), INTERVAL 30 DAY)";
    
    $result = mysqli_query($conn, $query);
    if (!$result) {
        error_log("Error in getUserStats: " . mysqli_error($conn));
        return false;
    }
    return mysqli_fetch_assoc($result);
}

// Get system settings
function getAdminSettings() {
    global $conn;
    $query = "SELECT * FROM admin_settings";
    $result = mysqli_query($conn, $query);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

// Update system setting
function updateAdminSetting($settingName, $settingValue, $adminID) {
    global $conn;
    $query = "INSERT INTO admin_settings (SettingName, SettingValue, UpdatedBy) 
              VALUES (?, ?, ?) 
              ON DUPLICATE KEY UPDATE 
              SettingValue = VALUES(SettingValue),
              UpdatedBy = VALUES(UpdatedBy),
              UpdatedAt = CURRENT_TIMESTAMP";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ssi", $settingName, $settingValue, $adminID);
    return mysqli_stmt_execute($stmt);
}

// Fetch total users
function getTotalUsers() {
    global $conn;
    $query = "SELECT COUNT(*) AS total_users FROM users";
    $result = mysqli_query($conn, $query);
    return mysqli_fetch_assoc($result)['total_users'];
}

// Fetch new users in the last month
function getNewUsersLastMonth() {
    global $conn;
    $query = "SELECT COUNT(*) AS new_users FROM users WHERE JoinedAt > NOW() - INTERVAL 1 MONTH";
    $result = mysqli_query($conn, $query);
    return mysqli_fetch_assoc($result)['new_users'];
}

// Fetch total posts
function getTotalPosts() {
    global $conn;
    $query = "SELECT COUNT(*) AS total_posts FROM posts";
    $result = mysqli_query($conn, $query);
    return mysqli_fetch_assoc($result)['total_posts'];
}

// Fetch weekly posts (last 7 days)
function getPostsLastWeek() {
    global $conn;
    $query = "SELECT COUNT(*) AS weekly_posts FROM posts WHERE CreatedAt > NOW() - INTERVAL 1 WEEK";
    $result = mysqli_query($conn, $query);
    return mysqli_fetch_assoc($result)['weekly_posts'];
}

// Fetch most active users by posts
function getMostActiveUsersByPosts() {
    global $conn;
    $query = "SELECT users.Username, COUNT(posts.PostID) AS post_count
              FROM users
              JOIN posts ON users.UserID = posts.UserID
              GROUP BY users.UserID
              ORDER BY post_count DESC
              LIMIT 10";
    $result = mysqli_query($conn, $query);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

// Fetch most active users by comments
function getMostActiveUsersByComments() {
    global $conn;
    $query = "SELECT users.Username, COUNT(comments.CommentID) AS comment_count
              FROM users
              JOIN comments ON users.UserID = comments.UserID
              GROUP BY users.UserID
              ORDER BY comment_count DESC
              LIMIT 10";
    $result = mysqli_query($conn, $query);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

// Fetch total comments
function getTotalComments() {
    global $conn;
    $query = "SELECT COUNT(*) AS total_comments FROM comments";
    $result = mysqli_query($conn, $query);
    return mysqli_fetch_assoc($result)['total_comments'];
}

// Fetch most liked posts
function getMostLikedPosts() {
    global $conn;
    $query = "SELECT posts.Title, COUNT(likes.LikeID) AS like_count
              FROM posts
              JOIN likes ON posts.PostID = likes.PostID
              GROUP BY posts.PostID
              ORDER BY like_count DESC
              LIMIT 10";
    $result = mysqli_query($conn, $query);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

// Fetch most commented posts
function getMostCommentedPosts() {
    global $conn;
    $query = "SELECT posts.Title, COUNT(comments.CommentID) AS comment_count
              FROM posts
              JOIN comments ON posts.PostID = comments.PostID
              GROUP BY posts.PostID
              ORDER BY comment_count DESC
              LIMIT 10";
    $result = mysqli_query($conn, $query);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

// Fetch top followed users
function getTopFollowedUsers() {
    global $conn;
    $query = "SELECT users.Username, COUNT(follows.FollowingID) AS followers_count
              FROM users
              JOIN follows ON users.UserID = follows.FollowingID
              GROUP BY users.UserID
              ORDER BY followers_count DESC
              LIMIT 10";
    $result = mysqli_query($conn, $query);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

// Fetch top followers (users following the most people)
function getTopFollowers() {
    global $conn;
    $query = "SELECT users.Username, COUNT(follows.FollowerID) AS following_count
              FROM users
              JOIN follows ON users.UserID = follows.FollowerID
              GROUP BY users.UserID
              ORDER BY following_count DESC
              LIMIT 10";
    $result = mysqli_query($conn, $query);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

// Fetch daily new users over time (for a graph or trend analysis)
function getUserGrowth() {
    global $conn;
    $query = "SELECT DATE(JoinedAt) AS join_date, COUNT(*) AS new_users
              FROM users
              WHERE JoinedAt >= DATE_SUB(NOW(), INTERVAL 30 DAY)
              GROUP BY DATE(JoinedAt)
              ORDER BY join_date ASC";
    $result = mysqli_query($conn, $query);
    if (!$result) {
        error_log("Error in getUserGrowth: " . mysqli_error($conn));
        return [];
    }
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

// Fetch post growth over time (for a graph or trend analysis)
function getPostGrowth() {
    global $conn;
    $query = "SELECT DATE(CreatedAt) AS post_date, COUNT(*) AS new_posts
              FROM posts
              WHERE CreatedAt >= DATE_SUB(NOW(), INTERVAL 30 DAY)
              GROUP BY DATE(CreatedAt)
              ORDER BY post_date ASC";
    $result = mysqli_query($conn, $query);
    if (!$result) {
        error_log("Error in getPostGrowth: " . mysqli_error($conn));
        return [];
    }
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

// Calculate total time spent by each user and rank them
/*function getTotalTimeSpentByUsers() {
    global $conn;
    $query = "
        SELECT users.Username, 
               SUM(TIMESTAMPDIFF(SECOND, user_sessions.LoginTime, IFNULL(user_sessions.LogoutTime, NOW()))) AS total_time_spent
        FROM users
        JOIN user_sessions ON users.UserID = user_sessions.UserID
        GROUP BY users.UserID
        ORDER BY total_time_spent DESC
        LIMIT 10";
    $result = mysqli_query($conn, $query);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}*/

function getTotalTimeSpentByUsers() {
    global $conn;
    $query = "
        SELECT 
            users.Username, 
            SUM(TIMESTAMPDIFF(SECOND, user_sessions.LoginTime, IFNULL(user_sessions.LogoutTime, NOW()))) AS total_time_spent,
            SUM(CASE WHEN DATE(user_sessions.LoginTime) = CURDATE() THEN TIMESTAMPDIFF(SECOND, user_sessions.LoginTime, IFNULL(user_sessions.LogoutTime, NOW())) ELSE 0 END) AS time_spent_today,
            SUM(CASE WHEN user_sessions.LoginTime >= (NOW() - INTERVAL 1 WEEK) THEN TIMESTAMPDIFF(SECOND, user_sessions.LoginTime, IFNULL(user_sessions.LogoutTime, NOW())) ELSE 0 END) AS time_spent_week,
            SUM(CASE WHEN user_sessions.LoginTime >= (NOW() - INTERVAL 1 MONTH) THEN TIMESTAMPDIFF(SECOND, user_sessions.LoginTime, IFNULL(user_sessions.LogoutTime, NOW())) ELSE 0 END) AS time_spent_month
        FROM users
        JOIN user_sessions ON users.UserID = user_sessions.UserID
        GROUP BY users.UserID
        ORDER BY total_time_spent DESC
        LIMIT 10";
        
    $result = mysqli_query($conn, $query);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

// Fetch all users
function getAllUsersDash() {
    global $conn;
    $query = "SELECT * FROM users";
    $result = mysqli_query($conn, $query);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

// Add a new user
function addUserDash($username, $email, $password) {
    global $conn;
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $query = "INSERT INTO users (Username, Email, Password) VALUES (?, ?, ?)";
    
    // Prepare the statement
    $stmt = mysqli_prepare($conn, $query);
    if ($stmt) {
        // Bind parameters (s = string)
        mysqli_stmt_bind_param($stmt, "sss", $username, $email, $hashedPassword);
        // Execute the statement
        mysqli_stmt_execute($stmt);
        // Close the statement
        mysqli_stmt_close($stmt);
    } else {
        // Handle error
        die('Query preparation failed: ' . mysqli_error($conn));
    }
}

// Update an existing user
function updateUserDash($userID, $username, $email) {
    global $conn;
    $query = "UPDATE users SET Username = ?, Email = ? WHERE UserID = ?";
    
    // Prepare the statement
    $stmt = mysqli_prepare($conn, $query);
    if ($stmt) {
        // Bind parameters (s = string, i = integer)
        mysqli_stmt_bind_param($stmt, "ssi", $username, $email, $userID);
        // Execute the statement
        mysqli_stmt_execute($stmt);
        // Close the statement
        mysqli_stmt_close($stmt);
    } else {
        // Handle error
        die('Query preparation failed: ' . mysqli_error($conn));
    }
}

// Delete a user
function deleteUserDash($userID) {
    global $conn;
    $query = "DELETE FROM users WHERE UserID = ?";
    
    // Prepare the statement
    $stmt = mysqli_prepare($conn, $query);
    if ($stmt) {
        // Bind parameter (i = integer)
        mysqli_stmt_bind_param($stmt, "i", $userID);
        // Execute the statement
        mysqli_stmt_execute($stmt);
        // Close the statement
        mysqli_stmt_close($stmt);
    } else {
        // Handle error
        die('Query preparation failed: ' . mysqli_error($conn));
    }
}
// Function to get all users or filter by search term
function getUsersBySearch($searchTerm = '') {
    global $conn;

    // Use ? placeholder for query
    $query = "SELECT * FROM users WHERE Username LIKE ? OR Email LIKE ?";
    $stmt = $conn->prepare($query);

    // Bind the parameters (both will use the same search term)
    $searchTermWithWildcards = '%' . $searchTerm . '%';
    $stmt->bind_param("ss", $searchTermWithWildcards, $searchTermWithWildcards); // "ss" means two string parameters

    // Execute the query and fetch results
    $stmt->execute();
    $result = $stmt->get_result();

    // Fetch and return all matching rows
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Get number of users who logged in today
function getActiveUsersToday() {
    global $conn;
    $query = "SELECT COUNT(DISTINCT UserID) AS active_users 
              FROM user_sessions 
              WHERE DATE(LoginTime) = CURDATE()";
    $result = mysqli_query($conn, $query);
    return mysqli_fetch_assoc($result)['active_users'];
}

// Get number of posts created today
function getPostsToday() {
    global $conn;
    $query = "SELECT COUNT(*) AS posts_today 
              FROM posts 
              WHERE DATE(CreatedAt) = CURDATE()";
    $result = mysqli_query($conn, $query);
    return mysqli_fetch_assoc($result)['posts_today'];
}

// Get number of pending reports
function getPendingReports() {
    global $conn;
    $query = "SELECT COUNT(*) AS pending_reports 
              FROM reports 
              WHERE Status = 'pending'";
    $result = mysqli_query($conn, $query);
    return mysqli_fetch_assoc($result)['pending_reports'];
}

// Check system health by monitoring key metrics
function getSystemHealth() {
    global $conn;
    
    // Check database connection
    if (!$conn) {
        return "Critical";
    }
    
    // Check for recent activity (last 5 minutes)
    $query = "SELECT COUNT(*) AS recent_activity 
              FROM user_sessions 
              WHERE LoginTime >= NOW() - INTERVAL 5 MINUTE";
    $result = mysqli_query($conn, $query);
    $recent_activity = mysqli_fetch_assoc($result)['recent_activity'];
    
    // Check database size
    $query = "SELECT table_schema AS db_name, 
              ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) AS size_mb 
              FROM information_schema.tables 
              WHERE table_schema = 'wshare_db_new' 
              GROUP BY table_schema";
    $result = mysqli_query($conn, $query);
    $db_size = mysqli_fetch_assoc($result)['size_mb'] ?? 0;
    
    // Define thresholds
    $size_threshold = 1000; // 1000MB = 1GB
    
    // Determine system health status
    if ($db_size > $size_threshold) {
        return "Warning";
    } elseif ($recent_activity === 0) {
        return "Warning";
    } else {
        return "Good";
    }
}

// Post Management Functions
function getAllPosts($limit = 10, $offset = 0) {
    global $conn;
    $query = "SELECT posts.*, users.Username 
              FROM posts 
              JOIN users ON posts.UserID = users.UserID 
              ORDER BY posts.CreatedAt DESC 
              LIMIT ? OFFSET ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ii", $limit, $offset);
    mysqli_stmt_execute($stmt);
    return mysqli_stmt_get_result($stmt)->fetch_all(MYSQLI_ASSOC);
}

function deletePostDash($postID) {
    global $conn;
    $query = "DELETE FROM posts WHERE PostID = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $postID);
    return mysqli_stmt_execute($stmt);
}

// Delete a post


// Comment Management Functions
function getAllComments($limit = 10, $offset = 0) {
    global $conn;
    $query = "SELECT comments.*, users.Username, posts.Title as PostTitle 
              FROM comments 
              JOIN users ON comments.UserID = users.UserID 
              JOIN posts ON comments.PostID = posts.PostID 
              ORDER BY comments.CreatedAt DESC 
              LIMIT ? OFFSET ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ii", $limit, $offset);
    mysqli_stmt_execute($stmt);
    return mysqli_stmt_get_result($stmt)->fetch_all(MYSQLI_ASSOC);
}

function deleteComment($commentId) {
    global $conn;
    
    // First delete all replies to this comment
    $stmt = $conn->prepare("DELETE FROM replies WHERE CommentID = ?");
    $stmt->bind_param("i", $commentId);
    $stmt->execute();
    
    // Then delete the comment itself
    $stmt = $conn->prepare("DELETE FROM comments WHERE CommentID = ?");
    $stmt->bind_param("i", $commentId);
    return $stmt->execute();
}

// Add this new function
function deleteReply($replyId) {
    global $conn;
    $query = "DELETE FROM replies WHERE ReplyID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $replyId);
    return $stmt->execute();
}

// Search Functions

// Filter comments by keyword
function filterCommentsByKeyword($keyword) {
    global $conn;
    $query = "SELECT comments.*, users.Username, posts.Title as PostTitle 
              FROM comments 
              JOIN users ON comments.UserID = users.UserID 
              JOIN posts ON comments.PostID = posts.PostID 
              WHERE comments.Content LIKE ?";
    $stmt = mysqli_prepare($conn, $query);
    $keyword = "%$keyword%";
    mysqli_stmt_bind_param($stmt, "s", $keyword);
    mysqli_stmt_execute($stmt);
    return mysqli_stmt_get_result($stmt)->fetch_all(MYSQLI_ASSOC);
}

// Analyze comment content using AI
function analyzeCommentContent($commentID) {
    global $conn;
    $query = "SELECT Content FROM comments WHERE CommentID = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $commentID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt)->fetch_assoc();
    $content = $result['Content'];

    // Call to AI-based content analysis service (e.g., using an API)
    $analysisResults = callAIContentAnalysisAPI($content);

    return $analysisResults;
}

// Mock function to call AI-based content analysis API
function callAIContentAnalysisAPI($content) {
    // This is a mock function. Replace with actual API call.
    return [
        'sentiment' => 'positive',
        'toxicity' => 'low',
        'keywords' => ['example', 'keyword']
    ];
}

// Statistics Functions
function getPostStats() {
    global $conn;
    $query = "SELECT 
                COUNT(*) as total_posts,
                COUNT(CASE WHEN CreatedAt >= DATE_SUB(NOW(), INTERVAL 24 HOUR) THEN 1 END) as posts_24h,
                COUNT(CASE WHEN CreatedAt >= DATE_SUB(NOW(), INTERVAL 7 DAY) THEN 1 END) as posts_7d
              FROM posts";
    $result = mysqli_query($conn, $query);
    return mysqli_fetch_assoc($result);
}

function getCommentStats() {
    global $conn;
    $query = "SELECT 
                COUNT(*) as total_comments,
                COUNT(CASE WHEN CreatedAt >= DATE_SUB(NOW(), INTERVAL 24 HOUR) THEN 1 END) as comments_24h,
                COUNT(CASE WHEN CreatedAt >= DATE_SUB(NOW(), INTERVAL 7 DAY) THEN 1 END) as comments_7d
              FROM comments";
    $result = mysqli_query($conn, $query);
    return mysqli_fetch_assoc($result);
}

// User Activity Log Functions
function getUserActivities($limit = 50) {
    global $conn;
    $query = "SELECT 
                'Login' as ActivityType,
                u.Username,
                s.LoginTime as CreatedAt,
                CONCAT('Logged in') as Description,
                '127.0.0.1' as IPAddress
              FROM user_sessions s
              JOIN users u ON s.UserID = u.UserID
              UNION ALL
              SELECT 
                'Post' as ActivityType,
                u.Username,
                p.CreatedAt,
                CONCAT('Created post: ', SUBSTRING(p.Title, 1, 50)) as Description,
                '127.0.0.1' as IPAddress
              FROM posts p
              JOIN users u ON p.UserID = u.UserID
              ORDER BY CreatedAt DESC
              LIMIT ?";
              
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $limit);
    mysqli_stmt_execute($stmt);
    return mysqli_stmt_get_result($stmt)->fetch_all(MYSQLI_ASSOC);
}


function getSystemLogs($days = 7, $userID = null, $actionType = null, $limit = 20, $offset = 0) {
    global $conn;
    $baseQuery = "SELECT logs.*, users.Username FROM (
                SELECT 
                    'login' as action,
                    UserID,
                    LoginTime as timestamp,
                    NULL as details
                FROM user_sessions 
                WHERE LoginTime >= NOW() - INTERVAL ? DAY
                UNION ALL
                SELECT 
                    'post' as action,
                    UserID,
                    CreatedAt as timestamp,
                    Title as details
                FROM posts 
                WHERE CreatedAt >= NOW() - INTERVAL ? DAY
              ) as logs
              LEFT JOIN users ON logs.UserID = users.UserID";

    $conditions = [];
    $params = [$days, $days];
    $types = "ii";
    
    if ($userID) {
        $conditions[] = "logs.UserID = ?";
        $params[] = $userID;
        $types .= "i";
    }
    
    if ($actionType) {
        $conditions[] = "logs.action = ?";
        $params[] = $actionType;
        $types .= "s";
    }
    
    if (!empty($conditions)) {
        $baseQuery .= " WHERE " . implode(" AND ", $conditions);
    }
    
    $baseQuery .= " ORDER BY timestamp DESC LIMIT ? OFFSET ?";
    $params[] = $limit;
    $params[] = $offset;
    $types .= "ii";
    
    $stmt = mysqli_prepare($conn, $baseQuery);
    mysqli_stmt_bind_param($stmt, $types, ...$params);
    mysqli_stmt_execute($stmt);
    return mysqli_stmt_get_result($stmt)->fetch_all(MYSQLI_ASSOC);
}

function countSystemLogs($days = 7, $userID = null, $actionType = null) {
    global $conn;
    $baseQuery = "SELECT COUNT(*) as total_logs FROM (
                SELECT 
                    'login' as action,
                    UserID,
                    LoginTime as timestamp
                FROM user_sessions 
                WHERE LoginTime >= NOW() - INTERVAL ? DAY
                UNION ALL
                SELECT 
                    'post' as action,
                    UserID,
                    CreatedAt as timestamp
                FROM posts 
                WHERE CreatedAt >= NOW() - INTERVAL ? DAY
              ) as logs";

    $conditions = [];
    $params = [$days, $days];
    $types = "ii";
    
    if ($userID) {
        $conditions[] = "UserID = ?";
        $params[] = $userID;
        $types .= "i";
    }
    
    if ($actionType) {
        $conditions[] = "action = ?";
        $params[] = $actionType;
        $types .= "s";
    }
    
    if (!empty($conditions)) {
        $baseQuery .= " WHERE " . implode(" AND ", $conditions);
    }
    
    $stmt = mysqli_prepare($conn, $baseQuery);
    mysqli_stmt_bind_param($stmt, $types, ...$params);
    mysqli_stmt_execute($stmt);
    return mysqli_stmt_get_result($stmt)->fetch_assoc()['total_logs'];
}

// Fix the incomplete getActivityStats function
function getActivityStats() {
    global $conn;
    $query = "SELECT 
                (SELECT COUNT(*) FROM user_sessions) + 
                (SELECT COUNT(*) FROM posts) as total_activities,
                (SELECT COUNT(*) FROM user_sessions WHERE LoginTime >= NOW() - INTERVAL 24 HOUR) +
                (SELECT COUNT(*) FROM posts WHERE CreatedAt >= NOW() - INTERVAL 24 HOUR) as activities_24h,
                (SELECT COUNT(*) FROM user_sessions WHERE LoginTime >= NOW() - INTERVAL 7 DAY) +
                (SELECT COUNT(*) FROM posts WHERE CreatedAt >= NOW() - INTERVAL 7 DAY) as activities_7d
              FROM DUAL";
    
    $result = mysqli_query($conn, $query);
    if (!$result) {
        error_log("Error in getActivityStats: " . mysqli_error($conn));
        return array(
            'total_activities' => 0,
            'activities_24h' => 0,
            'activities_7d' => 0
        );
    }
    return mysqli_fetch_assoc($result);
}

// Admin Settings Management
function getAllAdminSettings() {
    global $conn;
    $query = "SELECT * FROM admin_settings ORDER BY SettingName ASC";
    $result = mysqli_query($conn, $query);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

function updateSetting($settingName, $settingValue) {
    global $conn;
    $query = "INSERT INTO admin_settings (SettingName, SettingValue) 
              VALUES (?, ?) ON DUPLICATE KEY 
              UPDATE SettingValue = VALUES(SettingValue)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ss", $settingName, $settingValue);
    return mysqli_stmt_execute($stmt);
}

// System Monitoring
function getErrorLogs($limit = 50) {
    global $conn;
    $query = "SELECT * FROM error_logs ORDER BY CreatedAt DESC LIMIT ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $limit);
    mysqli_stmt_execute($stmt);
    return mysqli_stmt_get_result($stmt)->fetch_all(MYSQLI_ASSOC);
}

// Add proper cleanup to getDatabaseStats
function getDatabaseStats() {
    global $conn;
    $stats = [];
    
    $query = "SELECT 
                table_name,
                round(((data_length + index_length) / 1024 / 1024), 2) 'size_mb',
                table_rows as 'rows'
              FROM information_schema.TABLES 
              WHERE table_schema = DATABASE()
              ORDER BY (data_length + index_length) DESC";
              
    $result = mysqli_query($conn, $query);
    if (!$result) {
        error_log("Error in getDatabaseStats: " . mysqli_error($conn));
        return false;
    }
    
    $stats['tables'] = mysqli_fetch_all($result, MYSQLI_ASSOC);
    $stats['total_size'] = array_sum(array_column($stats['tables'], 'size_mb'));
    mysqli_free_result($result);
    
    return $stats;
}

function getSystemStatus() {
    global $conn;
    $status = [];
    
    // Database connection status
    $status['database'] = $conn ? 'Connected' : 'Disconnected';
    
    // Server info
    $status['server'] = $_SERVER['SERVER_SOFTWARE'];
    $status['php_version'] = PHP_VERSION;
    
    // Memory usage
    $status['memory_usage'] = memory_get_usage(true);
    $status['memory_limit'] = ini_get('memory_limit');
    
    return $status;
}

// Report Management Functions
function getAllReports() {
    global $conn;
    $query = "SELECT reports.*, users.Username, 
              CASE 
                WHEN reports.ReportType = 'post' THEN posts.Title
                WHEN reports.ReportType = 'comment' THEN comments.Content
                WHEN reports.ReportType = 'user' THEN reported_user.Username
              END as TargetContent
              FROM reports
              JOIN users ON reports.UserID = users.UserID
              LEFT JOIN posts ON reports.ReportType = 'post' AND reports.TargetID = posts.PostID
              LEFT JOIN comments ON reports.ReportType = 'comment' AND reports.TargetID = comments.CommentID
              LEFT JOIN users reported_user ON reports.ReportType = 'user' AND reports.TargetID = reported_user.UserID
              ORDER BY reports.CreatedAt DESC";
    $result = mysqli_query($conn, $query);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

function getReportsByStatus($status) {
    global $conn;
    $query = "SELECT reports.*, users.Username, 
              CASE 
                WHEN reports.ReportType = 'post' THEN posts.Title
                WHEN reports.ReportType = 'comment' THEN comments.Content
                WHEN reports.ReportType = 'user' THEN reported_user.Username
              END as TargetContent
              FROM reports
              JOIN users ON reports.UserID = users.UserID
              LEFT JOIN posts ON reports.ReportType = 'post' AND reports.TargetID = posts.PostID
              LEFT JOIN comments ON reports.ReportType = 'comment' AND reports.TargetID = comments.CommentID
              LEFT JOIN users reported_user ON reports.ReportType = 'user' AND reports.TargetID = reported_user.UserID
              WHERE reports.Status = ?
              ORDER BY reports.CreatedAt DESC";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $status);
    mysqli_stmt_execute($stmt);
    return mysqli_stmt_get_result($stmt)->fetch_all(MYSQLI_ASSOC);
}

function updateReportStatus($reportId, $status) {
    global $conn;
    $query = "UPDATE reports SET Status = ?, UpdatedAt = CURRENT_TIMESTAMP WHERE ReportID = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "si", $status, $reportId);
    return mysqli_stmt_execute($stmt);
}

function getReportDetails($reportId) {
    global $conn;
    $query = "SELECT reports.*, users.Username as ReporterName,
              CASE 
                WHEN reports.ReportType = 'post' THEN posts.Title
                WHEN reports.ReportType = 'comment' THEN comments.Content
                WHEN reports.ReportType = 'user' THEN reported_user.Username
              END as TargetContent,
              CASE 
                WHEN reports.ReportType = 'post' THEN posts.Content
                WHEN reports.ReportType = 'comment' THEN posts.Title
                WHEN reports.ReportType = 'user' THEN reported_user.Email
              END as AdditionalInfo,
              reported_user.Username as ReportedUsername
              FROM reports
              JOIN users ON reports.UserID = users.UserID
              LEFT JOIN posts ON reports.ReportType = 'post' AND reports.TargetID = posts.PostID
              LEFT JOIN comments ON reports.ReportType = 'comment' AND reports.TargetID = comments.CommentID
              LEFT JOIN users reported_user ON reports.ReportedUserID = reported_user.UserID
              WHERE reports.ReportID = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $reportId);
    mysqli_stmt_execute($stmt);
    return mysqli_stmt_get_result($stmt)->fetch_assoc();
}

function getReportStats() {
    global $conn;
    $query = "SELECT 
                COUNT(*) as total_reports,
                COUNT(CASE WHEN Status = 'pending' THEN 1 END) as pending_reports,
                COUNT(CASE WHEN Status = 'resolved' THEN 1 END) as resolved_reports,
                COUNT(CASE WHEN Status = 'reviewed' THEN 1 END) as reviewed_reports
              FROM reports";
    $result = mysqli_query($conn, $query);
    return mysqli_fetch_assoc($result);
}

// Calculate user retention rate
function getUserRetentionRate() {
    global $conn;
    $query = "SELECT 
                (COUNT(DISTINCT UserID) / (SELECT COUNT(*) FROM users WHERE JoinedAt <= NOW() - INTERVAL 1 MONTH)) * 100 AS retention_rate
              FROM user_sessions
              WHERE LoginTime >= NOW() - INTERVAL 1 MONTH";
    $result = mysqli_query($conn, $query);
    return round(mysqli_fetch_assoc($result)['retention_rate'], 2);
}

// Calculate average engagement time
function getAverageEngagementTime() {
    global $conn;
    $query = "SELECT 
                AVG(TIMESTAMPDIFF(MINUTE, LoginTime, IFNULL(LogoutTime, NOW()))) AS avg_engagement_time
              FROM user_sessions";
    $result = mysqli_query($conn, $query);
    return round(mysqli_fetch_assoc($result)['avg_engagement_time'], 2);
}

// Get user activity heatmap data
function getUserActivityHeatmapData() {
    global $conn;
    $query = "SELECT 
                DATE(LoginTime) AS date, 
                HOUR(LoginTime) AS hour, 
                COUNT(*) AS activity_count
              FROM user_sessions
              GROUP BY DATE(LoginTime), HOUR(LoginTime)";
    $result = mysqli_query($conn, $query);
    $data = mysqli_fetch_all($result, MYSQLI_ASSOC);

    $heatmapData = [
        'labels' => [],
        'data' => []
    ];

    foreach ($data as $row) {
        $heatmapData['labels'][] = $row['date'] . ' ' . $row['hour'] . ':00';
        $heatmapData['data'][] = $row['activity_count'];
    }

    return $heatmapData;
}

// Fetch detailed information about a post
function getPostDetails($postId) {
    global $conn;
    $query = "SELECT posts.*, COUNT(likes.LikeID) AS like_count
              FROM posts
              LEFT JOIN likes ON posts.PostID = likes.PostID
              WHERE posts.PostID = ?
              GROUP BY posts.PostID";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $postId);
    mysqli_stmt_execute($stmt);
    $postDetails = mysqli_stmt_get_result($stmt)->fetch_assoc();

    // Fetch comments for the post
    $query = "SELECT comments.Content, users.Username
              FROM comments
              JOIN users ON comments.UserID = users.UserID
              WHERE comments.PostID = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $postId);
    mysqli_stmt_execute($stmt);
    $postDetails['comments'] = mysqli_stmt_get_result($stmt)->fetch_all(MYSQLI_ASSOC);

    return $postDetails;
}

// Fetch detailed information about a comment
function getCommentDetails($commentId) {
    global $conn;
    $query = "SELECT comments.*, posts.Title AS PostTitle, users.Username
              FROM comments
              JOIN posts ON comments.PostID = posts.PostID
              JOIN users ON comments.UserID = users.UserID
              WHERE comments.CommentID = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $commentId);
    mysqli_stmt_execute($stmt);
    return mysqli_stmt_get_result($stmt)->fetch_assoc();
}

// Fetch detailed information about a user
function getUserDetails($userId) {
    global $conn;
    $query = "SELECT * FROM users WHERE UserID = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $userId);
    mysqli_stmt_execute($stmt);
    return mysqli_stmt_get_result($stmt)->fetch_assoc();
}

// Fetch user profile by user ID
function getUserProfileByIdDash($userId) {
    global $conn;
    $query = "SELECT * FROM userprofiles WHERE UserID = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $userId);
    mysqli_stmt_execute($stmt);
    return mysqli_stmt_get_result($stmt)->fetch_assoc();
}

// Function to calculate time ago
function calculateTimeAgo($datetime) {
    $time = strtotime($datetime);
    $timeDiff = time() - $time;

    if ($timeDiff < 60) {
        return 'just now';
    } elseif ($timeDiff < 3600) {
        return floor($timeDiff / 60) . ' minutes ago';
    } elseif ($timeDiff < 86400) {
        return floor($timeDiff / 3600) . ' hours ago';
    } elseif ($timeDiff < 604800) {
        return floor($timeDiff / 86400) . ' days ago';
    } else {
        return date('F j, Y', $time);
    }
}

// Function to count comments for a specific post
function countPostComments($postId) {
    global $conn;
    $query = "SELECT COUNT(*) AS comment_count FROM comments WHERE PostID = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $postId);
    mysqli_stmt_execute($stmt);
    return mysqli_stmt_get_result($stmt)->fetch_assoc()['comment_count'];
}

// Fetch total communities
function getTotalCommunities() {
    global $conn;
    $query = "SELECT COUNT(*) AS total_communities FROM communities";
    $result = mysqli_query($conn, $query);
    return mysqli_fetch_assoc($result)['total_communities'];
}

// Fetch total community members
function getTotalCommunityMembers() {
    global $conn;
    $query = "SELECT COUNT(*) AS total_members FROM community_members";
    $result = mysqli_query($conn, $query);
    return mysqli_fetch_assoc($result)['total_members'];
}

// Fetch active communities (with recent activity)
function getActiveCommunities() {
    global $conn;
    $query = "SELECT COUNT(DISTINCT CommunityID) AS active_communities 
              FROM community_posts 
              WHERE CreatedAt >= NOW() - INTERVAL 1 MONTH";
    $result = mysqli_query($conn, $query);
    return mysqli_fetch_assoc($result)['active_communities'];
}

// Fetch inactive communities (no recent activity)
function getInactiveCommunities() {
    global $conn;
    $query = "SELECT COUNT(*) AS inactive_communities 
              FROM communities 
              WHERE CommunityID NOT IN (
                  SELECT DISTINCT CommunityID 
                  FROM community_posts 
                  WHERE CreatedAt >= NOW() - INTERVAL 1 MONTH
              )";
    $result = mysqli_query($conn, $query);
    return mysqli_fetch_assoc($result)['inactive_communities'];
}

// Fetch all communities with details
function getAllCommunities() {
    global $conn;
    $query = "SELECT c.*, 
                     (SELECT COUNT(*) FROM community_members WHERE CommunityID = c.CommunityID) AS member_count,
                     (SELECT GROUP_CONCAT(u.Username SEPARATOR ', ') 
                      FROM community_members cm 
                      JOIN users u ON cm.UserID = u.UserID 
                      WHERE cm.CommunityID = c.CommunityID AND cm.Role = 'admin') AS admins,
                     (SELECT COUNT(*) 
                      FROM community_posts 
                      WHERE CommunityID = c.CommunityID AND CreatedAt >= NOW() - INTERVAL 1 MONTH) AS recent_activity
              FROM communities c";
    $result = mysqli_query($conn, $query);
    $communities = mysqli_fetch_all($result, MYSQLI_ASSOC);

    // Ensure admins is always an array and determine active status
    foreach ($communities as &$community) {
        $community['admins'] = $community['admins'] ? explode(', ', $community['admins']) : [];
        $community['is_active'] = $community['recent_activity'] > 0;
    }

    return $communities;
}

// Fetch community details by ID
function getCommunityDetails($communityId) {
    global $conn;
    $query = "SELECT c.*, 
                     (SELECT COUNT(*) FROM community_members WHERE CommunityID = c.CommunityID) AS member_count,
                     (SELECT GROUP_CONCAT(u.Username SEPARATOR ', ') 
                      FROM community_members cm 
                      JOIN users u ON cm.UserID = u.UserID 
                      WHERE cm.CommunityID = c.CommunityID AND cm.Role = 'admin') AS admins,
                     (SELECT COUNT(*) 
                      FROM community_posts 
                      WHERE CommunityID = c.CommunityID AND CreatedAt >= NOW() - INTERVAL 1 MONTH) AS recent_activity
              FROM communities c
              WHERE c.CommunityID = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $communityId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $community = mysqli_fetch_assoc($result);

    if ($community) {
        $community['admins'] = $community['admins'] ? explode(', ', $community['admins']) : [];
        $community['is_active'] = $community['recent_activity'] > 0;
    }

    return $community;
}

// Delete a community by ID
function deleteCommunity($communityId) {
    global $conn;
    $query = "DELETE FROM communities WHERE CommunityID = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $communityId);
    return mysqli_stmt_execute($stmt);
}

// Fetch community members by community ID
function getCommunityMembers($communityId) {
    global $conn;
    $query = "SELECT u.Username 
              FROM community_members cm 
              JOIN users u ON cm.UserID = u.UserID 
              WHERE cm.CommunityID = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $communityId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

// Search communities by title or description
function searchCommunities($searchTerm) {
    global $conn;
    $query = "SELECT c.*, 
                     (SELECT COUNT(*) FROM community_members WHERE CommunityID = c.CommunityID) AS member_count,
                     (SELECT GROUP_CONCAT(u.Username SEPARATOR ', ') 
                      FROM community_members cm 
                      JOIN users u ON cm.UserID = u.UserID 
                      WHERE cm.CommunityID = c.CommunityID AND cm.Role = 'admin') AS admins,
                     (SELECT COUNT(*) 
                      FROM community_posts 
                      WHERE CommunityID = c.CommunityID AND CreatedAt >= NOW() - INTERVAL 1 MONTH) AS recent_activity
              FROM communities c
              WHERE c.Title LIKE ? OR c.Description LIKE ?";
              
    $stmt = mysqli_prepare($conn, $query);
    $searchPattern = "%" . mysqli_real_escape_string($conn, $searchTerm) . "%";
    mysqli_stmt_bind_param($stmt, "ss", $searchPattern, $searchPattern);
    mysqli_stmt_execute($stmt);
    $communities = mysqli_stmt_get_result($stmt)->fetch_all(MYSQLI_ASSOC);

    foreach ($communities as &$community) {
        $community['admins'] = $community['admins'] ? explode(', ', $community['admins']) : [];
        $community['is_active'] = $community['recent_activity'] > 0;
    }

    return $communities;
}

// Filter communities by various criteria
function filterCommunities($filter) {
    global $conn;
    $baseQuery = "SELECT c.*, 
                     (SELECT COUNT(*) FROM community_members WHERE CommunityID = c.CommunityID) AS member_count,
                     (SELECT GROUP_CONCAT(u.Username SEPARATOR ', ') 
                      FROM community_members cm 
                      JOIN users u ON cm.UserID = u.UserID 
                      WHERE cm.CommunityID = c.CommunityID AND cm.Role = 'admin') AS admins,
                     (SELECT COUNT(*) 
                      FROM community_posts 
                      WHERE CommunityID = c.CommunityID AND CreatedAt >= NOW() - INTERVAL 1 MONTH) AS recent_activity
              FROM communities c";

    switch ($filter) {
        case 'active':
            $baseQuery .= " HAVING recent_activity > 0";
            break;
        case 'inactive':
            $baseQuery .= " HAVING recent_activity = 0";
            break;
        case 'public':
            $baseQuery .= " WHERE Visibility = 'public'";
            break;
        case 'private':
            $baseQuery .= " WHERE Visibility = 'private'";
            break;
        case 'most_members':
            $baseQuery .= " ORDER BY member_count DESC";
            break;
        default:
            $baseQuery .= " ORDER BY c.CreatedAt DESC";
    }

    $result = mysqli_query($conn, $baseQuery);
    $communities = mysqli_fetch_all($result, MYSQLI_ASSOC);

    foreach ($communities as &$community) {
        $community['admins'] = $community['admins'] ? explode(', ', $community['admins']) : [];
        $community['is_active'] = $community['recent_activity'] > 0;
    }

    return $communities;
}

// Ban a user
function banUser($userID, $adminID, $reason, $duration) {
    global $conn;
    
    // Validate inputs
    if (!$userID || !$adminID || !$reason || !$duration) {
        return false;
    }

    $banStart = date('Y-m-d H:i:s');
    $banEnd = date('Y-m-d H:i:s', strtotime("+$duration days"));

    $stmt = mysqli_prepare($conn, "INSERT INTO user_bans (UserID, BannedBy, BanStart, BanEnd, BanReason, IsActive) VALUES (?, ?, ?, ?, ?, 1)");
    mysqli_stmt_bind_param($stmt, "iisss", $userID, $adminID, $banStart, $banEnd, $reason);
    
    return mysqli_stmt_execute($stmt);
}

// Check if user is banned
function isUserBanned($userID) {
    global $conn;
    $query = "SELECT * FROM user_bans 
              WHERE UserID = ? AND IsActive = 1 
              AND BanEnd > NOW() 
              ORDER BY BanStart DESC LIMIT 1";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $userID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_assoc($result);
}

// Unban a user
function unbanUser($userID) {
    global $conn;
    $query = "UPDATE user_bans SET IsActive = 0 WHERE UserID = ? AND IsActive = 1";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $userID);
    return mysqli_stmt_execute($stmt);
}

// Get user's ban history
function getUserBanHistory($userID) {
    global $conn;
    $query = "SELECT b.*, u.Username as BannedByUsername 
              FROM user_bans b
              JOIN users u ON b.BannedBy = u.UserID
              WHERE b.UserID = ?
              ORDER BY b.BanStart DESC";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $userID);
    mysqli_stmt_execute($stmt);
    return mysqli_stmt_get_result($stmt)->fetch_all(MYSQLI_ASSOC);
}

// Function to check if admin is logged in
function checkAdminSession() {
    if (!isset($_SESSION['admin_id'])) {
        // Redirect to main user login page instead of admin login
        header("Location: ../../wshare system (adjusted)/login.php");
        exit();
    }
}

function getReports($status = null, $postType = null) {
    global $conn;
    $query = "SELECT reports.*, users.Username, 
              CASE 
                WHEN reports.ReportType = 'post' THEN posts.Title
                WHEN reports.ReportType = 'comment' THEN comments.Content
                WHEN reports.ReportType = 'user' THEN reported_user.Username
              END as TargetContent
              FROM reports
              JOIN users ON reports.UserID = users.UserID
              LEFT JOIN posts ON reports.ReportType = 'post' AND reports.TargetID = posts.PostID
              LEFT JOIN comments ON reports.ReportType = 'comment' AND reports.TargetID = comments.CommentID
              LEFT JOIN users reported_user ON reports.ReportType = 'user' AND reports.TargetID = reported_user.UserID";
    
    $conditions = [];
    $params = [];
    $types = "";

    if ($status) {
        $conditions[] = "reports.Status = ?";
        $params[] = $status;
        $types .= "s";
    }

    if ($postType) {
        $conditions[] = "reports.PostType = ?";
        $params[] = $postType;
        $types .= "s";
    }

    if (!empty($conditions)) {
        $query .= " WHERE " . implode(" AND ", $conditions);
    }

    $query .= " ORDER BY reports.CreatedAt DESC";
    
    $stmt = mysqli_prepare($conn, $query);
    if ($types) {
        mysqli_stmt_bind_param($stmt, $types, ...$params);
    }
    mysqli_stmt_execute($stmt);
    return mysqli_stmt_get_result($stmt)->fetch_all(MYSQLI_ASSOC);
}

function getReportedPostDetails($reportId) {
    global $conn;
    
    $sql = "SELECT r.*, p.Title, p.Content, p.PostID, 
            reporter.Username as ReporterName, 
            reported.Username as ReportedUsername, 
            reported.UserID as ReportedUserID 
            FROM reports r 
            LEFT JOIN posts p ON r.TargetID = p.PostID 
            LEFT JOIN users reporter ON r.UserID = reporter.UserID 
            LEFT JOIN users reported ON r.ReportedUserID = reported.UserID 
            WHERE r.ReportID = ?";
            
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $reportId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    return $result->fetch_assoc();
}

// Search Functions
function searchPostsDash($searchTerm) {
    global $conn;
    $query = "SELECT posts.*, users.Username 
              FROM posts 
              JOIN users ON posts.UserID = users.UserID 
              WHERE posts.Title LIKE ? OR posts.Content LIKE ?";
    $stmt = mysqli_prepare($conn, $query);
    if (!$stmt) {
        error_log("Error preparing statement: " . mysqli_error($conn));
        return false;
    }
    
    $searchPattern = "%" . mysqli_real_escape_string($conn, $searchTerm) . "%";
    mysqli_stmt_bind_param($stmt, "ss", $searchPattern, $searchPattern);
    mysqli_stmt_execute($stmt);
    return mysqli_stmt_get_result($stmt)->fetch_all(MYSQLI_ASSOC);
}

// Search comments by content
function searchCommentsDash($searchTerm) {
    global $conn;
    $query = "SELECT comments.*, users.Username, posts.Title as PostTitle 
              FROM comments 
              JOIN users ON comments.UserID = users.UserID 
              JOIN posts ON comments.PostID = posts.PostID 
              WHERE comments.Content LIKE ?";
    $stmt = mysqli_prepare($conn, $query);
    $searchPattern = "%" . mysqli_real_escape_string($conn, $searchTerm) . "%";
    mysqli_stmt_bind_param($stmt, "s", $searchPattern);
    mysqli_stmt_execute($stmt);
    return mysqli_stmt_get_result($stmt)->fetch_all(MYSQLI_ASSOC);
}




