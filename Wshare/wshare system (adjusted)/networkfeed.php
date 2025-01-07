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

// Handle search query
$searchQuery = isset($_GET['search']) ? $_GET['search'] : '';


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
    <!--<link rel="stylesheet" href="./css/notifications.css  ?v=<?php echo time(); ?>">-->
    
</head>
<body>

<!-- Replace the existing search input -->
<ul class="navbar">
    <div class="nav" style="display: flex;">
        <input class="search-input" type="text" id="networkSearch" 
               placeholder="Search network posts..." 
               style="width: 900px;">
    </div>
</ul>


<?php include 'navbar.php';?>

<div class="filter-row">
    <form method="GET" action="" id="filterForm" class="timeframe-filter">
        <select name="sort" id="sort" class="filter-select" onchange="updateSearch()">
            <option value="followed" <?php echo ($sort_option == 'followed') ? 'selected' : ''; ?>>From Followed</option>
            <option value="followers" <?php echo ($sort_option == 'followers') ? 'selected' : ''; ?>>From Followers</option>
        </select>
    </form>
</div>

    <div class = "container">
    <!--<h1>Posts from Your Network</h1>-->

    <br>
    <br>
    <br>
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

                    <div class="pic_user" style = "display:flex;">

                        <?php if (!empty($profilePic)): ?>
                            <img class="author_pic" src="<?php echo $profilePic; ?>" alt="Profile Picture">
                        <?php else: ?>
                            <img class="author_pic" src="default_pic.svg" alt="Profile Picture">
                        <?php endif; ?>

                        <div class="user_post_info">
                            <div style="display: flex;">
                                <p class="post_username"><a class="post_uname" href="view_user.php?username=<?php echo urlencode($post['Username']); ?>"><?php echo $post['Username']; ?></a></p>
                                <p class="post_time" style = "font-size:smaller; padding-top:9px; margin-left:2px;"><?php echo timeAgo($post['CreatedAt']); ?></p>
                            </div>
                        </div>

                    </div>

                    
                    <h3 class="post_title" ><?php echo $post['Title']; ?></h3>
                    

                    <p class="post_content"><?php echo $post['Content']; ?></p>

                    <?php if (!empty($post['PhotoPath'])):?>
                    <img src="<?php echo $post['PhotoPath']; ?>" alt="Post Image" class="post-image" style = "width:100%; height:100%;">
                    <?php endif; ?>
                    <div class="lik" style = "display:flex; padding:10px;">
                        <form class="like" action="like_post.php" method="POST" style = "margin:0;">
                            <input type="hidden" name="postID" value="<?php echo $post['PostID']; ?>">
                            <button type="submit" class="like-btn" name="like" style = "background-color:transparent; border:none; padding: 10px;">
                                <?php if (hasUserLikedPost($post['PostID'], $user_id)): ?>
                                    <img class="bulb" src="bulb_active.svg" style = "height:20px; width:20px;">
                                <?php else: ?>
                                    <img class="bulb" src="bulb.svg" style = "height:20px; width:20px;">
                                <?php endif; ?>
                            </button>
                        </form>

                        <span class="like-count" style = "display:flex; align-self:center; color:#0056b3;"><?php echo getLikeCount($post['PostID']); ?> Brilliant Points</span>

                        <button class="like-btn" style = "background-color:transparent; border:none; padding: 10px;"><img class="bulb" src="comment.svg" style = "height:20px; width:20px; background-color:transparent; outline:none; border:none;"></button>

                        <span class="like-count" style = "display:flex; align-self:center; color:#0056b3;"><?php echo countComments($post['PostID']); ?> Comments</span>

                        <button class="like-btn" style = "background-color:transparent; border:none; padding: 10px;"><a href="view_post.php?id=<?php echo $post['PostID']; ?>" style = "display:flex; align-self:center; text-decoration:none;"><img class="bulb" src="view.svg" style = "height:20px; width:20px; background-color:transparent; outline:none; border:none;"><p class="like-count" style = "display:flex; align-self:center; color:#0056b3; margin-left:5px;"> See disscussion</p></a> </button>

                        <!--<span class="like-count" >see thread</span>-->

                    </div>

                </div>

            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No posts found from <?php echo $sort_option == 'followers' ? "your followers" : "users you follow"; ?>.</p>
    <?php endif; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('networkSearch');
    const posts = document.querySelectorAll('.post-container');
    const resultsContainer = document.createElement('div');
    resultsContainer.className = 'search-results-info';
    document.querySelector('.container').insertBefore(resultsContainer, document.querySelector('.post-container'));

    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase().trim();
        let visibleCount = 0;

        posts.forEach(post => {
            const title = post.querySelector('.post_title').textContent.toLowerCase();
            const content = post.querySelector('.post_content').textContent.toLowerCase();
            const username = post.querySelector('.post_uname').textContent.toLowerCase();

            if (title.includes(searchTerm) || 
                content.includes(searchTerm) || 
                username.includes(searchTerm)) {
                post.style.display = 'block';
                visibleCount++;
            } else {
                post.style.display = 'none';
            }
        });

        // Update search results info
        if (searchTerm !== '') {
            resultsContainer.innerHTML = `
                <div class="search_results">
                    Results for "<span class="query">${searchTerm}</span>"
                    <span class="count">${visibleCount} ${visibleCount === 1 ? 'result' : 'results'} found</span>
                </div>
            `;
            resultsContainer.style.display = 'block';
        } else {
            resultsContainer.style.display = 'none';
            posts.forEach(post => post.style.display = 'block');
        }

        // Show no results message
        const noResultsMsg = document.querySelector('.no-results-message') || document.createElement('div');
        noResultsMsg.className = 'no-results-message';
        
        if (visibleCount === 0 && searchTerm !== '') {
            noResultsMsg.innerHTML = '<h4 style="color: #007bff; text-align:center; padding:20px;">No results found</h4>';
            if (!document.querySelector('.no-results-message')) {
                document.querySelector('.container').appendChild(noResultsMsg);
            }
        } else if (document.querySelector('.no-results-message')) {
            document.querySelector('.no-results-message').remove();
        }
    });
});
</script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="./javascripts/index.js"></script>



</body>
</html>
