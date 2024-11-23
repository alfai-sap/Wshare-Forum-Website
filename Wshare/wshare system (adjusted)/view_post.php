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
    
            <h3><?php echo $post['Title']; ?></h3>
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
            <p class="post-content"><?php echo $post['Content']; ?></p>
    
                  
            <?php if (!empty($post['PhotoPath'])): ?>
                <div class="post-image">
                    <img class = "post-image-img" src="<?php echo $post['PhotoPath']; ?>" alt="Post Image">
                </div>
            <?php endif; ?>

            <div class="lik">
                <form class="like" action="like_post.php" method="POST">
                    <input type="hidden" name="postID" value="<?php echo $post['PostID']; ?>">
                    <button type="submit" class="like-btn" name="like"><img class="bulb" src="bulb.svg"></button>
                </form>

                <span class="like-count"><?php echo getLikeCount($post['PostID']); ?> Briliant Points</span>
                
                <button class="like-btn"><img class="bulb" src="comment.svg"></button>

                <span class="like-count"><?php echo countComments($post['PostID']); ?> Comments</span>
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
                            <form class="reply-form" style="display: none;" action="reply_process.php" method="POST">
                                <input type="hidden" name="comment_id" value="<?php echo $comment['CommentID']; ?>">
                                <textarea name="content" class="reply-input" placeholder="reply to <?php echo $comment['Username']; ?>'s comment..." required></textarea>
                                <button type="submit" class="reply-comment-btn">
                                    <img class="send-icon" src="send.svg">
                                </button>
                            </form>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    
        <?php if (isset($_SESSION['username'])): ?>
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
        <?php else: ?>
            <p>Please <a href="login.php">login</a> to comment.</p>
        <?php endif; }?>
    </div>
    

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


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="./javascripts/index.js"></script>

</body>
</html>
