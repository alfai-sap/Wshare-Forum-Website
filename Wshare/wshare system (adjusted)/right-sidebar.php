<!-- Right Sidebar -->
<div class="right-sidebar">
    
<?php
$topDailyPosts = getTopDailyPosts();
?>
    <div class="leaderboard">
        <h3 class="dd-toggle">Popular Posts</h3>
        <ul class="popular-posts">
            <?php 
            $popularPosts = getPopularPosts(); // We'll add this function
            foreach ($popularPosts as $post): ?>
                <li class="post-item">
                    <a href="view_post.php?id=<?php echo $post['PostID']; ?>" class="post-link">
                        <div class="post-title"><?php echo htmlspecialchars(substr($post['Title'], 0, 50)) . (strlen($post['Title']) > 50 ? '...' : ''); ?></div>
                        <div class="post-stats">
                            <span class="stat">
                                <img src="bulb_active.svg" class="stat-icon">
                                <?php echo $post['like_count']; ?>
                            </span>
                            <span class="stat">
                                <img src="bookmark_filled.svg" class="stat-icon">
                                <?php echo $post['bookmark_count']; ?>
                            </span>
                        </div>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

    

</div>

<style>
    .dd-toggle {
        cursor: pointer;
        margin-bottom: 0;
    }

    .dd-content {
        margin: 0;
        padding: 0;
        list-style: none;
        max-height: 0; /* Initially collapsed */
        overflow: hidden;
        transition: max-height 0.4s ease-out; /* Smooth transition for expansion */
    }

    .dd-content.expanded {
        max-height: 500px; /* Large enough to fit any content */
    }

    .dd-content li {
        padding: 5px 0;
    }

    .right-sidebar {
        background: #fff;
        padding: 15px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .leaderboard h3 {
        color: #333;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 2px solid #eee;
    }

    .popular-posts {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .post-item {
        margin-bottom: 12px;
        transition: transform 0.2s;
    }

    .post-item:hover {
        transform: translateX(5px);
    }

    .post-link {
        text-decoration: none;
        color: inherit;
        display: block;
        padding: 8px;
        border-radius: 6px;
        background: #f8f9fa;
        transition: background-color 0.2s;
    }

    .post-link:hover {
        background: #f1f3f5;
    }

    .post-title {
        font-size: 0.9rem;
        color: #2c3e50;
        margin-bottom: 5px;
        line-height: 1.4;
    }

    .post-stats {
        display: flex;
        gap: 12px;
        font-size: 0.8rem;
        color: #666;
    }

    .stat {
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .stat-icon {
        width: 14px;
        height: 14px;
        opacity: 0.7;
    }
</style>

<script>
    // Function to toggle smooth visibility of the dropdown content
    function toggleVisibility(id) {
        const element = document.getElementById(id);
        if (element.classList.contains("expanded")) {
            element.classList.remove("expanded"); // Collapse
        } else {
            element.classList.add("expanded"); // Expand
        }
    }
</script>
