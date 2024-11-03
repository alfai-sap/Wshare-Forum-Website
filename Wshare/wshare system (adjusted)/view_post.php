<?php
require_once "functions.php";
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

    <?php
    // Check if post ID is provided in the URL
    if (isset($_GET['id'])) {
        $postId = $_GET['id'];
        $post = getPostById($postId);
        $comments = getCommentsByPostId($postId);
        $userProfile = getUserProfileById($post['UserID']);
        $profilePic = $userProfile['ProfilePic'];
        $user = getUserByUsername($_SESSION['username']);
        
        // Display post details
        echo '<div class="post">';
            echo '<div class="author-info">';
                if (!empty($profilePic)) {
                    echo '<img class="author_pic" src="' . $profilePic . '" alt="Profile Picture">';
                } else {
                    echo '<img class="author_pic" src="default_pic.svg" alt="Profile Picture">';
                }
                echo '<div class="unametime" style="display:flex; flex-direction: column;">';
                    echo '<p class="authorname">' . $post['Username'] . '</p>';
                    echo '<p class="timestamp" style="margin-top:5px;">Posted at: ' . $post['CreatedAt'] . '</p>';
                    echo '<p class="timestamp" style="margin-top:5px;">Edited on: ' . $post['updatedAt'] . '</p>';
                echo '</div>';
            echo '</div>';
            echo '<h3>' . $post['Title'] . '</h3>';
            echo '<p class="post-content">' . $post['Content'] . '</p>';

            // Like and comment buttons
            echo '<div class="lik">';
                echo '<form class="like" action="like_post.php" method="POST">';
                    echo '<input type="hidden" name="postID" value="' . $post['PostID'] . '">';
                    echo '<button type="submit" class="like-btn" name="like"><img class="bulb" src="bulb.svg"></button>';
                echo '</form>';
                echo '<span class="like-count">' . getLikeCount($post['PostID']) . '</span>';
                $num_comm = countComments($post['PostID']);
                echo '<button class="like-btn"><img class="bulb" src="comment.svg"></button>';
                echo '<span class="like-count"> ' . $num_comm . '</span>';
            echo '</div>';
        echo '</div>';

        // Display comments
        if ($comments) {
            echo '<div id="comments-label" class="comments-label">';
                echo '<h4>Comments</h4>';
                echo '<p><img id="comments-label-icon" class="comments-label-icon" src="show.svg"></p>';
            echo '</div>';
            echo '<div class="comments" id="comments">';
                foreach ($comments as $comment) {
                    echo '<div class="comment">';
                        echo '<div class="comments_author">';
                            echo '<div class="comments_author_uname_content">';
                                if (!empty($profilePic)) {
                                    echo '<img class="comments_author_pfp" src="' . $comment['ProfilePic'] . '">';
                                } else {
                                    echo '<img class="comments_author_pfp" src="default_pic.svg">';
                                }
                                echo '<div class="comments_author_uname_time">';
                                    echo '<p class="comments_author_uname"><strong>' . $comment['Username'] . '</strong></p>';
                                    echo '<p class="comment_timestamp">' . $comment['CreatedAt'] . '</p>';
                                echo '</div>';
                            echo '</div>';
                            echo '<p class="commentcontent">' . $comment['Content'] . '</p>';
                        echo '</div>';

                        // Display replies
                        $replies = getRepliesByCommentId($comment['CommentID']);
                        if ($replies) {
                            echo '<button class="shw" data-comment-id="' . $comment['CommentID'] . '">';
                                echo '<p class="icon-label"><img class="reply-icon" src="chats.svg"> replies</p>';
                            echo '</button>';
                            echo '<div class="replies" style="display: none;">';
                                foreach ($replies as $reply) {
                                    echo '<div class="comment-replies">';
                                        echo '<img class="comment-reply-author-pfp" src="' . $reply['ProfilePic'] . '">';
                                        echo '<p class="comment-reply-content"><strong>' . $reply['Username'] . ':</strong> ' . $reply['Content'] . '</p>';
                                    echo '</div>';
                                }
                            echo '</div>';
                        }
                        echo '<button class="reply-btn" data-comment-id="' . $comment['CommentID'] . '">';
                            echo '<p class="icon-label"><img class="reply-icon" src="reply.svg"> reply</p>';
                        echo '</button>';

                        // Reply form
                        if (isset($_SESSION['username'])) {
                            echo '<form class="reply-form" style="display: none;" action="reply_process.php" method="POST">';
                                echo '<input type="hidden" name="comment_id" value="' . $comment['CommentID'] . '">';
                                echo '<textarea name="content" class="reply-input" placeholder="reply to ' . $comment['Username'] . '`s comment..." required></textarea>';
                                echo '<button type="submit" class="reply-comment-btn"><img class="send-icon" src="send.svg"></button>';
                            echo '</form>';
                        }
                    echo '</div>';
                }
            echo '</div>';
        }

        // Add comment form if user is logged in
        if (isset($_SESSION['username'])) {
            echo '<div class="comment-form-container">';
                if (!empty($user['ProfilePic'])) {
                    echo '<img class="user_pic" src="' . $user['ProfilePic'] . '" alt="Profile Picture">';
                } else {
                    echo '<img class="user_pic" src="default_pic.svg" alt="Profile">';
                }
                echo '<form class="comment-form" action="comment_for_view_post.php" method="POST">';
                    echo '<input type="hidden" name="post_id" value="' . $postId . '">';
                    echo '<textarea name="content" class="comment-input" placeholder="Comment on ' . $post['Username'] . '`s post..." required></textarea>';
                    echo '<button type="submit" class="comment-btn" id="Comment"><img class="send-icon" src="send.svg"></button>';
                echo '</form>';
            echo '</div>';
        } else {
            echo '<p>Please <a href="login.php">login</a> to comment.</p>';
        }
    } else {
        // Handle case where post ID is not provided
        echo '<p>Comment Uploaded.</p>';
    }
    ?>

</div>

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
    // JavaScript to handle back button functionality
    document.getElementById('backButton').addEventListener('click', function() {
        window.history.back();
    });
</script>

</body>
</html>
