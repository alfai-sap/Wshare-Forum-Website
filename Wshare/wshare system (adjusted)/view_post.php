<?php
require_once "functions.php";
require_once "changes.php";
require_once "bookmark_functions.php"; // Add this line to include bookmark functions
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
    <!--<link rel="stylesheet" href="./css/notifications.css?v=<?php echo time(); ?>">-->
    

    <script>
    // Save the previous page's URL when the page is loaded
    if (document.referrer && !localStorage.getItem('previousPage')) {
        localStorage.setItem('previousPage', document.referrer);
    }
    </script>
    <style>
        .ban-message {
            color: #721c24;
            background-color: #f8d7da;
            padding: 10px;
            border: 1px solid #f5c6cb;
            border-radius: 5px;
            margin: 10px 0;
        }
    </style>
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
        
        // Check if post exists
        if (!$post) {
            echo '<div class="error-message">Post not found or has been deleted.</div>';
            exit;
        }

        $comments = getCommentsByPostId($postId);
        $userProfile = getUserProfileById($post['UserID']);
        $profilePic = $userProfile['ProfilePic'] ?? 'default_pic.svg';
        $user = getUserByUsername($_SESSION['username']);
    ?>
        <div class="post-container">
        <div class="post">
        <?php if (hasUserLikedPost($post['PostID'], $_SESSION['user_id'])): ?>
                <div class="liked-message">
                    <p>You liked this post on <?php echo date('F j, Y', strtotime(getLikeDate($post['PostID'], $_SESSION['user_id']))); ?></p>
                </div>
            <?php endif; ?>
            <div class="author-info">
                <img class="author_pic" src="<?php echo htmlspecialchars($profilePic); ?>" alt="Profile Picture">
                
                <div class="unametime">
                    <div class="unam-time">
                        <?php if ($post['Username'] === '[deleted user]'): ?>
                            <p class="authorname deleted-user">[deleted user]</p>
                        <?php else: ?>
                            <p class="authorname">
                                <a href="view_user.php?username=<?php echo urlencode($post['Username']); ?>">
                                    <?php echo htmlspecialchars($post['Username']); ?>
                                </a>
                            </p>
                        <?php endif; ?>
                        <p class="timestamp"><?php echo timeAgo($post['CreatedAt']); ?></p>
                    </div>
                    
                    <?php if (isset($post['updatedAt']) && $post['updatedAt'] !== $post['CreatedAt']): ?>
                        <p class="timestamp-update">Edited <?php echo date('F j, Y, g:i a', strtotime($post['updatedAt'])); ?></p>
                    <?php endif; ?>
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
    
                  
            <?php
                // Create a combined media array with both images and videos
                $media = [];
                
                // Add videos if they exist
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

                // Replace the existing image gallery section with this
                if (!empty($media)): ?>
                    <div class="post-images-container" data-images="<?php echo htmlspecialchars(json_encode($media)); ?>">
                        <div class="images-wrapper">
                            <?php foreach($media as $index => $item): ?>
                                <div class="post-image-slide <?php echo $index === 0 ? 'active' : ''; ?>" data-index="<?php echo $index; ?>">
                                    <?php if ($item['type'] === 'image'): ?>
                                        <img class="post-image-img" src="<?php echo htmlspecialchars(trim($item['path'])); ?>" alt="Post Image">
                                    <?php elseif ($item['type'] === 'video'): ?>
                                        <video class="post-video" controls>
                                            <source src="<?php echo htmlspecialchars(trim($item['path'])); ?>" type="video/mp4">
                                            Your browser does not support the video tag.
                                        </video>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <?php if(count($media) > 1): ?>
                            <div class="image-nav-buttons">
                                <button class="nav-arrow prev-image">←</button>
                                <button class="nav-arrow next-image">→</button>
                            </div>
                            <div class="image-counter">1/<?php echo count($media); ?></div>
                        <?php endif; ?>
                        <button class="fullscreen-btn">⛶</button>
                    </div>

                    <!-- Update the fullscreen modal structure -->
                    <div class="fullscreen-modal">
                        <button class="fullscreen-close">×</button>
                        <img class="fullscreen-image" src="" alt="Fullscreen Image">
                        <video class="fullscreen-video" controls style="display: none;">
                            <source src="" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                        <div class="image-nav-buttons">
                            <button class="nav-arrow prev-image">←</button>
                            <button class="nav-arrow next-image">→</button>
                        </div>
                        <div class="image-counter"></div>
                        <div class="zoom-controls">
                            <button class="zoom-btn" onclick="zoom(1.2)">+</button>
                            <button class="zoom-btn" onclick="zoom(0.8)">-</button>
                            <button class="zoom-btn" onclick="resetZoom()">Reset</button>
                        </div>
                    </div>
            <?php endif; ?>

            <!-- Display documents -->
            <?php
            $documents = getDocumentsByPostId($postID);
            if (!empty($documents)): ?>
                <div class="post-documents">
                    <button class="dropdown-btn" onclick="toggleDocuments()">See documents attached</button>
                    <ul class="document-list" id="document-list" style="display: none;">
                        <?php foreach ($documents as $document): ?>
                            <li><a href="<?php echo htmlspecialchars($document, ENT_QUOTES, 'UTF-8'); ?>" target="_blank"><?php echo basename($document); ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
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

                <form action="bookmark_handler.php" method="POST">
                    <input type="hidden" name="post_id" value="<?php echo $post['PostID']; ?>">
                    <input type="hidden" name="action" value="<?php echo isBookmarked($_SESSION['user_id'], $post['PostID']) ? 'remove' : 'add'; ?>">
                    <button type="submit" class="bookmark-btn">
                        <?php if (isBookmarked($_SESSION['user_id'], $post['PostID'])): ?>
                            <img class="bookmark-icon" src="bookmark_filled.svg">
                            <span class="like-count">Bookmarked</span>
                        <?php else: ?>
                            <img class="bookmark-icon" src="bookmark.svg">
                            <span class="like-count">Add to bookmarks</span>
                        <?php endif; ?>
                    </button>
                </form>

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
                                <div class="ban-message">
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
            <?php if (checkUserBan()): // Check for general ban only ?>
                <div class="ban-message">
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

