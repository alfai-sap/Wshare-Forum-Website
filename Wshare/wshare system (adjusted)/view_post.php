<?php
require_once "functions.php";
require_once "changes.php"; // Add this line
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Post</title>
    <link rel="stylesheet" href="./css/view_post.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="./css/navbar.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="./css/left-navbar.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="./css/modal.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="./css/notifications.css?v=<?php echo time(); ?>">

    <script>
    // Save the previous page's URL when the page is loaded
    if (document.referrer && !localStorage.getItem('previousPage')) {
        localStorage.setItem('previousPage', document.referrer);
    }
    </script>

</head>
<body>
    <?php include 'navbar.php'; ?>

<div class="container">

    <a class="backButton" id="backButton">
        <div class="back"><p class="back-label">Back</p></div>
    </a>

    
    <?php
    // Check if post ID is provided in the URL
    if (isset($_GET['id'])) {
        $postId = $_GET['id'];
        $post = getPostById($postId);
        $comments = getCommentsByPostId($postId);
        $userProfile = getUserProfileById($post['UserID']);
        $profilePic = $userProfile['ProfilePic'];
        $user = getUserByUsername($_SESSION['username']);
    ?>
        <div class="post-container">
        <div class="post">
        <?php if (hasUserLikedPost($post['PostID'], $_SESSION['user_id'])): ?>
                <div class="liked-message" style="background-color: #e0f7fa; padding: 10px; border-radius: 5px; margin-bottom: 30px;">
                    <p style="margin: 0; color: #00796b;">You liked this post on <?php echo date('F j, Y', strtotime(getLikeDate($post['PostID'], $_SESSION['user_id']))); ?></p>
                </div>
            <?php endif; ?>
            <div class="author-info">
                <?php if (!empty($profilePic)): ?>
                    <img class="author_pic" src="<?php echo $profilePic; ?>" alt="Profile Picture">
                <?php else: ?>
                    <img class="author_pic" src="default_pic.svg" alt="Profile Picture">
                <?php endif; ?>
                
                <div class="unametime" style="display:flex; flex-direction: column;">
                    <div class="unam-time" style="display: flex;">
                        <p class="authorname"><?php echo $post['Username']; ?></p>
                        <p class="timestamp"><?php echo timeAgo($post['CreatedAt']); ?></p>
                    </div>
                    
                    <p class="timestamp-update">edited <?php echo timeAgo($post['updatedAt']); ?></p>
                </div>
            </div>
                    
            
                    
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
            <h3><?php echo $post['Title']; ?></h3>
            
            <p class="post-content"><?php echo $post['Content']; ?></p>
    
                  
            <?php if (!empty($post['PhotoPath'])): ?>
                <?php
                    $images = [];
                    if (!empty($post['PhotoPath'])) {
                        $images[] = $post['PhotoPath'];
                    }
                    
                    // Get additional images from post_images table
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
            <?php endif; ?>

            <div class="lik">
                <form class="like" action="like_post.php" method="POST">
                    <input type="hidden" name="postID" value="<?php echo $post['PostID']; ?>">
                    <button type="submit" class="like-btn" name="like">
                        <?php if (hasUserLikedPost($post['PostID'], $_SESSION['user_id'])): ?>
                            <img class="bulb" src="bulb_active.svg">
                        <?php else: ?>
                            <img class="bulb" src="bulb.svg">
                        <?php endif; ?>
                    </button>
                </form>

                <span class="like-count"><?php echo getLikeCount($post['PostID']); ?> Brilliant Points</span>
                
                <button class="like-btn"><img class="bulb" src="comment.svg"></button>

                <span class="like-count"><?php echo countComments($post['PostID']); ?> Comments</span>

                <button class="like-btn" style="background-color:transparent; border:none; padding: 10px;">
                    <a href="report_form.php?type=post&id=<?php echo $post['PostID']; ?>" style="display:flex; align-self:center; text-decoration:none;">
                        <img class="bulb" src="report.svg" style="height:25px; width:25px; background-color:transparent; outline:none; border:none;">
                        <p class="like-count" style="display:flex; align-self:center; color:#ff0000; margin-left:5px;">Report</p>
                    </a>
                </button>
            </div>
        </div>
    
        <?php if ($comments): ?>
            <div id="comments-label" class="comments-label">
                <h4>Comments</h4>
                <p><img id="comments-label-icon" class="comments-label-icon" src="show.svg"></p>
            </div>

            <div class="comments" id="comments">
                <?php foreach ($comments as $comment): ?>
                    <div class="comment">
                        <div class="comments_author">
                            <div class="comments_author_uname_content">
                                <?php if (!empty($comment['ProfilePic'])): ?>
                                    <img class="comments_author_pfp" src="<?php echo $comment['ProfilePic']; ?>">
                                <?php else: ?>
                                    <img class="comments_author_pfp" src="default_pic.svg">
                                <?php endif; ?>
    
                                <div class="comments_author_uname_time">
                                    <p class="comments_author_uname"><strong><?php echo $comment['Username']; ?></strong></p>
                                    <p class="comment_timestamp"><?php echo timeAgo($comment['CreatedAt']); ?></p>
                                </div>
                            </div>
                            <p class="commentcontent"><?php echo $comment['Content']; ?></p>
                        </div>
    
                        <?php $replies = getRepliesByCommentId($comment['CommentID']); ?>
                        <?php if ($replies): ?>
                            <button class="shw" data-comment-id="<?php echo $comment['CommentID']; ?>">
                                <p class="icon-label">
                                    <img class="reply-icon" src="chats.svg"> replies
                                </p>
                            </button>
                            <div class="replies" style="display: none;">
                                <?php foreach ($replies as $reply): ?>
                                    <div class="comment-replies">
                                        <img class="comment-reply-author-pfp" src="<?php echo $reply['ProfilePic']; ?>">
                                        <p class="comment-reply-content">
                                            <strong><?php echo $reply['Username']; ?>:</strong> <?php echo $reply['Content']; ?>
                                        </p>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
    
                        <button class="reply-btn" data-comment-id="<?php echo $comment['CommentID']; ?>">
                            <p class="icon-label">
                                <img class="reply-icon" src="reply.svg"> reply
                            </p>
                        </button>
                        
                        <?php if (isset($_SESSION['username'])): ?>
                            <?php if (checkUserBan()): ?>
                                <div class="ban-message" style="color: red; margin: 10px 0;">
                                    <?php echo checkUserBan(true); ?>
                                </div>
                            <?php else: ?>
                                <form class="reply-form" style="display: none;" action="reply_process.php" method="POST">
                                    <input type="hidden" name="comment_id" value="<?php echo $comment['CommentID']; ?>">
                                    <textarea name="content" class="reply-input" placeholder="reply to <?php echo $comment['Username']; ?>'s comment..." required></textarea>
                                    <button type="submit" class="reply-comment-btn">
                                        <img class="send-icon" src="send.svg">
                                    </button>
                                </form>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    
        <?php if (isset($_SESSION['username'])): ?>
            <?php if (checkUserBan()): ?>
                <div class="ban-message" style="color: red; margin: 10px 0;">
                    <?php echo checkUserBan(true); ?>
                </div>
            <?php else: ?>
                <div class="comment-form-container">
                    <?php if (!empty($user['ProfilePic'])): ?>
                        <img class="user_pic" src="<?php echo $user['ProfilePic']; ?>" alt="Profile Picture">
                    <?php else: ?>
                        <img class="user_pic" src="default_pic.svg" alt="Profile">
                    <?php endif; ?>
                    
                    <form class="comment-form" action="comment_for_view_post.php" method="POST">
                        <input type="hidden" name="post_id" value="<?php echo $postId; ?>">
                        <textarea name="content" class="comment-input" placeholder="Comment on <?php echo $post['Username']; ?>'s post..." required></textarea>
                        <button type="submit" class="comment-btn" id="Comment">
                            <img class="send-icon" src="send.svg">
                        </button>
                    </form>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <p>Please <a href="login.php">login</a> to comment.</p>
        <?php endif; }?>
    </div>
    

