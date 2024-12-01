<?php

// Assuming you already have the database connection set up here
$conn = mysqli_connect("localhost", "root", "", "wshare_db_new");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
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
    $query = "SELECT COUNT(*) AS new_users FROM users WHERE dateJoined > NOW() - INTERVAL 1 MONTH";
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
    $query = "SELECT DATE(dateJoined) AS join_date, COUNT(*) AS new_users
              FROM users
              GROUP BY DATE(dateJoined)
              ORDER BY join_date DESC
              LIMIT 30"; // Last 30 days
    $result = mysqli_query($conn, $query);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

// Fetch post growth over time (for a graph or trend analysis)
function getPostGrowth() {
    global $conn;
    $query = "SELECT DATE(CreatedAt) AS post_date, COUNT(*) AS new_posts
              FROM posts
              GROUP BY DATE(CreatedAt)
              ORDER BY post_date DESC
              LIMIT 30"; // Last 30 days
    $result = mysqli_query($conn, $query);
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
function getAllUsers() {
    global $conn;
    $query = "SELECT * FROM users";
    $result = mysqli_query($conn, $query);
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

// Add a new user
function addUser($username, $email, $password) {
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
function updateUser($userID, $username, $email) {
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
function deleteUser($userID) {
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


?>

