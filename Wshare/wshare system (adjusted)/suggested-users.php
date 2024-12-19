<?php
require_once 'functions.php';
$userID = $_SESSION['user_id'] ?? null;
$suggestedUsers = getSuggestedUsers($userID);
?>

<div class="right-sidebar">

<div class="leaderboard">
    <h3 class="dd-toggle" onclick="toggleVisibility('suggested-users-content')">People You May Know</h3>
    <ul class="dd-content expanded" id="suggested-users-content">
        <?php foreach ($suggestedUsers as $user): ?>
            <li class="post-item">
                <a href="view_user.php?username=<?php echo htmlspecialchars($user['Username']); ?>" class="post-link user-profile-link">
                    <div class="user-avatar">
                        <img src="<?php echo $user['ProfilePic'] ?? 'default_pic.svg'; ?>" alt="Profile Picture">
                    </div>
                    <div class="user-details">
                        <div class="username"><?php echo htmlspecialchars($user['Username']); ?></div>
                        <div class="email"><?php echo htmlspecialchars($user['Email'] ?? ''); ?></div>
                        <div class="mutual-connections">
                            <?php 
                            $mutualCount = getMutualConnectionsCount($userID, $user['UserID']);
                            echo $mutualCount . ' mutual connection' . ($mutualCount != 1 ? 's' : '');
                            ?>
                        </div>
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