<!-- Scripts -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.querySelector('.post-images-container');
    const slides = document.querySelectorAll('.post-image-slide');
    const counter = document.querySelector('.image-counter');
    const modal = document.querySelector('.fullscreen-modal');
    const fullscreenImage = modal.querySelector('.fullscreen-image');
    const fullscreenVideo = modal.querySelector('.fullscreen-video');
    let currentIndex = 0;

    function updateSlide(index) {
        slides.forEach(slide => slide.classList.remove('active'));
        slides[index].classList.add('active');
        counter.textContent = `${index + 1}/${slides.length}`;
    }

    function navigateSlides(direction) {
        currentIndex = (currentIndex + direction + slides.length) % slides.length;
        updateSlide(currentIndex);
        if (modal.style.display === 'block') {
            const currentSlide = slides[currentIndex];
            const isVideo = currentSlide.querySelector('video') !== null;
            if (isVideo) {
                fullscreenImage.style.display = 'none';
                fullscreenVideo.style.display = 'block';
                fullscreenVideo.src = currentSlide.querySelector('video').src;
            } else {
                fullscreenVideo.style.display = 'none';
                fullscreenImage.style.display = 'block';
                fullscreenImage.src = currentSlide.querySelector('img').src;
            }
        }
    }

    // Navigation buttons
    container.querySelectorAll('.nav-arrow').forEach(button => {
        button.addEventListener('click', (e) => {
            e.stopPropagation();
            navigateSlides(button.classList.contains('next-image') ? 1 : -1);
        });
    });

    // Fullscreen functionality
    container.querySelector('.fullscreen-btn').addEventListener('click', () => {
        modal.style.display = 'block';
        const currentSlide = slides[currentIndex];
        const isVideo = currentSlide.querySelector('video') !== null;
        if (isVideo) {
            fullscreenImage.style.display = 'none';
            fullscreenVideo.style.display = 'block';
            fullscreenVideo.src = currentSlide.querySelector('video').src;
        } else {
            fullscreenVideo.style.display = 'none';
            fullscreenImage.style.display = 'block';
            fullscreenImage.src = currentSlide.querySelector('img').src;
        }
    });

    modal.querySelector('.fullscreen-close').addEventListener('click', () => {
        modal.style.display = 'none';
    });

    // Modal navigation
    modal.querySelectorAll('.nav-arrow').forEach(button => {
        button.addEventListener('click', () => {
            navigateSlides(button.classList.contains('next-image') ? 1 : -1);
        });
    });

    // Keyboard navigation
    document.addEventListener('keydown', (e) => {
        if (modal.style.display === 'block') {
            if (e.key === 'ArrowLeft') navigateSlides(-1);
            if (e.key === 'ArrowRight') navigateSlides(1);
            if (e.key === 'Escape') modal.style.display = 'none';
        }
    });
});

