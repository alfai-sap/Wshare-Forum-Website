<div class="post-container">
    <div class="post">
        <div class="pic_user" style="display:flex;">
            <img class="author_pic" src="<?php echo $post['ProfilePic'] ?: 'default_pic.svg'; ?>" alt="Profile Picture">
            <div class="user_post_info">
                <div style="display: flex;">
                    <p class="post_username">
                        <a class="post_uname" href="view_user.php?username=<?php echo urlencode($post['Username']); ?>">
                            <?php echo htmlspecialchars($post['Username']); ?>
                        </a>
                    </p>
                    <p class="post_time" style="font-size:smaller; padding-top:9px; margin-left:2px;">
                        <?php echo timeAgo($post['CreatedAt']); ?>
                    </p>
                </div>
            </div>
        </div>
        <h3 class="post_title"><?php echo htmlspecialchars($post['Title']); ?></h3>
        <p class="post_content"><?php echo formatParagraph($post['Content']); ?></p>
        
        <?php if (!empty($post['PhotoPath'])): ?>
            <img src="<?php echo htmlspecialchars($post['PhotoPath']); ?>" alt="Post Image" class="post-image" style="width:100%; height:100%;">
        <?php endif; ?>

        <div class="lik" style="display:flex; padding:10px;">
            <!-- Like button and count -->
            <form class="like" action="like_post.php" method="POST" style="margin:0;">
                <input type="hidden" name="postID" value="<?php echo $post['PostID']; ?>">
                <button type="submit" class="like-btn" name="like" style="background-color:transparent; border:none; padding: 10px;">
                    <img class="bulb" src="<?php echo hasUserLikedPost($post['PostID'], $user_id) ? 'bulb_active.svg' : 'bulb.svg'; ?>" style="height:20px; width:20px;">
                </button>
            </form>
            <span class="like-count" style="display:flex; align-self:center; color:#0056b3;">
                <?php echo getLikeCount($post['PostID']); ?> Brilliant Points
            </span>
            <!-- Comment count -->
            <button class="like-btn" style="background-color:transparent; border:none; padding: 10px;">
                <img class="bulb" src="comment.svg" style="height:20px; width:20px;">
            </button>
            <span class="like-count" style="display:flex; align-self:center; color:#0056b3;">
                <?php echo countComments($post['PostID']); ?> Comments
            </span>
            <!-- View discussion link -->
            <button class="like-btn" style="background-color:transparent; border:none; padding: 10px;">
                <a href="view_post.php?id=<?php echo $post['PostID']; ?>" style="display:flex; align-self:center; text-decoration:none;">
                    <img class="bulb" src="view.svg" style="height:20px; width:20px;">
                    <p class="like-count" style="display:flex; align-self:center; color:#0056b3; margin-left:5px;">See discussion</p>
                </a>
            </button>
        </div>
    </div>
</div>
