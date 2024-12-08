<?php
include 'dashFunctions.php';

if (!isset($_GET['id'])) {
    die('Report ID is required');
}

$reportId = $_GET['id'];
$report = getReportDetails($reportId);

if (!$report) {
    die('Report not found');
}

// Fetch additional details based on report type
$targetDetails = [];
if ($report['ReportType'] == 'post') {
    $targetDetails = getPostDetails($report['TargetID']);
} elseif ($report['ReportType'] == 'comment') {
    $targetDetails = getCommentDetails($report['TargetID']);
} elseif ($report['ReportType'] == 'user') {
    $targetDetails = getUserDetails($report['TargetID']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Report</title>
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>
    <?php include 'sidebar.php'; ?>
    
    <div class="container">
        <h1>Report Details</h1>
        <div class="report-details">
            <p><strong>ID:</strong> <?php echo $report['ReportID']; ?></p>
            <p><strong>Type:</strong> <?php echo ucfirst($report['ReportType']); ?></p>
            <p><strong>Reporter:</strong> <?php echo htmlspecialchars($report['ReporterName']); ?></p>
            <p><strong>Violation:</strong> <?php echo htmlspecialchars($report['Violation']); ?></p>
            <p><strong>Status:</strong> <?php echo ucfirst($report['Status']); ?></p>
            <p><strong>Created At:</strong> <?php echo $report['CreatedAt']; ?></p>
            <p><strong>Target Content:</strong> <?php echo htmlspecialchars($report['TargetContent']); ?></p>
            <p><strong>Additional Info:</strong> <?php echo htmlspecialchars($report['AdditionalInfo']); ?></p>
            <?php if ($report['EvidencePhoto']): ?>
                <p><strong>Evidence Photo:</strong></p>
                <img src="<?php echo htmlspecialchars($report['EvidencePhoto']); ?>" alt="Evidence Photo">
            <?php endif; ?>
        </div>

        <?php if ($report['ReportType'] == 'post' && $targetDetails): ?>
            <h2>Post Details</h2>
            <div class="post-container">
                <div class="post">
                    <div class="author-info">
                        <?php $profilePic = getUserProfileById($targetDetails['UserID'])['ProfilePic']; ?>
                        <?php if (!empty($profilePic)): ?>
                            <img class="author_pic" src="<?php echo $profilePic; ?>" alt="Profile Picture">
                        <?php else: ?>
                            <img class="author_pic" src="default_pic.svg" alt="Profile Picture">
                        <?php endif; ?>
                        
                        <div class="unametime" style="display:flex; flex-direction: column;">
                            <div class="unam-time" style="display: flex;">
                                <p class="authorname"><?php echo $targetDetails['Username'] ?? 'Unknown'; ?></p>
                                <p class="timestamp"><?php echo timeAgo($targetDetails['CreatedAt']); ?></p>
                            </div>
                            
                            <p class="timestamp-update">edited <?php echo timeAgo($targetDetails['updatedAt']); ?></p>
                        </div>
                    </div>
                            
                    <?php                                                                  
                        $postID = $targetDetails['PostID'];

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
                    <h3><?php echo $targetDetails['Title']; ?></h3>
                    
                    <p class="post-content"><?php echo $targetDetails['Content']; ?></p>
            
                          
                    <?php if (!empty($targetDetails['PhotoPath'])): ?>
                        <div class="post-image">
                            <img class = "post-image-img" src="<?php echo $targetDetails['PhotoPath']; ?>" alt="Post Image">
                        </div>
                    <?php endif; ?>

                    <div class="lik">
                        <form class="like" action="like_post.php" method="POST">
                            <input type="hidden" name="postID" value="<?php echo $targetDetails['PostID']; ?>">
                            <button type="submit" class="like-btn" name="like"><img class="bulb" src="bulb.svg"></button>
                        </form>

                        <span class="like-count"><?php echo $targetDetails['like_count']; ?> Brilliant Points</span>
                        
                        <button class="like-btn"><img class="bulb" src="comment.svg"></button>

                        <span class="like-count"><?php echo countComments($targetDetails['PostID']); ?> Comments</span>
                    </div>
                </div>
            
                <?php if ($targetDetails['comments']): ?>
                    <div id="comments-label" class="comments-label">
                        <h4>Comments</h4>
                        <p><img id="comments-label-icon" class="comments-label-icon" src="show.svg"></p>
                    </div>

                    <div class="comments" id="comments">
                        <?php foreach ($targetDetails['comments'] as $comment): ?>
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
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php elseif ($report['ReportType'] == 'comment' && $targetDetails): ?>
            <h2>Comment Details</h2>
            <div class="comment-details">
                <p><strong>Content:</strong> <?php echo nl2br(htmlspecialchars($targetDetails['Content'])); ?></p>
                <p><strong>Post Title:</strong> <?php echo htmlspecialchars($targetDetails['PostTitle']); ?></p>
                <p><strong>Author:</strong> <?php echo htmlspecialchars($targetDetails['Username']); ?></p>
            </div>
        <?php elseif ($report['ReportType'] == 'user' && $targetDetails): ?>
            <h2>User Details</h2>
            <div class="user-details">
                <p><strong>Username:</strong> <?php echo htmlspecialchars($targetDetails['Username']); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($targetDetails['Email']); ?></p>
                <p><strong>Joined At:</strong> <?php echo $targetDetails['dateJoined']; ?></p>
            </div>
        <?php endif; ?>
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
</body>
</html>