</div>

<!-- Add Image Modal -->
<div id="imageModal" class="image-modal">
    <span class="close-modal">&times;</span>
    <button class="nav-btn prev-btn" title="Previous">&lt;</button>
    <div class="modal-content">
        <img id="modalImage" src="" alt="Modal Image" draggable="false">
    </div>
    <button class="nav-btn next-btn" title="Next">&gt;</button>
    <div class="modal-counter"></div>
    <div class="zoom-controls">
        <button class="zoom-btn" onclick="zoom(1.2)">+</button>
        <button class="zoom-btn" onclick="zoom(0.8)">-</button>
        <button class="zoom-btn" onclick="resetZoom()">Reset</button>
    </div>
</div>

<!-- Remove all the duplicate JavaScript code and replace with this -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.querySelector('.post-images-container');
    if (container) {
        // Initialize the slideshow
        initializeImageSlideshow();
        
        // Add click handler for container
        container.addEventListener('click', function(e) {
            if (!e.target.classList.contains('slide-nav')) {
                const images = this.dataset.images.split(',');
                const activeSlide = this.querySelector('.post-image-slide.active');
                const currentIndex = Array.from(this.querySelectorAll('.post-image-slide')).indexOf(activeSlide);
                openModal(images, currentIndex);
            }
        });
    }
});
</script>

<!-- Scripts - Maintain this order -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="./javascripts/modal.js"></script>
<script src="./javascripts/index.js"></script>

<script>
    document.getElementById('comments-label').addEventListener('click', function() {
        var icon = document.getElementById('comments-label-icon');
        var element = document.getElementById('comments');
        if (element.style.display === 'none') {
            icon.style.transform = 'rotateZ(180deg)';
            element.style.display = 'block';
        } else {
            icon.style.transform = 'rotateZ(0deg)';
            element.style.display = 'none';
        }
    });
