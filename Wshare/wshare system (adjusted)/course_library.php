<?php
session_start();
require_once 'functions.php';

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Library - Wshare</title>
    <link rel="stylesheet" href="./css/navbar.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="./css/left-navbar.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="./css/course-library.css?v=<?php echo time(); ?>">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="course-library-container">
        <div class="welcome-section">
            <h1 class="welcome-title">Welcome to Course Library!</h1>
            <p class="welcome-subtitle">Explore a diverse collection of courses and lessons tailored to your academic journey and interests.</p>
            
            <div class="about-feature">
                <button class="about-btn" onclick="toggleAbout()">
                    About this feature
                    <span class="arrow-icon">▼</span>
                </button>
                <div class="about-content" id="aboutContent">
                    <p>The Course Library is your gateway to comprehensive learning resources. Here you'll find:</p>
                    <ul>
                        <li>Curated academic materials</li>
                        <li>Field-specific study guides</li>
                        <li>Interactive learning content</li>
                        <li>Collaborative study resources</li>
                    </ul>
                    <p>Browse through our extensive collection and enhance your learning experience.</p>
                </div>
            </div>

            <a href="#" class="cta-button">
                <span>Start Browsing</span>
                <div class="cta-hover-effect"></div>
            </a>
        </div>
    </div>

    <script>
        function toggleAbout() {
            const content = document.getElementById('aboutContent');
            const btn = document.querySelector('.about-btn');
            const arrow = btn.querySelector('.arrow-icon');
            
            content.classList.toggle('show');
            arrow.style.transform = content.classList.contains('show') ? 'rotate(180deg)' : 'rotate(0)';
        }
    </script>
</body>
</html>
