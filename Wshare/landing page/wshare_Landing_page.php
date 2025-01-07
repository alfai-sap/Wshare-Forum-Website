<?php
require_once 'dashfunctions.php';
session_start();

// Fetch analytics data
$totalUsers = getTotalUsers();
$newUsersLastMonth = getNewUsersLastMonth();
$totalPosts = getTotalPosts();
$weeklyPosts = getPostsLastWeek();
$mostActiveUsersByPosts = getMostActiveUsersByPosts();
$mostActiveUsersByComments = getMostActiveUsersByComments();
$mostLikedPosts = getMostLikedPosts();
$mostCommentedPosts = getMostCommentedPosts();
$topFollowedUsers = getTopFollowedUsers();
$topFollowers = getTopFollowers();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to WShare</title>
    <link rel="stylesheet" href="wshare_Landing_page.css ?v=<?php echo time(); ?>">
</head>
<body>

<!-- Navbar -->
<nav class="navbar">
    <div class="container">
        <a href="#" class="logo">WShare</a>
        <ul class="nav-links">
            <li><a href="#about">About</a></li>
            <li><a href="#features">Features</a></li>
            <li><a href="#glimpse">Glimpse</a></li>
            <li><a href="../wshare system (adjusted)/signup.php" class="signup-btn">Sign up</a></li>
            <li><a href="../wshare system (adjusted)/login.php" class="signup-btn">Login</a></li>
        </ul>
    </div>
</nav>

<section class="hero-section">
    <div class="hero-text">
        <h1>Welcome to WShare</h1>
        <p>Connect, share, and grow with like-minded individuals.</p>
        <a href="../wshare system (adjusted)/signup.php" class="cta-btn">Join Now</a>
    </div>
</section>

<!-- Features Section -->
<section class="features-section" id="features">
    <div class="container">
        <h2>Explore WShare Features</h2>
        
        <!-- Feature 1: Collaborative Discussions -->
        <div class="feature-item">
            <h3>Collaborative Discussions</h3>
            <p>Start meaningful conversations, share insights, and brainstorm with people who share your interests. Our discussion boards are designed for fluid, interactive conversations that bring ideas to life.</p>
        </div>

        <!-- Feature 2: Dynamic Networking -->
        <div class="feature-item">
            <h3>Dynamic Networking</h3>
            <p>Connect with individuals who can help you grow, both personally and professionally. WShare enables you to find like-minded users, build valuable relationships, and collaborate in a network of passionate individuals.</p>
        </div>

        <!-- Feature 3: Share Your Posts -->
        <div class="feature-item">
            <h3>Share and Be Heard</h3>
            <p>Want to share an idea, experience, or opinion? WShare allows users to create and share posts in a space where people are eager to hear what you have to say. Get feedback, spark discussions, and let your thoughts be seen.</p>
        </div>

        <!-- Feature 4: Stay Informed -->
        <div class="feature-item">
            <h3>Stay Informed</h3>
            <p>Never miss out on the latest posts, updates, and trends within your community. WShare’s notification system keeps you in the loop, so you can engage with relevant content at the right time.</p>
        </div>

        <!-- Feature 5: Build Your Profile -->
        <div class="feature-item">
            <h3>Build Your Profile</h3>
            <p>Create a personalized profile that reflects your interests, skills, and connections. WShare’s profile system is designed to highlight your uniqueness, making it easier for others to connect with you.</p>
        </div>
    </div>
</section>

<!-- Glimpse Section -->
<section class="glimpse-section" id="glimpse">
    <div class="container">
        <h2>Glimpse of WShare</h2><br><br>
        <div class="glimpse-gallery">
            <div class="glimpse-item">
                <img src="comm_sample.png" alt="Platform Glimpse 1">
                <p class="glimpse-description">Discover how our platform makes collaboration seamless and fun.</p>
            </div>
            <div class="glimpse-item">
                <img src="comm_sample2.png" alt="Platform Glimpse 2">
                <p class="glimpse-description">Explore the posts and discussions by our vibrant community members.</p>
            </div>
            <div class="glimpse-item">
                <img src="platform_sample1.png" alt="Platform Glimpse 3">
                <p class="glimpse-description">See what makes WShare the perfect place to connect and share ideas.</p>
            </div>
        </div>
    </div>
</section>

<section class="user-stats-section">
    <div class="container">
        <h2>User Metrics</h2>
        <div class="user-stats">
            <div class="stat-list">
                <h3>Most Active Users (Posts)</h3>
                <ul>
                    <?php foreach ($mostActiveUsersByPosts as $user): ?>
                        <li><?php echo $user['Username']; ?> (<?php echo $user['post_count']; ?> posts)</li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="stat-list">
                <h3>Most Active Users (Comments)</h3>
                <ul>
                    <?php foreach ($mostActiveUsersByComments as $user): ?>
                        <li><?php echo $user['Username']; ?> (<?php echo $user['comment_count']; ?> comments)</li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="stat-list">
                <h3>Most Liked Posts</h3>
                <ul>
                    <?php foreach ($mostLikedPosts as $post): ?>
                        <li><?php echo $post['Title']; ?> (<?php echo $post['like_count']; ?> likes)</li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="stat-list">
                <h3>Most Commented Posts</h3>
                <ul>
                    <?php foreach ($mostCommentedPosts as $post): ?>
                        <li><?php echo $post['Title']; ?> (<?php echo $post['comment_count']; ?> comments)</li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</section>



<!-- Footer -->
<footer class="footer">
    <div class="container">
        <p>&copy; 2024 WShare. All rights reserved.</p>
        <ul class="footer-links">
            <li><a href="#">Privacy Policy</a></li>
            <li><a href="#">Terms of Service</a></li>
            <li><a href="#">Contact Us</a></li>
        </ul>
    </div>
</footer>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const glimpseItems = document.querySelectorAll('.glimpse-item');

    const observer = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('show');
                observer.unobserve(entry.target); // Remove observer after animation triggers
            }
        });
    }, { threshold: 0.1 });

    glimpseItems.forEach(item => {
        observer.observe(item);
    });
});
</script>

</body>
</html>
