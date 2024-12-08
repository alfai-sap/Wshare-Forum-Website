
<!-- Right Sidebar -->
<div class="right-sidebar">
    
<?php
$topDailyPosts = getTopDailyPosts();
?>
<!--
<div class="top-daily-posts">
    <h3 onclick="toggleVisibility('daily-posts-list')" class="dd-toggle">Top Daily Posts</h3>
    <ul id="daily-posts-list" class="dd-content expanded">
        <?php foreach ($topDailyPosts as $post): ?>
            <li style="display: flex;">
                <div style="margin-left: 10px;">
                    <img src="<?php echo $post['ProfilePic']; ?>">
                    <p><?php echo htmlspecialchars($post['Title']); ?></p>
                    <small>By <?php echo htmlspecialchars($post['Username']); ?> - <?php echo $post['Engagement']; ?> Engagements</small>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
</div>



<div class="people-you-may-know">
    <h3 onclick="toggleVisibility('people-you-may-know-list')" class="dd-toggle">People You May Know</h3>
    <ul id="people-you-may-know-list" class="dd-content expanded">
        <?php foreach ($peopleYouMayKnow as $person): ?>
            <li style="display: flex;">
                <img src="<?php echo $person['ProfilePic'] ?: 'default-profile-pic.jpg'; ?>" style="height: 30px; width: 30px; border-radius: 50%;">
                <p style="margin-left: 10px;"><?php echo htmlspecialchars($person['Username']); ?></p>
            </li>
        <?php endforeach; ?>
    </ul>
</div>-->

    <div class="leaderboard">
        <h3 onclick="toggleVisibility('top-users')" class="dd-toggle">Top Users</h3>
        <ul id="top-users" class="dd-content expanded"> <!-- Add "expanded" -->
            <?php foreach ($topUsers as $user): ?>
                <li style="display: flex;">
                    <img src="<?php echo $user['ProfilePic']; ?>" style="height: 30px; width: 30px; border-radius: 50%;">
                    <p style="margin: 5px; padding-left: 5px;"><?php echo htmlspecialchars($user['Username']); ?> -</p>
                    <p style="color: #007bff; margin: 5px;"><?php echo htmlspecialchars($user['total_likes']); ?> Points</p>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
    
    <div class="leaderboard">
        <h3 onclick="toggleVisibility('top-communities')" class="dd-toggle">Top Communities</h3>
        <ul id="top-communities" class="dd-content expanded"> <!-- Add "expanded" -->
            <?php foreach ($topCommunities as $community): ?>
                <li><?php echo htmlspecialchars($community['Title']); ?> - <?php echo htmlspecialchars($community['member_count']); ?> members</li>
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
