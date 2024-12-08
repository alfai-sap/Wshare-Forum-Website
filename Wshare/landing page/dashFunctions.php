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
              GROUP BY DATE(JoinedAt)
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
function getTotalTimeSpentByUsers() {
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
}

?>
