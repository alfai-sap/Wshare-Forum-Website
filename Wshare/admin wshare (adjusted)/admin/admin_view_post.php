<?php
include 'dashFunctions.php';
require_once '../../wshare system (adjusted)/functions.php'; // Fix the path to functions.php

if (isset($_POST['delete_comment'])) {
    deleteComment($_POST['comment_id']);
    // Redirect to the same page with the post ID
    header("Location: admin_view_post.php?id=" . $_GET['id']);
    exit;
}

// Add this at the top with other POST handlers
if (isset($_POST['delete_reply'])) {
    deleteReply($_POST['reply_id']);
    // Redirect to the same page with the post ID
    header("Location: admin_view_post.php?id=" . $_GET['id']);
    exit;
}

// Check if post ID is provided
if (!isset($_GET['id'])) {
    header('Location: manage_posts.php');
    exit;
}

$postId = $_GET['id'];
$post = getPostById($postId);

// If post doesn't exist, redirect back
if (!$post) {
    header('Location: manage_posts.php');
    exit;
}

$comments = getCommentsByPostId($postId);
$userProfile = getUserProfileById($post['UserID']);
$profilePic = $userProfile['ProfilePic'] ?? 'default_pic.svg';

// Get media files for the post
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

// Get documents
$documents = getDocumentsByPostId($postId);

