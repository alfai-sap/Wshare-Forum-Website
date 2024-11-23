<!-- Right Sidebar -->
<div class="right-sidebar">

    <div class="notifications">
        <h3>Notifications</h3>
        <ul id="notifications-list">
            <?php foreach ($notifications as $notification): ?>
                <li><?php echo htmlspecialchars($notification['Content']); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <div class="leaderboard">
        <h3>Top Users</h3>
        <ul id="top-users">
            <?php foreach ($topUsers as $user): ?>
                <li><?php echo htmlspecialchars($user['Username']); ?> - <?php echo htmlspecialchars($user['total_likes']); ?> likes</li>
            <?php endforeach; ?>
        </ul>
    </div>
    
    <div class="leaderboard">
        <h3>Top Communities</h3>
        <ul id="top-communities">
            <?php foreach ($topCommunities as $community): ?>
                <li><?php echo htmlspecialchars($community['Title']); ?> - <?php echo htmlspecialchars($community['member_count']); ?> members</li>
            <?php endforeach; ?>
        </ul>
    </div>

    
</div>

