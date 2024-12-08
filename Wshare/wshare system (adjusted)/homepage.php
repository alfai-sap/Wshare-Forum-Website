<?php
session_start();
require_once 'functions.php';
require_once 'changes.php';
require_once 'notifications_functions.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wshare Home</title>
    <!--<link rel="stylesheet" href="./css/homepage.css">-->
    <link rel="stylesheet" href="./css/left-navbar.css ?v=<?php echo time(); ?>" >
    <link rel="stylesheet" href="./css/homepage.css ?v=<?php echo time(); ?>" >
    <link rel="stylesheet" href="./css/navbar.css ?v=<?php echo time(); ?>" >
    <link rel="stylesheet" href="./css/right-sidebar.css ?v=<?php echo time(); ?>" >
    <link rel="stylesheet" href="./css/notifications.css ?v=<?php echo time(); ?>" >
    <link rel="stylesheet" href="./css/modal.css ?v=<?php echo time(); ?>" >
</head>

<body></body>
    <ul class="navbar">
        <form class="nav" action="" method="GET" id="searchForm" style="display: flex;">
            <input class="search-input" type="text" id="search" name="search" placeholder="Search a topic..." style="width: 670px;">
            <select name="tag" id="tag">
                <option value="">Select Tag</option>
                <?php
                // Fetch all tags from the database
                $tagsQuery = "SELECT TagName FROM tags";
                $tagsResult = $conn->query($tagsQuery);
                while ($tag = $tagsResult->fetch_assoc()) {
                    echo '<option value="' . htmlspecialchars($tag['TagName']) . '">' . htmlspecialchars($tag['TagName']) . '</option>';
                }
                ?>
            </select>
            <button class="search-button" id="searchBtn">Search</button>
        </form>
    </ul>

    

    <!-- Logo Navigation Bar -->
    <?php include 'navbar.php';?>
    <?php include 'right-sidebar.php';?>
    <!-- Main Content -->
    <div class="container">
        
    <?php if (isset($_SESSION['username'])): 
            
    ?>
        

        <div class="filter-row">
            <?php if (isset($_SESSION['username'])): 
                if (!checkUserBan()): ?>
                    <button class="dropbtn" style="background-color:#007bff;">
                        <a href="create_post.php" style="text-decoration: none; color:#000000; width:200px; color:#ffffff;">Create post</a>
                    </button>
                <?php else: ?>
                    <div class="ban-message" style="color: red; margin: 10px;">
                        <?php echo checkUserBan(true); ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>

            
            <div class="dropdown">
                <button class="dropbtn">Sort post by</button>
                <div class="dropdown-content">
                    <a href="?sort=time">Newest</a>
                    <a href="?sort=date">Oldest</a>
                    <a href="?sort=comments">Most Popular</a>
                    <a href="?sort=Bpts">Highest Brilliant Points</a>
                </div>
            </div>

            <form action="" method="GET" id="filterForm" class="timeframe-filter">
                <select name="timeframe" id="timeframe" class="filter-select">
                    <option value="">Time Frame</option>
                    <option value="today">Today</option>
                    <option value="week">This Week</option>
                    <option value="month">This Month</option>
                    <option value="year">This Year</option>
                </select>
                <button type="submit" class="filter-button">Apply</button>
            </form>
        </div>
        <br><br>
        <!-- Advanced Filter Section -->
        
        
        <!-- Display Posts -->
        <?php
                        
            $search = isset($_GET['search']) ? $_GET['search'] : '';
            $tag = isset($_GET['tag']) ? $_GET['tag'] : '';

            if (!empty($search) || !empty($tag)) {
                $posts = searchAndFilterPosts($search, $tag);
                echo '<h3 class="search_results">Search Results</h3>';
            } else {
                $posts = getRecentPosts();
            }

            if (isset($_GET['sort']) || isset($_GET['timeframe']) || isset($_GET['tag'])) {
                $sort = $_GET['sort'] ?? '';
                $timeframe = $_GET['timeframe'] ?? '';
                $tag = $_GET['tag'] ?? '';
                
                $posts = getFilteredPosts($sort, $timeframe, $tag);
            } else {
                $posts = getRecentPosts();
            }


            if ($posts):

                foreach ($posts as $post):

                    $userProfile = getUserProfileById($post['UserID']);
                    $profilePic = $userProfile['ProfilePic'];

            ?>
                    <div class="post-container">

                        <div class="post">

                            <div class="pic_user" style="display:flex;">
                                <?php if (!empty($profilePic)): ?>
                                    <img class="author_pic" src="<?php echo $profilePic; ?>" alt="Profile Picture">
                                <?php else: ?>
                                    <img class="author_pic" src="default_pic.svg" alt="Profile Picture">
                                <?php endif; ?>

                                <div class="user_post_info">
                                    <div style="display: flex;">
                                        <p class="post_username">
                                            <a class="post_uname" href="view_user.php?username=<?php echo urlencode($post['Username']); ?>">
                                                <?php echo $post['Username']; ?>
                                            </a>
                                        </p>
                                        <p class="post_time" style="font-size:smaller; padding-top:9px; margin-left:2px;">
                                            <?php echo timeAgo($post['CreatedAt']); ?>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Display tags associated with the post -->
                            <?php 
                                                                                        
                                $postID = $post['PostID'];

                                // Query to get tags associated with the post
                                $tagsQuery = "SELECT t.TagName FROM post_tags pt
                                            INNER JOIN tags t ON pt.TagID = t.TagID
                                            WHERE pt.PostID = ?";
                                $tagsStmt = $conn->prepare($tagsQuery);
                                $tagsStmt->bind_param('i', $postID);
                                $tagsStmt->execute();
                                $tagsResult = $tagsStmt->get_result();

                                $tags = [];
                                while ($row = $tagsResult->fetch_assoc()) {
                                    $tags[] = $row['TagName'];
                                }

                                $tagsStmt->close();

                                if (!empty($tags)): ?>
                                    <div class="post-tags">
                                        <?php foreach ($tags as $tag): ?>
                                            <span class="tag-label"><?php echo htmlspecialchars($tag); ?></span>
                                        <?php endforeach; ?>
                                    </div>
                            <?php endif; ?>

                            <h3 class="post_title"><?php echo $post['Title']; ?></h3>


                            

                            <p class="post_content" style="display:none;"><?php echo formatParagraph($post['Content'], 1000, 50); ?></p>

                            <!-- Check if the post has images -->
                            <?php 
                                $images = [];
                                if (!empty($post['PhotoPath'])) {
                                    // First add the main photo from posts table if it exists
                                    $images[] = $post['PhotoPath'];
                                }
                                
                                // Then add any additional images from post_images table
                                $stmt = $conn->prepare("SELECT ImagePath FROM post_images WHERE PostID = ? ORDER BY DisplayOrder");
                                $stmt->bind_param('i', $post['PostID']);
                                $stmt->execute();
                                $imgResult = $stmt->get_result();
                                
                                while($img = $imgResult->fetch_assoc()) {
                                    $images[] = $img['ImagePath'];
                                }
                                $stmt->close();

                                if (!empty($images)): ?>
                                    <div class="post-images-container" data-images="<?php echo htmlspecialchars(implode(',', $images)); ?>">
                                        <?php foreach($images as $index => $image): ?>
                                            <div class="post-image-slide <?php echo $index === 0 ? 'active' : ''; ?>">
                                                <img class="post-image-img" src="<?php echo htmlspecialchars(trim($image)); ?>" alt="Post Image">
                                            </div>
                                        <?php endforeach; ?>
                                        
                                        <?php if(count($images) > 1): ?>
                                            <button class="slide-nav prev" data-direction="prev">&lt;</button>
                                            <button class="slide-nav next" data-direction="next">&gt;</button>
                                            <div class="image-counter">1/<?php echo count($images); ?></div>
                                        <?php endif; ?>
                                        
                                        <div class="gallery-overlay"></div>
                                    </div>
                            <?php endif; ?>

                            <div class="lik" style="display:flex; padding:10px;">
                                <form class="like" action="like_post.php" method="POST" style="margin:0;">
                                    <input type="hidden" name="postID" value="<?php echo $post['PostID']; ?>">
                                    <button type="submit" class="like-btn" name="like" style="background-color:transparent; border:none; padding: 10px;">
                                        <?php if (hasUserLikedPost($post['PostID'], $_SESSION['user_id'])): ?>
                                            <img class="bulb" src="bulb_active.svg" style="height:20px; width:20px;">
                                        <?php else: ?>
                                            <img class="bulb" src="bulb.svg" style="height:20px; width:20px;">
                                        <?php endif; ?>
                                    </button>
                                </form>

                                <span class="like-count" style="display:flex; align-self:center; color:#007bff;">
                                    <?php echo getLikeCount($post['PostID']); ?> Brilliant Points
                                </span>

                                <button class="like-btn" style="background-color:transparent; border:none; padding: 10px;">
                                    <img class="bulb" src="comment.svg" style="height:20px; width:20px; background-color:transparent; outline:none; border:none;">
                                </button>

                                <span class="like-count" style="display:flex; align-self:center; color:#007bff;">
                                    <?php echo countComments($post['PostID']); ?> Comments
                                </span>

                                <button class="like-btn" style="background-color:transparent; border:none; padding: 10px;">
                                    <a href="view_post.php?id=<?php echo $post['PostID']; ?>" style="display:flex; align-self:center; text-decoration:none;">
                                        <img class="bulb" src="view.svg" style="height:20px; width:20px; background-color:transparent; outline:none; border:none;">
                                        <p class="like-count" style="display:flex; align-self:center; color:#007bff; margin-left:5px;">See discussion</p>
                                    </a>
                                </button>

            

                            </div>

                        </div>
                    </div>

                <?php endforeach; ?>

            <?php else: ?>

                <h4 style="color: #007bff; text-align:center; padding-top:200px; padding-bottom:200px;">No topic yet... you may start the topic by posting.</h4>

            <?php endif; ?>

        <?php else: ?>

            <?php
                header('Location: guest.php');
                exit;
            ?>

        <?php endif; ?>

    </div>

    

    <!-- Image Modal -->
    <div id="imageModal" class="image-modal">
        <span class="close-modal" title="Close">&times;</span>
        <button class="nav-btn prev-btn" title="Previous">&lt;</button>
        <div class="modal-content">
            <img id="modalImage" src="" alt="Modal Image" draggable="false">
        </div>
        <button class="nav-btn next-btn" title="Next">&gt;</button>
        <div class="image-counter"></div>
    </div>

    <!-- JavaScript -->
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

    <script>
        document.getElementById("searchBtn").addEventListener("click", function() {
            document.getElementById("searchForm").submit();
        });
    </script>


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="./javascripts/index.js"></script>
    <script src="./javascripts/modal.js"></script>

</body>

</html>