// Get tags
$tags = getTagsByPostId($postId);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Post - Admin Panel</title>
    <!-- Fix CSS file paths to point to the correct location -->
    <link rel="stylesheet" href="../../wshare system (adjusted)/css/view_post.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="dashboard.css?v=<?php echo time(); ?>">
    <style>
        .comment.highlighted {
            background-color: #fff3cd;
            transition: background-color 0.5s ease;
            animation: highlight-fade 2s ease-in-out;
        }

        @keyframes highlight-fade {
            0%, 100% { background-color: #fff3cd; }
            50% { background-color: #fff8e1; }
        }
    </style>
</head>
<body>
    <?php include 'sidebar.php'; ?>
    
    <div class="container">
        <a href="manage_posts.php" class="backButton">
            <div class="back"><p class="back-label">Back</p></div>
        </a>
        
        <div class="post-container">
            <div class="post">
                <?php if (hasUserLikedPost($post['PostID'], $_SESSION['user_id'] ?? 0)): ?>
                    <div class="liked-message">
                        <p>You liked this post on <?php echo date('F j, Y', strtotime(getLikeDate($post['PostID'], $_SESSION['user_id']))); ?></p>
                    </div>
                <?php endif; ?>

                <div class="author-info">
                    <img class="author_pic" src="../../wshare system (adjusted)/<?php echo htmlspecialchars($profilePic); ?>" alt="Profile Picture">
                    
                    <div class="unametime">
                        <div class="unam-time">
                            <p class="authorname"><?php echo htmlspecialchars($post['Username']); ?></p>
                            <p class="timestamp"><?php echo timeAgo($post['CreatedAt']); ?></p>
                        </div>
                        
                        <?php if (isset($post['updatedAt']) && $post['updatedAt'] !== $post['CreatedAt']): ?>
                            <p class="timestamp-update">Edited <?php echo date('F j, Y, g:i a', strtotime($post['updatedAt'])); ?></p>
                        <?php endif; ?>
                    </div>
                </div>

                <?php if (!empty($tags)): ?>
                    <div class="post-tags">
                        <?php foreach ($tags as $tag): ?>
                            <span class="tag-label"><?php echo htmlspecialchars($tag); ?></span>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <h3><?php echo htmlspecialchars($post['Title']); ?></h3>
                <p class="post-content"><?php echo nl2br(htmlspecialchars($post['Content'])); ?></p>

                <?php if (!empty($media)): ?>
                    <div class="post-images-container" data-images="<?php echo htmlspecialchars(json_encode($media)); ?>">
                        <div class="images-wrapper">
                            <?php foreach($media as $index => $item): ?>
                                <div class="post-image-slide <?php echo $index === 0 ? 'active' : ''; ?>" data-index="<?php echo $index; ?>">
                                    <?php if ($item['type'] === 'image'): ?>
                                        <img class="post-image-img" src="../../wshare system (adjusted)/<?php echo htmlspecialchars(trim($item['path'])); ?>" alt="Post Image">
                                    <?php elseif ($item['type'] === 'video'): ?>
                                        <video class="post-video" controls>
                                            <source src="../../wshare system (adjusted)/<?php echo htmlspecialchars(trim($item['path'])); ?>" type="video/mp4">
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

                    <!-- Fullscreen modal structure -->
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

                <?php if (!empty($documents)): ?>
                    <div class="post-documents">
                        <button class="dropdown-btn" onclick="toggleDocuments()">See documents attached</button>
                        <ul class="document-list" id="document-list" style="display: none;">
                            <?php foreach ($documents as $document): ?>
                                <li><a href="../../wshare system (adjusted)/<?php echo htmlspecialchars($document); ?>" target="_blank"><?php echo basename($document); ?></a></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <div class="lik">
                    <!-- Display statistics -->
                    <button class="like-btn">
                        <img class="bulb" src="../../wshare system (adjusted)/bulb_active.svg">
                    </button>
                    <span class="like-count"><?php echo getLikeCount($post['PostID']); ?> Brilliant Points</span>
                    
                    <button class="like-btn">
                        <img class="bulb" src="../../wshare system (adjusted)/comment.svg">
                    </button>
                    <span class="like-count"><?php echo countComments($post['PostID']); ?> Comments</span>
                </div>
            </div>

            <?php if ($comments): ?>
                <div class="comments-section">
                    <h3>Comments</h3>
                    <?php foreach ($comments as $comment): ?>
                        <?php 
                        // Get replies at the start of each comment loop
                        $replies = getRepliesByCommentId($comment['CommentID']); 
                        ?>
                        <div class="comment <?php echo (isset($_GET['highlight']) && $_GET['highlight'] == $comment['CommentID']) ? 'highlighted' : ''; ?>">
                            <div class="comments_author">
                                <div class="comments_author_uname_content">
                                    <?php if (!empty($comment['ProfilePic'])): ?>
                                        <img class="comments_author_pfp" src="../../wshare system (adjusted)/<?php echo $comment['ProfilePic']; ?>">
                                    <?php else: ?>
                                        <img class="comments_author_pfp" src="../../wshare system (adjusted)/default_pic.svg">
                                    <?php endif; ?>
        
                                    <div class="comments_author_uname_time">
                                        <p class="comments_author_uname"><strong><?php echo $comment['Username']; ?></strong></p>
                                        <p class="comment_timestamp"><?php echo timeAgo($comment['CreatedAt']); ?></p>
                                    </div>
                                </div>
                                <p class="commentcontent"><?php echo $comment['Content']; ?></p>
                            </div>
        
                            <div class="comment-actions">
                                <?php if ($replies): ?>
                                    <button class="shw" data-comment-id="<?php echo $comment['CommentID']; ?>">
                                        <p class="icon-label">
                                            <img class="reply-icon" src="../../wshare system (adjusted)/chats.svg"> 
                                            <?php echo count($replies); ?> replies
                                        </p>
                                    </button>
                                <?php endif; ?>
                                
                                <form method="POST" class="delete-comment-form" onsubmit="return confirm('Are you sure you want to delete this comment? This will also delete all replies to this comment.');">
                                    <input type="hidden" name="comment_id" value="<?php echo $comment['CommentID']; ?>">
                                    <button type="submit" name="delete_comment" class="delete-comment-btn">
                                        <img class="delete-icon" src="../../wshare system (adjusted)/delete.svg"> Delete
                                    </button>
                                </form>
                            </div>
        
                            <?php if ($replies): ?>
                                <div class="replies" id="replies-<?php echo $comment['CommentID']; ?>" style="display: none;">
                                    <?php foreach ($replies as $reply): ?>
                                        <div class="comment-replies">
                                            <div class="reply-wrapper">
                                                <img class="comment-reply-author-pfp" src="../../wshare system (adjusted)/<?php echo $reply['ProfilePic']; ?>">
                                                <div class="reply-content-wrapper">
                                                    <div class="reply-header">
                                                        <p class="comments_author_uname"><strong><?php echo $reply['Username']; ?></strong></p>
                                                        <p class="comment_timestamp"><?php echo timeAgo($reply['CreatedAt']); ?></p>
                                                        <form method="POST" class="delete-reply-form" onsubmit="return confirm('Are you sure you want to delete this reply?');">
                                                            <input type="hidden" name="reply_id" value="<?php echo $reply['ReplyID']; ?>">
                                                            <button type="submit" name="delete_reply" class="delete-comment-btn">
                                                                <img class="delete-icon" src="../../wshare system (adjusted)/delete.svg"> Delete
                                                            </button>
                                                        </form>
                                                    </div>
                                                    <p class="commentcontent"><?php echo $reply['Content']; ?></p>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Include necessary JavaScript -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.querySelector('.post-images-container');
            if (!container) return;

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
            container.querySelector('.fullscreen-btn')?.addEventListener('click', () => {
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

            modal.querySelector('.fullscreen-close')?.addEventListener('click', () => {
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

        // Zoom functionality
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

        // Document toggle functionality
        function toggleDocuments() {
            var docList = document.getElementById('document-list');
            docList.style.display = docList.style.display === 'none' ? 'block' : 'none';
        }

        // Add this JavaScript for handling reply visibility
        document.querySelectorAll('.shw').forEach(button => {
            button.addEventListener('click', function() {
                const commentId = this.getAttribute('data-comment-id');
                const repliesDiv = document.getElementById('replies-' + commentId);
                
                if (repliesDiv.style.display === 'none' || repliesDiv.style.display === '') {
                    // Hide all other replies first
                    document.querySelectorAll('.replies').forEach(div => {
                        if (div.id !== 'replies-' + commentId) {
                            div.style.display = 'none';
                        }
                    });
                    // Show this reply section
                    repliesDiv.style.display = 'block';
                } else {
                    repliesDiv.style.display = 'none';
                }
            });
        });

        // Add this section for handling highlighted comments
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const highlightedCommentId = urlParams.get('highlight');
            
            if (highlightedCommentId) {
                const highlightedComment = document.querySelector(`.comment.highlighted`);
                if (highlightedComment) {
                    highlightedComment.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }
        });
    </script>
</body>
</html>