</script>

<script>
    // JavaScript to toggle reply form visibility
    document.querySelectorAll('.reply-btn').forEach(button => {
        button.addEventListener('click', function() {
            const replyForm = this.nextElementSibling;
            if (replyForm.style.display === 'none') {
                replyForm.style.display = 'block';
            } else {
                replyForm.style.display = 'none';
            }
        });
    });

    // JavaScript to toggle replies visibility
    document.querySelectorAll('.shw').forEach(button => {
        button.addEventListener('click', function() {
            const replies = this.parentNode.querySelector('.replies');
            if (replies.style.display === 'none' || replies.style.display === '') {
                replies.style.display = 'block';
            } else {
                replies.style.display = 'none';
            }
        });
    });
</script>

<script>
    // JavaScript to handle the back button functionality
    document.getElementById('backButton').addEventListener('click', function() {
        const previousPage = localStorage.getItem('previousPage');

        if (previousPage) {
            // Redirect to the manually stored previous page
            window.location.href = previousPage;

            // Optional: Clear the stored previous page after redirecting
            localStorage.removeItem('previousPage');
        } else {
            // Fallback if no previous page is found
            window.location.href = 'http://localhost/php-parctice/wshare%20admin%20latest/Wshare/wshare%20system%20(adjusted)/homepage.php'; // Replace with your fallback URL
        }
    });


    document.getElementById('logo-nav').addEventListener('click', function() {
            var element = document.getElementById('left-navbar');
            element.style.display = (element.style.display === 'none') ? 'block' : 'none';
        });

        document.getElementById('logo-left-nav').addEventListener('click', function() {
            var element = document.getElementById('left-navbar');
            element.style.display = (element.style.display === 'none') ? 'block' : 'none';
        });
</script>

<script>
    // Optional: Clear previousPage when navigating to a new page
    window.addEventListener('load', function() {
        localStorage.removeItem('previousPage');
    });
</script>

<style>
.post-images-container {
    position: relative;
    max-width: 100%;
    margin: 20px 0;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.post-image-slide {
    display: none;
    aspect-ratio: 16/9;
    background: #f5f5f5;
}

.post-image-slide.active {
    display: block;
}

.post-image-img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    background: #f5f5f5;
}

.slide-nav {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background: rgba(0, 0, 0, 0.5);
    color: white;
    padding: 15px;
    border: none;
    cursor: pointer;
    z-index: 2;
    border-radius: 50%;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    opacity: 0;
    transition: opacity 0.3s, background 0.3s;
}

.post-images-container:hover .slide-nav {
    opacity: 1;
}

.slide-nav:hover {
    background: rgba(0, 0, 0, 0.8);
}

.prev {
    left: 10px;
}

.next {
    right: 10px;
}

.image-counter {
    position: absolute;
    bottom: 15px;
    right: 15px;
    background: rgba(0, 0, 0, 0.6);
    color: white;
    padding: 5px 15px;
    border-radius: 15px;
    font-size: 14px;
    font-weight: 500;
}

/* Modal styles */
.image-modal {
    background: rgba(0, 0, 0, 0.9);
}

.modal-content {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100vh;
}

.modal-content img {
    max-width: 90vw;
    max-height: 85vh;
    object-fit: contain;
}

.nav-btn {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background: rgba(255, 255, 255, 0.1);
    color: white;
    padding: 20px;
    border: none;
    cursor: pointer;
    font-size: 24px;
    border-radius: 50%;
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s, background 0.3s;
}

.image-modal:hover .nav-btn {
    opacity: 1;
}

.nav-btn:hover {
    background: rgba(255, 255, 255, 0.2);
}

.prev-btn {
    left: 20px;
}

.next-btn {
    right: 20px;
}

.modal-counter {
    position: absolute;
    bottom: 20px;
    left: 50%;
    transform: translateX(-50%);
    background: rgba(255, 255, 255, 0.1);
    color: white;
    padding: 8px 20px;
    border-radius: 20px;
    font-size: 14px;
}

.zoom-controls {
    position: fixed;
    bottom: 20px;
    right: 20px;
    display: flex;
    gap: 10px;
}

.zoom-btn {
    background: rgba(255, 255, 255, 0.1);
    color: white;
    border: none;
    padding: 8px 15px;
    border-radius: 5px;
    cursor: pointer;
    transition: background 0.3s;
}

.zoom-btn:hover {
    background: rgba(255, 255, 255, 0.2);
}

.close-modal {
    position: fixed;
    top: 20px;
    right: 20px;
    color: white;
    font-size: 32px;
    cursor: pointer;
    z-index: 1001;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 50%;
    transition: background 0.3s;
}

.close-modal:hover {
    background: rgba(255, 255, 255, 0.2);
}
</style>

</body>
</html>
