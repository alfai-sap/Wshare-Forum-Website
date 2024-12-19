<?php
require_once 'functions.php';
$userID = $_SESSION['user_id'] ?? null;
$topCommunities = getTopCommunities();
?>

<div class="right-sidebar">

<div class="leaderboard">
    <h3 class="dd-toggle" onclick="toggleVisibility('top-communities-content')">Top Communities</h3>
    <ul class="dd-content expanded" id="top-communities-content">
        <?php foreach ($topCommunities as $community): ?>
            <li class="post-item">
                <a href="community_page.php?community_id=<?php echo htmlspecialchars($community['CommunityID']); ?>" class="post-link community-profile-link">
                    <div class="community-details">
                        <div class="community-name"><?php echo htmlspecialchars($community['CommunityName']); ?></div>
                        <div class="community-stats">
                            <span class="members-count"><?php echo number_format($community['MemberCount']); ?> members</span>
                            •
                            <span class="posts-count"><?php echo number_format($community['PostCount']); ?> posts</span>
                        </div>
                    </div>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</div>

</div>

<style>
.community-profile-link {
    display: flex !important;
    align-items: center;
    gap: 12px;
    padding: 12px !important;
}

.community-avatar {
    flex-shrink: 0;
    width: 45px;
    height: 45px;
    background-color: #f0f2f5;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}

.community-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.community-details {
    flex: 1;
    min-width: 0;
}

.community-name {
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 4px;
    font-size: 0.95rem;
}

.community-stats {
    display: flex;
    gap: 8px;
    align-items: center;
    color: #666;
    font-size: 0.8rem;
}

.members-count, .posts-count {
    color: #0056b3;
    font-size: 0.75rem;
}

/* Hover effect */
.community-profile-link:hover {
    background: #f8f9fa;
    transform: translateX(5px);
    transition: transform 0.2s ease-in-out;
}

/* Match the background and header styling */
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
</style>