<?php
session_start();
require_once 'db_connection.php'; // Ensure this includes your DB connection
require_once 'functions.php'; //

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Default sorting option
$sort_option = isset($_GET['sort']) ? $_GET['sort'] : 'followed';

// Depending on sort option, fetch the correct posts
if ($sort_option == 'followers') {
    $posts = getPostsFromFollowers($user_id, $conn);
} else {
    $posts = getPostsFromFollowedUsers($user_id, $conn);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sort Posts</title>
    <link rel = "stylesheet" href="./css/networkfeed.css ?v=<?php echo time(); ?>">
    <link rel = "stylesheet" href="./css/navbar.css ?v=<?php echo time(); ?>">
    <link rel = "stylesheet" href="./css/left-navbar.css ?v=<?php echo time(); ?>">
    
</head>
<body>

    <ul class="navbar">
       
    </ul>
<?php include 'navbar.php';?><br>

    <div class = "container">
    <h1>Posts from Your Network</h1>

    <div class="sort-options">
    <form method="GET" action="networkfeed.php">
        
        <select name="sort" id="sort" class="sort-select" onchange="this.form.submit()">
            <option value = "">All posts</option>
            <option value="followed" <?php if ($sort_option == 'followed') echo 'selected'; ?>>From Followed</option>
            <option value="followers" <?php if ($sort_option == 'followers') echo 'selected'; ?>>From Followers</option>
        </select>
    </form>
    </div>
    <!--<h2>Posts <?php echo $sort_option == 'followers' ? "from Your Followers" : "from People You Follow"; ?>:</h2>-->
    <?php if ($posts->num_rows > 0): ?>
        <?php while ($post = $posts->fetch_assoc()): ?>
            <?php 
                // Fetch the user's profile picture from the userprofiles table (replace with actual logic)
                $profilePic = getUserProfilePic($post['UserID']); 
            ?>
            <div class="post-container">

                <div class="post">

                    <div class="pic_user" style="display:flex;">
                        <?php if (!empty($profilePic)): ?>
                            <img class="author_pic" src="<?php echo $profilePic; ?>" alt="Profile Picture" style="height:50px; width:50px; border-radius:50%;">
                        <?php else: ?>
                            <img class="author_pic" src="default_pic.svg" alt="Profile Picture" style="height:50px; width:50px; border-radius:50%;">
                        <?php endif; ?>

                        <div class="user_post_info">
                            <p class="post_username">
                                <a class="post_uname" href="view_user.php?username=<?php echo urlencode($post['Username']); ?>">
                                    <?php echo htmlspecialchars($post['Username']); ?>
                                </a>
                            </p>
                            <p class="post_time" style="font-size:smaller;">posted at: <?php echo htmlspecialchars($post['CreatedAt']); ?></p>
                            <p class="post_time">updated at: <?php echo htmlspecialchars($post['UpdatedAt']); ?></p>
                        </div>
                    </div>

                    <hr/>
                    <h3 class="post_title" style = "text-align:center;"><?php echo htmlspecialchars($post['Title']); ?></h3>
                    <hr/>

                    <p class="post_content" style = "display:none;"><?php echo htmlspecialchars($post['Content']); ?></p>

                    <div class="lik" style="display:flex; padding:10px;">

                        <form class="like" action="like_post.php" method="POST" style="margin:0;">
                            <input type="hidden" name="postID" value="<?php echo $post['PostID']; ?>">
                            <button type="submit" class="like-btn" name="like" style="background-color:transparent; border:none; padding: 10px;">
                                <img class="bulb" src="bulb.svg" style="height:30px; width:30px;">
                            </button>
                        </form>

                        <span class="like-count" style="display:flex; align-self:center; color:#007bff;">
                            <?php echo getLikeCount($post['PostID']); ?>
                        </span>

                        <button class="like-btn" style="background-color:transparent; border:none; padding: 10px;">
                            <img class="bulb" src="comment.svg" style="height:30px; width:30px; background-color:transparent; outline:none; background-color:transparent; border:none;">
                        </button>

                        <span class="like-count" style="display:flex; align-self:center; color:#007bff;">
                            <?php echo countComments($post['PostID']); ?>
                        </span>

                        <button class="like-btn" style="background-color:transparent; border:none; padding: 10px;">
                            <a href="view_post.php?id=<?php echo $post['PostID']; ?>">
                                <img class="bulb" src="view.svg" style="height:30px; width:30px; background-color:transparent; outline:none; background-color:transparent; border:none;">
                            </a>
                        </button>

                    </div>

                </div>

            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No posts found from <?php echo $sort_option == 'followers' ? "your followers" : "users you follow"; ?>.</p>
    <?php endif; ?>
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
