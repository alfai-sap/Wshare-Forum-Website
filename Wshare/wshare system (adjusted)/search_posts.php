<?php
require_once 'functions.php';
session_start();

if (isset($_GET['query'])) {
    $search = trim($_GET['query']);
    $posts = searchAndFilterPosts($search);
    
    if (!empty($posts)) {
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

                    <?php 
                    // Get and display tags
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
                    <p class="post_content"><?php echo formatParagraph($post['Content'], 1000, 50); ?></p>

                    <?php
                    // Get and display documents
                    $documents = getDocumentsByPostId($post['PostID']);
                    if (!empty($documents)): ?>
                        <div class="post-documents">
                            <ul>
                                <?php foreach ($documents as $document): ?>
                                    <li><a href="<?php echo htmlspecialchars($document, ENT_QUOTES, 'UTF-8'); ?>" target="_blank"><?php echo basename($document); ?></a></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; 

                    // Handle media display
                    $media = [];
                    if (!empty($post['PhotoPath'])) {
                        $media[] = ['type' => 'image', 'path' => $post['PhotoPath']];
                    }

                    // Add additional images
                    $stmt = $conn->prepare("SELECT ImagePath FROM post_images WHERE PostID = ? ORDER BY DisplayOrder");
                    $stmt->bind_param('i', $post['PostID']);
                    $stmt->execute();
                    $imgResult = $stmt->get_result();
                    while($img = $imgResult->fetch_assoc()) {
                        $media[] = ['type' => 'image', 'path' => $img['ImagePath']];
                    }
                    $stmt->close();

                    // Add videos
                    $stmt = $conn->prepare("SELECT VideoPath FROM post_videos WHERE PostID = ?");
                    $stmt->bind_param('i', $post['PostID']);
                    $stmt->execute();
                    $vidResult = $stmt->get_result();
                    while($video = $vidResult->fetch_assoc()) {
                        $media[] = ['type' => 'video', 'path' => $video['VideoPath']];
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
                                <?php if (isset($_SESSION['user_id']) && hasUserLikedPost($post['PostID'], $_SESSION['user_id'])): ?>
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
    } else {
        echo '<h4 style="color: #007bff; text-align:center; padding:20px;">No results found</h4>';
    }
}
?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Reinitialize media slideshow functionality for search results
    document.querySelectorAll('.post-media-slideshow').forEach(container => {
        // ... existing media slideshow code ...
    });
});
</script>