let currentZoom = 1;

function zoom(factor) {
    currentZoom *= factor;
    currentZoom = Math.min(Math.max(0.5, currentZoom), 3); // Limit zoom between 0.5x and 3x
    updateTransform();
}

function resetZoom() {
    currentZoom = 1;
    updateTransform();
}

function updateTransform() {
    document.querySelector('.fullscreen-image').style.transform = 
        `translate(-50%, -50%) scale(${currentZoom})`;
}

// Reset transform when opening modal
document.querySelector('.fullscreen-btn').addEventListener('click', resetZoom);
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentZoom = 1;
    let isDragging = false;
    let startX, startY;
    let translateX = 0;
    let translateY = 0;
    
    const modal = document.querySelector('.fullscreen-modal');
    const image = modal.querySelector('.fullscreen-image');
    
    // Add mouse wheel zoom handling
    function handleWheel(e) {
        if (modal.style.display === 'block') {
            e.preventDefault();
            const delta = e.deltaY || e.detail || e.wheelDelta;
            
            // Calculate zoom factor based on scroll direction
            const factor = delta > 0 ? 0.9 : 1.1;
            
            // Get mouse position relative to image
            const rect = image.getBoundingClientRect();
            const mouseX = e.clientX - rect.left;
            const mouseY = e.clientY - rect.top;
            
            // Apply zoom
            currentZoom *= factor;
            currentZoom = Math.min(Math.max(0.5, currentZoom), 3); // Limit zoom between 0.5x and 3x
            
            // Update transform with mouse position as zoom origin
            image.style.transform = `translate(calc(-50% + ${translateX}px), calc(-50% + ${translateY}px)) scale(${currentZoom})`;
        }
    }
    
    // Add wheel event listener
    modal.addEventListener('wheel', handleWheel, { passive: false });
    
    function startDrag(e) {
        if (e.target.closest('.nav-arrow') || 
            e.target.closest('.zoom-btn') || 
            e.target.closest('.fullscreen-close')) {
            return;
        }
        
        isDragging = true;
        modal.classList.add('dragging');
        
        if (e.type === 'mousedown') {
            startX = e.clientX - translateX;
            startY = e.clientY - translateY;
        } else if (e.type === 'touchstart') {
            startX = e.touches[0].clientX - translateX;
            startY = e.touches[0].clientY - translateY;
        }
    }
    
    function doDrag(e) {
        if (!isDragging) return;
        e.preventDefault();
        
        let clientX, clientY;
        if (e.type === 'mousemove') {
            clientX = e.clientX;
            clientY = e.clientY;
        } else if (e.type === 'touchmove') {
            clientX = e.touches[0].clientX;
            clientY = e.touches[0].clientY;
        }
        
        translateX = clientX - startX;
        translateY = clientY - startY;
        
        image.style.transform = `translate(calc(-50% + ${translateX}px), calc(-50% + ${translateY}px)) scale(${currentZoom})`;
    }
    
    function stopDrag() {
        isDragging = false;
        modal.classList.remove('dragging');
    }
    
    function resetPosition() {
        translateX = 0;
        translateY = 0;
        currentZoom = 1;
        image.style.transform = 'translate(-50%, -50%) scale(1)';
    }
    
    // Mouse events
    image.addEventListener('mousedown', startDrag);
    document.addEventListener('mousemove', doDrag);
    document.addEventListener('mouseup', stopDrag);
    
    // Touch events
    image.addEventListener('touchstart', startDrag);
    document.addEventListener('touchmove', doDrag, { passive: false });
    document.addEventListener('touchend', stopDrag);
    
    // Reset position when closing modal
    modal.querySelector('.fullscreen-close').addEventListener('click', () => {
        resetPosition();
        modal.style.display = 'none';
    });
    
    // Reset position when opening modal
    document.querySelector('.fullscreen-btn').addEventListener('click', resetPosition);
    
    // Zoom functions
    window.zoom = function(factor) {
        currentZoom *= factor;
        currentZoom = Math.min(Math.max(0.5, currentZoom), 3);
        image.style.transform = `translate(calc(-50% + ${translateX}px), calc(-50% + ${translateY}px)) scale(${currentZoom})`;
    };
    
    window.resetZoom = resetPosition;
});
</script>

<script>
function toggleDocuments() {
    var docList = document.getElementById('document-list');
    if (docList.style.display === 'none') {
        docList.style.display = 'block';
    } else {
        docList.style.display = 'none';
    }
}
</script>

</body>
</html>
