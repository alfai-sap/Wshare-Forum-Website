<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'functions.php';

$topUsers = getTopUsersByTotalLikes();
$topCommunities = getTopCommunities();
$userID = getUserIdByUsername($_SESSION['username']); // Assuming user ID is stored in the session
$topDailyPosts= getTopDailyPosts();