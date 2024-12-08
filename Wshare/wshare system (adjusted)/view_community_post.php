<?php
require_once "functions.php";
session_start();

// Check if post ID is provided in the URL
if (isset($_GET['id'])) {
    $postId = $_GET['id'];
    $post = getCommunityPostById($postId);
    $communityId = $post['CommunityID'];
    $comments = getCommunityCommentsByPostId($postId);
    $userProfile = getUserProfileById($post['UserID']);
    $profilePic = $userProfile['ProfilePic'] ?? 'default_pic.svg'; // Default picture if none exists
    $user = isset($_SESSION['username']) ? getUserByUsername($_SESSION['username']) : null;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Post</title>
    <link rel="stylesheet" href="./css/navbar.css?v=<?= time(); ?>">
    <link rel="stylesheet" href="./css/left-navbar.css?v=<?= time(); ?>">
    <link rel="stylesheet" href="./css/view_post.css?v=<?= time(); ?>">
</head>

<body>
    <?php include 'navbar.php'; ?>

    <div class="container">
        <a class="backButton" id="backButton">
            <div class="back"><p class="back-label">Back</p></div>
        </a>

        <?php if (isset($post)): ?>
            <div class="post">
            <?php if (hasUserLikedPost($post['PostID'], $_SESSION['user_id'])): ?>
                    <div class="liked-message" style="background-color: #e0f7fa; padding: 10px; border-radius: 5px; margin-bottom: 10px;">
                        <p style="margin: 0; color: #00796b;">You liked this post on <?php echo date('F j, Y', strtotime(getLikeDate($post['PostID'], $_SESSION['user_id']))); ?></p>
                    </div><br>
                <?php endif; ?>
                <div class="author-info">
                    <img class="author_pic" src="<?= $profilePic ?>" alt="Profile Picture">
                    <div class="unametime" style="display:flex; flex-direction: column;">
                    <div class="unam-time" style="display: flex; align-items: center;">
                        <p class="authorname"><?php echo $post['Username']; ?></p>
                        <?php
                            // Check if post author is an admin of this community
                            $adminCheck = "SELECT Role FROM community_members WHERE CommunityID = ? AND UserID = ? AND Role = 'admin'";
                            $stmt = $conn->prepare($adminCheck);
                            $stmt->bind_param('ii', $communityId, $post['UserID']);
                            $stmt->execute();
                            $adminResult = $stmt->get_result();
                            if ($adminResult->num_rows > 0): ?>
                                <span style="color: #ff4b4b; margin-left: 5px; font-size: 0.8em; padding: 2px 6px; border: 1px solid #ff4b4b; border-radius: 4px;">Admin</span>
                            <?php endif; ?>
                        <p class="timestamp"><?php echo timeAgo($post['CreatedAt']); ?></p>
                    </div>
                    
                    <p class="timestamp-update">edited <?php echo timeAgo($post['UpdatedAt']); ?></p>
                </div>
                </div>
                <h3><?= $post['Title'] ?></h3>
                <p class="post-content"><?= $post['Content'] ?></p>

                <!-- Add this photo section -->
                <?php if (!empty($post['PhotoPath'])): ?>
                    <div class="post-image">
                        <img class="post-image-img" src="<?php echo $post['PhotoPath']; ?>" alt="Post Image">
                    </div>
                <?php endif; ?>

                

                <!-- Like and comment buttons -->
                <div class="lik">
                    <form class="like" action="like_community_post.php" method="POST">
                        <input type="hidden" name="postID" value="<?= $post['PostID'] ?>">
                        <button type="submit" class="like-btn" name="like">
                            <?php if (hasUserLikedPost($post['PostID'], $_SESSION['user_id'])): ?>
                                <img class="bulb" src="bulb_active.svg">
                            <?php else: ?>
                                <img class="bulb" src="bulb.svg">
                            <?php endif; ?>
                        </button>
                    </form>
                    <span class="like-count"><?= getCommunityLikeCount($post['PostID']) ?></span>
                    <button class="like-btn">
                        <img class="bulb" src="comment.svg">
                    </button>
                    <span class="like-count"><?= getCommunityCountComment($post['PostID']) ?></span>
                </div>
            </div>

            <!-- Comments Section -->
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
                                    <img class="comments_author_pfp" src="<?= $comment['ProfilePic'] ?? 'default_pic.svg' ?>">
                                    <div class="comments_author_uname_time">
                                        <div class="author-header" style="display: flex; align-items: center; gap: 8px; margin-bottom: 4px;">
                                            <p class="comments_author_uname">
                                                <strong><?= $comment['Username'] ?></strong>
                                            </p>
                                            <?php
                                            // Check if commenter is an admin of this community
                                            $adminCheck = "SELECT Role FROM community_members 
                                                          WHERE CommunityID = ? AND UserID = ? AND Role = 'admin'";
                                            $stmt = $conn->prepare($adminCheck);
                                            $stmt->bind_param('ii', $communityId, $comment['UserID']);
                                            $stmt->execute();
                                            $adminResult = $stmt->get_result();
                                            if ($adminResult->num_rows > 0): ?>
                                                <span class="admin-badge" style="color: #ff4b4b; font-size: 0.75em; padding: 2px 6px; border: 1px solid #ff4b4b; border-radius: 4px;">Admin</span>
                                            <?php endif; ?>
                                        </div>
                                        <p class="comment_timestamp" style="margin: 0; color: #666;"><?= timeAgo($comment['CreatedAt']) ?></p>
                                    </div>
                                </div>
                                <p class="commentcontent"><?= $comment['Content'] ?></p>
                            </div>

                            <!-- Replies Section -->
                            <?php 
                            $replies = getCommunityRepliesByCommentId($comment['CommentID']); 
                            if ($replies): ?>
                                <button class="shw" data-comment-id="<?= $comment['CommentID'] ?>">
                                    <p class="icon-label"><img class="reply-icon" src="chats.svg"> replies</p>
                                </button>
                                <div class="replies" style="display: none;">
                                    <?php foreach ($replies as $reply): ?>
                                        <div class="comment-replies">
                                            <img class="comment-reply-author-pfp" src="<?= $reply['ProfilePic'] ?>">
                                            <div style="display: flex; align-items: center; gap: 8px;">
                                                <strong><?= $reply['Username'] ?></strong>
                                                <?php
                                                // Check if replier is an admin
                                                $stmt = $conn->prepare($adminCheck);
                                                $stmt->bind_param('ii', $communityId, $reply['UserID']);
                                                $stmt->execute();
                                                $adminResult = $stmt->get_result();
                                                if ($adminResult->num_rows > 0): ?>
                                                    <span class="admin-badge" style="color: #ff4b4b; font-size: 0.75em; padding: 2px 6px; border: 1px solid #ff4b4b; border-radius: 4px;">(Admin)</span>
                                                <?php endif; ?>
                                            </div>
                                            <p class="comment-reply-content">
                                                : <?= $reply['Content'] ?>
                                            </p>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>

                            <!-- Reply Form -->
                            <?php if (isset($_SESSION['username'])): ?>
                                <button class="reply-btn" data-comment-id="<?= $comment['CommentID'] ?>">
                                    <p class="icon-label"><img class="reply-icon" src="reply.svg"> reply</p>
                                </button>
                                <form class="reply-form" style="display: none;" action="community_reply_process.php" method="POST">
                                    <input type="hidden" name="comment_id" value="<?= $comment['CommentID'] ?>">
                                    <textarea name="content" class="reply-input" placeholder="reply to <?= $comment['Username'] ?>'s comment..." required></textarea>
                                    <button type="submit" class="reply-comment-btn">
                                        <img class="send-icon" src="send.svg">
                                    </button>
                                </form>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <!-- Comment Form -->
            <?php if (isset($_SESSION['username'])): ?>
                <div class="comment-form-container">
                    <img class="user_pic" src="<?= $user['ProfilePic'] ?? 'default_pic.svg' ?>" alt="Profile Picture">
                    <form class="comment-form" action="comment_for_view_post.php" method="POST">
                        <input type="hidden" name="post_id" value="<?= $postId ?>">
                        <textarea name="content" class="comment-input" placeholder="Comment on <?= $post['Username'] ?>'s post..." required></textarea>
                        <button type="submit" class="comment-btn" id="Comment">
                            <img class="send-icon" src="send.svg">
                        </button>
                    </form>
                </div>
            <?php else: ?>
                <p>Please <a href="login.php">login</a> to comment.</p>
            <?php endif; ?>
        <?php else: ?>
            <p>Post not found or comment uploaded.</p>
        <?php endif; ?>

    </div>

    <!-- Add Image Modal -->
    <div id="imageModal" class="image-modal">
        <span class="close-modal" onclick="closeModal()">&times;</span>
        <img id="modalImage" class="modal-content">
        <div class="zoom-controls">
            <button class="zoom-btn" onclick="zoom(1.2)">+</button>
            <button class="zoom-btn" onclick="zoom(0.8)">-</button>
            <button class="zoom-btn" onclick="resetZoom()">Reset</button>
        </div>
    </div>

    <script>
    let currentZoom = 1;
    let isDragging = false;
    let startX, startY, translateX = 0, translateY = 0;

    // Make post images clickable
    document.querySelectorAll('.post-image-img').forEach(img => {
        img.style.cursor = 'pointer';
        img.onclick = function() {
            openModal(this.src);
        }
    });

    function openModal(imgSrc) {
        const modal = document.getElementById('imageModal');
        const modalImg = document.getElementById('modalImage');
        modal.style.display = "block";
        modalImg.src = imgSrc;
        resetPosition();
    }

    // Same functions as above...
    function closeModal() {
        document.getElementById('imageModal').style.display = "none";
        resetPosition();
    }

    function zoom(factor) {
        currentZoom *= factor;
        currentZoom = Math.min(Math.max(0.5, currentZoom), 3);
        updateTransform();
    }

    function startDrag(e) {
        isDragging = true;
        if (e.type === "mousedown") {
            startX = e.clientX - translateX;
            startY = e.clientY - translateY;
        } else if (e.type === "touchstart") {
            startX = e.touches[0].clientX - translateX;
            startY = e.touches[0].clientY - translateY;
        }
        document.getElementById('modalImage').style.cursor = 'grabbing';
    }

    function drag(e) {
        if (!isDragging) return;
        e.preventDefault();
        
        const clientX = e.type === "mousemove" ? e.clientX : e.touches[0].clientX;
        const clientY = e.type === "mousemove" ? e.clientY : e.touches[0].clientY;
        
        translateX = clientX - startX;
        translateY = clientY - startY;
        
        updateTransform();
    }

    function stopDrag() {
        isDragging = false;
        document.getElementById('modalImage').style.cursor = 'grab';
    }

    function resetPosition() {
        translateX = 0;
        translateY = 0;
        currentZoom = 1;
        updateTransform();
    }

    function updateTransform() {
        document.getElementById('modalImage').style.transform = 
            `scale(${currentZoom}) translate(${translateX}px, ${translateY}px)`;
    }

    // Event listeners
    const modalImage = document.getElementById('modalImage');
    modalImage.addEventListener('mousedown', startDrag);
    modalImage.addEventListener('touchstart', startDrag);
    document.addEventListener('mousemove', drag);
    document.addEventListener('touchmove', drag, { passive: false });
    document.addEventListener('mouseup', stopDrag);
    document.addEventListener('touchend', stopDrag);

    // Keyboard controls
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeModal();
        if (e.key === '+' || e.key === '=') zoom(1.2);
        if (e.key === '-') zoom(0.8);
        if (e.key === '0') resetPosition();
    });
    </script>

    <script>
        // JavaScript to handle the back button functionality
        document.getElementById('backButton').addEventListener('click', function() {
            // Redirect to the community page with the correct community ID
            window.location.href = 'community_page.php?community_id=<?php echo $communityId; ?>';
        });

        // JavaScript to toggle visibility of elements
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

        document.querySelectorAll('.reply-btn').forEach(button => {
            button.addEventListener('click', function() {
                const replyForm = this.nextElementSibling;
                replyForm.style.display = (replyForm.style.display === 'none') ? 'block' : 'none';
            });
        });

        document.querySelectorAll('.shw').forEach(button => {
            button.addEventListener('click', function() {
                const replies = this.parentNode.querySelector('.replies');
                replies.style.display = (replies.style.display === 'none' || replies.style.display === '') ? 'block' : 'none';
            });
        });
    </script>
</body>
</html>
