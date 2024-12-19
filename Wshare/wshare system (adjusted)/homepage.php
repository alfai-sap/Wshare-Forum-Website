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
    <link rel="stylesheet" href="./css/left-navbar.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="./css/homepage.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="./css/navbar.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="./css/right-sidebar.css?v=<?php echo time(); ?>">
    <!--<link rel="stylesheet" href="./css/notifications.css?v=<?php echo time(); ?>">-->
    <link rel="stylesheet" href="./css/modal.css?v=<?php echo time(); ?>">
</head>

<body>
    <ul class="navbar">
        <div class="nav" style="display: flex;">
            <input class="search-input" type="text" id="search" name="search" 
                   placeholder="Search posts..." 
                   value="<?php echo htmlspecialchars($search ?? ''); ?>" 
                   style="width: 900px;"
                   autocomplete="off">
        </div>
    </ul>

    <!-- Logo Navigation Bar -->
    <?php include 'navbar.php';?>
    <?php include 'right-sidebar.php';?>
    <!-- Main Content -->
    <div class="container">
        
    <?php if (isset($_SESSION['username'])): ?>
        <div class="filter-row">
            <?php if (isset($_SESSION['username'])): ?>
                <?php if (checkUserBan(true)): ?>
                    <div class="ban-message" style="color: red; margin: 10px 0;">
                        <?php echo checkUserBan(true); ?>
                    </div>
                <?php else: ?>
                    <button class="dropbtn" style="background-color:#007bff;">
                        <a href="create_post.php" style="text-decoration: none; color:#ffffff;">Create post</a>
                    </button>
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
                <select name="tag" id="tag" class="filter-select">
                    <option value="">Filter by Tag</option>
                    <?php
                    // Fetch all tags from the database
                    $tagsQuery = "SELECT TagName FROM tags";
                    $tagsResult = $conn->query($tagsQuery);
                    while ($tag = $tagsResult->fetch_assoc()) {
                        $selected = (isset($_GET['tag']) && $_GET['tag'] === $tag['TagName']) ? 'selected' : '';
                        echo '<option value="' . htmlspecialchars($tag['TagName']) . '" ' . $selected . '>' 
                             . htmlspecialchars($tag['TagName']) . '</option>';
                    }
                    ?>
                </select>
                <select name="timeframe" id="timeframe" class="filter-select">
                    <option value="">Time Frame</option>
                    <option value="today" <?php echo (isset($_GET['timeframe']) && $_GET['timeframe'] === 'today') ? 'selected' : ''; ?>>Today</option>
                    <option value="week" <?php echo (isset($_GET['timeframe']) && $_GET['timeframe'] === 'week') ? 'selected' : ''; ?>>This Week</option>
                    <option value="month" <?php echo (isset($_GET['timeframe']) && $_GET['timeframe'] === 'month') ? 'selected' : ''; ?>>This Month</option>
                    <option value="year" <?php echo (isset($_GET['timeframe']) && $_GET['timeframe'] === 'year') ? 'selected' : ''; ?>>This Year</option>
                </select>
                <button type="submit" class="filter-button">Apply Filters</button>
            </form>
        </div>
        <br><br>
        <!-- Advanced Filter Section -->
        
        <!-- Display Posts -->
        <?php
            $search = isset($_GET['search']) ? $_GET['search'] : '';
            $tag = isset($_GET['tag']) ? $_GET['tag'] : '';
            
            if (!empty($search)) {
                $posts = searchAndFilterPosts($search);
                $postCount = count($posts);
                echo '<div class="search_results">
                        Results for "<span class="query">' . htmlspecialchars($search) . '</span>"
                        <span class="count">' . $postCount . ' ' . ($postCount === 1 ? 'result' : 'results') . ' found</span>
                      </div>';
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
                            <!-- Display documents -->
                            <?php
                            $documents = getDocumentsByPostId($post['PostID']);
                            if (!empty($documents)): ?>
                                <div class="post-documents">
                                    <ul>
                                        <?php foreach ($documents as $document): ?>
                                            <li><a href="<?php echo htmlspecialchars($document, ENT_QUOTES, 'UTF-8'); ?>" target="_blank"><?php echo basename($document); ?></a></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php endif; ?>
                            <!-- Check if the post has images or videos -->
                            <?php 
                                // Create a combined media array with both images and videos
                                $media = [];
                                
                                // Add main photo if exists
                                if (!empty($post['PhotoPath'])) {
                                    $media[] = [
                                        'type' => 'image',
                                        'path' => $post['PhotoPath']
                                    ];
                                }

                                // Add additional images
                                $stmt = $conn->prepare("SELECT ImagePath FROM post_images WHERE PostID = ? ORDER BY DisplayOrder");
                                $stmt->bind_param('i', $post['PostID']);
                                $stmt->execute();
                                $imgResult = $stmt->get_result();
                                while($img = $imgResult->fetch_assoc()) {
                                    $media[] = [
                                        'type' => 'image',
                                        'path' => $img['ImagePath']
                                    ];
                                }
                                $stmt->close();

                                // Add videos
                                $stmt = $conn->prepare("SELECT VideoPath FROM post_videos WHERE PostID = ?");
                                $stmt->bind_param('i', $post['PostID']);
                                $stmt->execute();
                                $vidResult = $stmt->get_result();
                                while($video = $vidResult->fetch_assoc()) {
                                    $media[] = [
                                        'type' => 'video',
                                        'path' => $video['VideoPath']
                                    ];
                                }
                                $stmt->close();

                                if (!empty($media)): ?>
                                    <div class="post-media-container">
                                        <div class="post-media-slideshow" data-media-count="<?php echo count($media); ?>">
                                            <div class="media-progress-bar">
                                                <?php foreach($media as $index => $item): ?>
                                                    <div class="progress-dot <?php echo $index === 0 ? 'active' : ''; ?>" data-index="<?php echo $index; ?>"></div>
                                                <?php endforeach; ?>
                                            </div>
                                            <div class="media-wrapper">
                                                <?php foreach($media as $index => $item): ?>
                                                    <div class="media-slide <?php echo $index === 0 ? 'active' : ''; ?>" data-index="<?php echo $index; ?>">
                                                        <?php if ($item['type'] === 'image'): ?>
                                                            <img class="post-media-img" src="<?php echo htmlspecialchars(trim($item['path'])); ?>" alt="Post Image">
                                                        <?php else: ?>
                                                            <video class="post-media-video" controls>
                                                                <source src="<?php echo htmlspecialchars($item['path']); ?>" type="video/mp4">
                                                                Your browser does not support the video tag.
                                                            </video>
                                                        <?php endif; ?>
                                                    </div>
                                                <?php endforeach; ?>
                                                <?php if(count($media) > 1): ?>
                                                    <div class="nav-area nav-left"></div>
                                                    <div class="nav-area nav-right"></div>
                                                    <div class="media-counter">1/<?php echo count($media); ?></div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
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
                                <span class="like-count" style="display:flex; align-self:center; color:#0056b3;">
                                    <?php echo getLikeCount($post['PostID']); ?> Brilliant Points
                                </span>
                                <button class="like-btn" style="background-color:transparent; border:none; padding: 10px;">
                                    <img class="bulb" src="comment.svg" style="height:20px; width:20px; background-color:transparent; outline:none; border:none;">
                                </button>
                                <span class="like-count" style="display:flex; align-self:center; color:#0056b3;">
                                    <?php echo countComments($post['PostID']); ?> Comments
                                </span>
                                <button class="like-btn" style="background-color:transparent; border:none; padding: 10px;">
                                    <a href="view_post.php?id=<?php echo $post['PostID']; ?>" style="display:flex; align-self:center; text-decoration:none;">
                                        <img class="bulb" src="view.svg" style="height:20px; width:20px; background-color:transparent; outline:none; border:none;">
                                        <p class="like-count" style="display:flex; align-self:center; color:#0056b3; margin-left:5px;">See discussion</p>
                                    </a>
                                </button>
                            </div>
                        </div>
                    </div>
        <?php
                endforeach;
            else:
        ?>
            <h4 style="color: #007bff; text-align:center; padding-top:200px; padding-bottom:200px;">No topic yet... you may start the topic by posting.</h4>
        <?php
            endif;
        ?>
        
    <?php else: ?>

        <?php
            header('Location: guest.php');
            exit;
        ?>

    <?php endif; ?>

    </div>

    <script src="javascripts/navbar.js"></script>
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
    
    <script>
        // Media slideshow functionality
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.post-media-slideshow').forEach(container => {
                const slides = container.querySelectorAll('.media-slide');
                const dots = container.querySelectorAll('.progress-dot');
                const counter = container.querySelector('.media-counter');
                let currentIndex = 0;

                // Navigation click handlers
                container.querySelector('.nav-area.nav-left')?.addEventListener('click', (e) => {
                    e.stopPropagation();
                    currentIndex = (currentIndex - 1 + slides.length) % slides.length;
                    updateMedia();
                });

                container.querySelector('.nav-area.nav-right')?.addEventListener('click', (e) => {
                    e.stopPropagation();
                    currentIndex = (currentIndex + 1) % slides.length;
                    updateMedia();
                });

                // Progress dot click handlers
                dots.forEach((dot, index) => {
                    dot.addEventListener('click', (e) => {
                        e.stopPropagation();
                        currentIndex = index;
                        updateMedia();
                    });
                });

                function updateMedia() {
                    // Pause all videos when switching slides
                    container.querySelectorAll('video').forEach(video => {
                        video.pause();
                    });

                    // Hide all slides
                    slides.forEach(slide => slide.classList.remove('active'));
                    dots.forEach(dot => dot.classList.remove('active'));

                    // Show current slide
                    slides[currentIndex].classList.add('active');
                    dots[currentIndex].classList.add('active');

                    // Update counter
                    if (counter) {
                        counter.textContent = `${currentIndex + 1}/${slides.length}`;
                    }
                }
            });
        });
    </script>

    <script>
        const searchInput = document.getElementById('search');
        const postsContainer = document.querySelector('.container');
        let typingTimer;
        const doneTypingInterval = 300; // Wait for 300ms after user stops typing

        searchInput.addEventListener('input', function() {
            clearTimeout(typingTimer);
            if (this.value) {
                typingTimer = setTimeout(performSearch, doneTypingInterval);
            } else {
                // If search is empty, show all posts
                window.location.href = 'homepage.php';
            }
        });

        function performSearch() {
            const query = searchInput.value;
            
            // Create URL with search parameter
            const url = `search_posts.php?query=${encodeURIComponent(query)}`;
            
            // Fetch results
            fetch(url)
                .then(response => response.text())
                .then(html => {
                    // Replace posts container content with search results
                    postsContainer.innerHTML = html;
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }
    </script>

    <style>
        .nav-area {
            position: absolute;
            top: 0;
            height: 100%;
            width: 30%;
            cursor: pointer;
            z-index: 10;
        }
        .nav-area:hover {
            background: rgba(0, 0, 0, 0.1);
        }
        .nav-left {
            left: 0;
        }
        .nav-right {
            right: 0;
        }
        .post-images-container {
            position: relative;
        }
        .images-wrapper {
            position: relative;
        }
    </style>
    </style>
</body>
</html>
</body>
</html>