<?php
session_start();
require_once 'functions.php';

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
</head>

<body>
    <ul class="navbar">
        <form class="nav" action="" method="GET" id="searchForm" style="display: flex;">
            <input class="search-input" type="text" id="search" name="search" placeholder="Search a topic...">
        </form>
        <div class="btn-search">
            <button class="search-button" id="searchBtn">Search</button>
        </div>
    </ul>
    <!-- Logo Navigation Bar -->
    <?php include 'navbar.php';?>
    

    <!-- Main Content -->
    <div class="container">
        
        <?php if (isset($_SESSION['username'])): ?>

            <!-- Create Post Form -->
            <div class="post-form">

                <label class="create-label">Create A Post</label>

                <form id="post-form" action="create_post_process.php" method="POST" enctype="multipart/form-data">
                    <input class="post-title-in" type="text" id="title" name="title" placeholder="Title..." required>
                    <textarea class="post-content-in" id="content" name="content" placeholder="What am I thinking?..." required></textarea>
                    <input type="file" id = "photo" name="photo" accept="image/*"> <!-- Add photo input -->
                    <input type="submit" class="post-postbtn-in" value="Post">
                </form>


            </div>

            <!-- Sort Dropdown -->
            <div class="dropdown">

                <button class="dropbtn">Sort post by</button>

                <div class="dropdown-content">
                    <a href="?sort=time">Newest</a>
                    <a href="?sort=date">Oldest</a>
                    <a href="?sort=comments">Most Popular</a>
                    <a href="?sort=Bpts">Highest Brilliant Points</a>
                </div>

            </div><br><br>

            <!-- Display Posts -->
            <?php

            if (isset($_GET['search']) && !empty($_GET['search'])) 
            {
                $search = $_GET['search'];
                $posts = searchPosts($search);
                echo '<h3 class="search_results">Search Results</h3>';
            } 
            else 
            {
                $posts = getRecentPosts();
            }


            if (isset($_GET['sort'])) 
            {
                $sort = $_GET['sort'];
                switch ($sort) 
                {
                    case 'time':
                        $posts = getPostsSortedByTime();
                        break;
                    case 'date':
                        $posts = getPostsSortedByDate();
                        break;
                    case 'comments':
                        $posts = getPostsSortedByComments();
                        break;
                    case 'Bpts':
                        $posts = getPostsSortedByBPTS();
                        break;
                    default:
                        $posts = getRecentPosts();
                        break;
                }
            }

            if ($posts):

                foreach ($posts as $post):

                    $userProfile = getUserProfileById($post['UserID']);
                    $profilePic = $userProfile['ProfilePic'];

            ?>
                    <div class="post-container">

                        <div class="post">

                            <div class="pic_user" style = "display:flex;">

                                <?php if (!empty($profilePic)): ?>
                                    <img class="author_pic" src="<?php echo $profilePic; ?>" alt="Profile Picture" style = "height:50px; width:50px; border-radius:50%;">
                                <?php else: ?>
                                    <img class="author_pic" src="default_pic.svg" alt="Profile Picture" style = "height:50px; width:50px; border-radius:50%;">
                                <?php endif; ?>

                                <div class="user_post_info">

                                    <p class="post_username"><a class="post_uname" href="view_user.php?username=<?php echo urlencode($post['Username']); ?>"><?php echo $post['Username']; ?></a></p>
                                    <p class="post_time" style = "font-size:smaller;">posted at: <?php echo $post['CreatedAt']; ?></p>
                                    <p class="post_time">updated at: <?php echo $post['updatedAt']; ?></p>
                                </div>

                            </div>

                            <hr/>
                            <h3 class="post_title"><?php echo $post['Title']; ?></h3>
                            <hr/>

                            <p class="post_content"><?php echo $post['Content']; ?></p>

                            <div class="lik" style = "display:flex; padding:10px;">

                                <form class="like" action="like_post.php" method="POST" style = "margin:0;">
                                    <input type="hidden" name="postID" value="<?php echo $post['PostID']; ?>">
                                    <button type="submit" class="like-btn" name="like" style = "background-color:transparent; border:none; padding: 10px;"><img class="bulb" src="bulb.svg" style = "height:30px; width:30px;"></button>
                                </form>

                                <span class="like-count" style = "display:flex; align-self:center; color:#007bff;"><?php echo getLikeCount($post['PostID']); ?></span>

                                <button class="like-btn" style = "background-color:transparent; border:none; padding: 10px;"><img class="bulb" src="comment.svg" style = "height:30px; width:30px; background-color:transparent; outline:none; border:none;"></button>

                                <span class="like-count" style = "display:flex; align-self:center; color:#007bff;"><?php echo countComments($post['PostID']); ?></span>

                                <button class="like-btn" style = "background-color:transparent; border:none; padding: 10px;"><a href="view_post.php?id=<?php echo $post['PostID']; ?>"><img class="bulb" src="view.svg" style = "height:30px; width:30px; background-color:transparent; outline:none; border:none;"></a></button>

                                <!--<span class="like-count" >see thread</span>-->

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

</body>

</html>