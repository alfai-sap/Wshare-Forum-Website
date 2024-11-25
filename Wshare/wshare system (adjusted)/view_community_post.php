<?php
require_once "functions.php";
session_start();

// Check if post ID is provided in the URL
if (isset($_GET['id'])) {
    $postId = $_GET['id'];
    $post = getCommunityPostById($postId);
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
            <div class="back" style="display:flex; margin-left:10px;">
                <img class="icons" src="signoff.svg">
                <p class="back-label" style="padding-top:10px;color:#007bff">Back</p>
            </div>
        </a>

        <?php if (isset($post)): ?>
            <div class="post">
                <div class="author-info">
                    <img class="author_pic" src="<?= $profilePic ?>" alt="Profile Picture">
                    <div class="unametime" style="display:flex; flex-direction: column;">
                    <div class="unam-time" style="display: flex;">
                        <p class="authorname"><?php echo $post['Username']; ?></p>
                        <p class="timestamp"><?php echo timeAgo($post['CreatedAt']); ?></p>
                    </div>
                    
                    <p class="timestamp-update">edited <?php echo timeAgo($post['UpdatedAt']); ?></p>
                </div>
                </div>
                <h3><?= $post['Title'] ?></h3>
                <p class="post-content"><?= $post['Content'] ?></p>

                <!-- Like and comment buttons -->
                <div class="lik">
                    <form class="like" action="like_post.php" method="POST">
                        <input type="hidden" name="postID" value="<?= $post['PostID'] ?>">
                        <button type="submit" class="like-btn" name="like">
                            <img class="bulb" src="bulb.svg">
                        </button>
                    </form>
                    <span class="like-count"><?= getLikeCount($post['PostID']) ?></span>
                    <button class="like-btn">
                        <img class="bulb" src="comment.svg">
                    </button>
                    <span class="like-count"><?= countComments($post['PostID']) ?></span>
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
                                        <p class="comments_author_uname"><strong><?= $comment['Username'] ?></strong></p>
                                        <p class="comment_timestamp"><?= $comment['CreatedAt'] ?></p>
                                    </div>
                                </div>
                                <p class="commentcontent"><?= $comment['Content'] ?></p>
                            </div>

                            <!-- Replies Section -->
                            <?php $replies = getCommunityRepliesByCommentId($comment['CommentID']); ?>
                            <?php if ($replies): ?>
                                <button class="shw" data-comment-id="<?= $comment['CommentID'] ?>">
                                    <p class="icon-label"><img class="reply-icon" src="chats.svg"> replies</p>
                                </button>
                                <div class="replies" style="display: none;">
                                    <?php foreach ($replies as $reply): ?>
                                        <div class="comment-replies">
                                            <img class="comment-reply-author-pfp" src="<?= $reply['ProfilePic'] ?>">
                                            <p class="comment-reply-content"><strong><?= $reply['Username'] ?>:</strong> <?= $reply['Content'] ?></p>
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

    <script>
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

        // JavaScript to handle back button functionality
        document.getElementById('backButton').addEventListener('click', function() {
            window.history.back();
        });
    </script>
</body>
</html>
