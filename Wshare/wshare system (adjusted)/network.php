<?php
require_once 'functions.php';
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Get logged-in user's ID
$loggedInUserID = getUserByUsername($_SESSION['username'])['UserID'];

// Handle sorting (default to followers)
$sortType = isset($_GET['sort']) ? $_GET['sort'] : 'followers';

// Handle search query
$searchQuery = isset($_GET['search']) ? $_GET['search'] : '';

// Retrieve followers or following based on sort type
if ($searchQuery) {
    $users = searchFollowUsers($loggedInUserID, $searchQuery, $sortType);
} else {
    $users = ($sortType === 'followers') ? getFollowers($loggedInUserID) : getFollowing($loggedInUserID);
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Followers & Following</title>
    <link rel="stylesheet" href="./css/network.css ?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="./css/navbar.css ?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="./css/left-navbar.css ?v=<?php echo time(); ?>">
</head>
<body>

    <ul class="navbar">
        <form action="" method="GET" class="search-bar">
            <input type="text" name="search" placeholder="I'm looking for..." value="<?php echo htmlspecialchars($searchQuery); ?>" class="search-input">
            <input type="hidden" name="sort" value="<?php echo $sortType; ?>">
            <button type="submit" class="search-button">Search</button>
        </form>
    </ul>
<?php include 'navbar.php';?>
    
<div class="container">

    <h1>Your Network</h1>

    <!-- Search Bar -->
    

    <!-- Sort by Followers or Following -->
    <div class="sort-options">
        <a href="network.php?sort=followers" class="sort-link">Followers</a> |
        <a href="network.php?sort=followed" class="sort-link">Followed</a>
    </div>

    <ul class="user-list">
    <h4><?php echo ucfirst($sortType); ?></h4>

    <?php if ($users): ?>
        <?php foreach ($users as $user): ?>
            <li class="user-item">
                <a href="view_user.php?username=<?php echo urlencode($user['Username']); ?>">
                    <div class="user-info">
                        <?php if ($user['ProfilePic']): ?>
                            <img src="<?php echo $user['ProfilePic']; ?>" alt="Profile Picture" class="profile-pic">
                        <?php else: ?>
                            <img src="default_pic.svg" alt="Default Picture" class="profile-pic">
                        <?php endif; ?>
                        <div class="details">
                            <p><strong><?php echo $user['Username']; ?></strong></p>
                            <p><?php echo $user['Email']; ?></p>
                        </div>
                    </div>
                </a>
            </li>
        <?php endforeach; ?>
    <?php else: ?>
        <li><p>No users found.</p></li>
    <?php endif; ?>
</ul>

</div>

<script>
        document.getElementById('logo-nav').addEventListener('click', function() {
            var element = document.getElementById('left-navbar');
            element.style.display = (element.style.display === 'none') ? 'block' : 'none';
        });

        document.getElementById('logo-left-nav').addEventListener('click', function() {
            var element = document.getElementById('left-navbar');
            element.style.display = (element.style.display === 'none') ? 'block' : 'none';
        });

        document.getElementById('comm_label').addEventListener('click', function() {
            var element = document.getElementById('comments');
            element.style.display = (element.style.display === 'none') ? 'block' : 'none';
        });
    </script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="./javascripts/index.js"></script>
</body>
</html>
