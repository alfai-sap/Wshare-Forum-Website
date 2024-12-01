<?php
include 'dashFunctions.php'; // Include your database functions

// Fetch the analytics data from your functions
$totalUsers = getTotalUsers();
$newUsersLastMonth = getNewUsersLastMonth();
$mostActiveUsersByPosts = getMostActiveUsersByPosts();
$mostActiveUsersByComments = getMostActiveUsersByComments();
$totalPosts = getTotalPosts();
$weeklyPosts = getPostsLastWeek();
$mostLikedPosts = getMostLikedPosts();
$totalComments = getTotalComments();
$mostCommentedPosts = getMostCommentedPosts();
$topFollowedUsers = getTopFollowedUsers();
$topFollowers = getTopFollowers();
$dailyNewUsers = getUserGrowth();
$monthlyPosts = getPostGrowth();
$topUsersByTimeSpent = getTotalTimeSpentByUsers();


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analytics Dashboard</title>
    <link rel="stylesheet" href="dashboard.css?v=<?php echo time(); ?>">
</head>
<body>

<?php include 'sidebar.php'; ?>
<h1>Wshare Analytics</h1>

<div class="container">
<!-- Analytics Summary Section -->
<div class="dashboard">
    <div class="box">
        <h2>Total Users</h2>
        <p><?php echo $totalUsers; ?></p>
    </div>
    <div class="box">
        <h2>New Users (Last Month)</h2>
        <p><?php echo $newUsersLastMonth; ?></p>
    </div>
    <div class="box">
        <h2>Total Posts</h2>
        <p><?php echo $totalPosts; ?></p>
    </div>
    <div class="box">
        <h2>Weekly Posts</h2>
        <p><?php echo $weeklyPosts; ?></p>
    </div>
    <div class="box">
        <h2>Total Comments</h2>
        <p><?php echo $totalComments; ?></p>
    </div>
</div>

<!-- Bento Box Style for Tables -->
<div class="table-container">
    
    <!-- Most Active Users by Posts -->
    <div class="table-box">
        <h2>Most Active Users by Posts</h2>
        <table>
            <tr><th>Username</th><th>Post Count</th></tr>
            <?php foreach ($mostActiveUsersByPosts as $user) { ?>
                <tr><td><?php echo $user['Username']; ?></td><td><?php echo $user['post_count']; ?></td></tr>
            <?php } ?>
        </table>
    </div>

    <!-- Most Active Users by Comments -->
    <div class="table-box">
        <h2>Most Active Users by Comments</h2>
        <table>
            <tr><th>Username</th><th>Comment Count</th></tr>
            <?php foreach ($mostActiveUsersByComments as $user) { ?>
                <tr><td><?php echo $user['Username']; ?></td><td><?php echo $user['comment_count']; ?></td></tr>
            <?php } ?>
        </table>
    </div>

    <!-- Most Liked Posts -->
    <div class="table-box">
        <h2>Most Liked Posts</h2>
        <table>
            <tr><th>Title</th><th>Likes</th></tr>
            <?php foreach ($mostLikedPosts as $post) { ?>
                <tr><td><?php echo $post['Title']; ?></td><td><?php echo $post['like_count']; ?></td></tr>
            <?php } ?>
        </table>
    </div>

    <!-- Most Commented Posts -->
    <div class="table-box">
        <h2>Most Commented Posts</h2>
        <table>
            <tr><th>Title</th><th>Comments</th></tr>
            <?php foreach ($mostCommentedPosts as $post) { ?>
                <tr><td><?php echo $post['Title']; ?></td><td><?php echo $post['comment_count']; ?></td></tr>
            <?php } ?>
        </table>
    </div>

    <!-- Top Followed Users -->
    <div class="table-box">
        <h2>Top Followed Users</h2>
        <table>
            <tr><th>Username</th><th>Followers</th></tr>
            <?php foreach ($topFollowedUsers as $user) { ?>
                <tr><td><?php echo $user['Username']; ?></td><td><?php echo $user['followers_count']; ?></td></tr>
            <?php } ?>
        </table>
    </div>

    <!-- Top Followers (Following the Most) -->
    <div class="table-box">
        <h2>Top Followers (Following the Most)</h2>
        <table>
            <tr><th>Username</th><th>Following Count</th></tr>
            <?php foreach ($topFollowers as $user) { ?>
                <tr><td><?php echo $user['Username']; ?></td><td><?php echo $user['following_count']; ?></td></tr>
            <?php } ?>
        </table>
    </div>

    <!-- Most Time Spent -->
    <div class="table-box">
        <h2>Most Time Spent</h2>
        <table>
            <tr>
                <th>Username</th>
                <th>Total Time Spent</th>
                <th>Time Spent Today</th>
                <th>Time Spent This Week</th>
                <th>Time Spent This Month</th>
            </tr>
            <?php foreach ($topUsersByTimeSpent as $user) { ?>
                <tr>
                    <td><?php echo $user['Username']; ?></td>
                    <td><?php echo gmdate("H:i:s", $user['total_time_spent']); ?></td>
                    <td><?php echo gmdate("H:i:s", $user['time_spent_today']); ?></td>
                    <td><?php echo gmdate("H:i:s", $user['time_spent_week']); ?></td>
                    <td><?php echo gmdate("H:i:s", $user['time_spent_month']); ?></td>
                </tr>
            <?php } ?>
        </table>
    </div>
    
</div>
</div>

</body>
</html>
